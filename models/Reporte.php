<?php
class Reporte extends Conectar
{

    /* ============================================================
       REPORTE 1: TRAZABILIDAD DETALLADA
       (Requisito → Requerimiento → Caso de prueba)
    ============================================================ */
    public function reporte_trazabilidad()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    r.codigo AS codigo_requisito,
                    r.nombre AS nombre_requisito,
                    rq.codigo AS codigo_requerimiento,
                    rq.nombre AS nombre_requerimiento,
                    cp.codigo AS codigo_caso_prueba,
                    cp.nombre AS nombre_caso_prueba,
                    cp.estado_ejecucion
                FROM requisito r
                LEFT JOIN requerimiento rq ON rq.id_requisito = r.id_requisito
                LEFT JOIN caso_prueba cp ON cp.id_requerimiento = rq.id_requerimiento
                ORDER BY r.codigo, rq.codigo, cp.codigo";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       REPORTE 2: COBERTURA POR REQUISITO
       (Cantidad de requerimientos y casos asociados)
    ============================================================ */
    public function reporte_cobertura()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    r.codigo AS codigo_requisito,
                    r.nombre AS nombre_requisito,
                    COUNT(DISTINCT rq.id_requerimiento) AS total_requerimientos,
                    COUNT(DISTINCT cp.id_caso) AS total_casos_prueba
                FROM requisito r
                LEFT JOIN requerimiento rq ON rq.id_requisito = r.id_requisito AND rq.estado = 1
                LEFT JOIN caso_prueba cp ON cp.id_requerimiento = rq.id_requerimiento AND cp.estado = 1
                GROUP BY r.codigo, r.nombre
                ORDER BY r.codigo";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       TOTAL CASOS DE PRUEBA ACTIVOS
       (Para dashboard principal)
    ============================================================ */
    public function get_total_casos_prueba()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT COUNT(*) AS total FROM caso_prueba WHERE estado = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       PORCENTAJE DE CASOS EJECUTADOS (estado_ejecucion = 'Completado')
    ============================================================ */
    public function get_porcentaje_casos_ejecutados()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN LOWER(TRIM(cp.estado_ejecucion)) = 'completado' THEN 1 ELSE 0 END) AS ejecutados
            FROM caso_prueba cp
            WHERE cp.estado = 1
        ";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = (int) ($row["total"] ?? 0);
        $ejecutados = (int) ($row["ejecutados"] ?? 0);

        $porcentaje = $total > 0 ? round(($ejecutados / $total) * 100, 2) : 0;

        return [
            "total" => $total,
            "ejecutados" => $ejecutados,
            "porcentaje" => $porcentaje
        ];
    }

    /* ============================================================
       CASOS DE PRUEBA POR ÓRGANO JURISDICCIONAL
       (Usando tablas relacionales nuevas)
    ============================================================ */
    public function get_casos_por_organo_jurisdiccional()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "
            SELECT 
                o.id_organo,
                o.nombre AS organo_jurisdiccional,
                COUNT(DISTINCT cp.id_caso) AS total_casos
            FROM organo_jurisdiccional o
            LEFT JOIN requerimiento_organo ro  ON ro.id_organo = o.id_organo
            LEFT JOIN requerimiento r          ON r.id_requerimiento = ro.id_requerimiento
            LEFT JOIN caso_prueba cp           ON cp.id_requerimiento = r.id_requerimiento
            WHERE o.estado = 1
            GROUP BY o.id_organo, o.nombre
            ORDER BY o.nombre ASC
        ";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_detalle_por_organo($id_organo = null)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    o.nombre AS organo_jurisdiccional,
                    r.codigo AS codigo_requerimiento,
                    r.nombre AS nombre_requerimiento,
                    cp.codigo AS codigo_caso,
                    cp.nombre AS nombre_caso,
                    cp.version,
                    cp.estado_ejecucion,
                    COALESCE(cp.creado_por, 'Equipo QA') AS responsable,
                    DATE(cp.fecha_creacion) AS fecha_registro
                FROM organo_jurisdiccional o
                INNER JOIN requerimiento_organo ro ON ro.id_organo = o.id_organo
                INNER JOIN requerimiento r ON r.id_requerimiento = ro.id_requerimiento
                INNER JOIN caso_prueba cp ON cp.id_requerimiento = r.id_requerimiento
                WHERE o.estado = 1";

        if (!empty($id_organo)) {
            $sql .= " AND o.id_organo = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $id_organo, PDO::PARAM_INT);
        } else {
            $stmt = $conectar->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function get_organos_activos()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT id_organo, nombre 
            FROM organo_jurisdiccional 
            WHERE estado = 1 
            ORDER BY nombre ASC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    /* ============================================================
       SEGUIMIENTO POR ESPECIALIDAD
       (Usando relación requerimiento → especialidad)
    ============================================================ */
    public function get_seguimiento_por_especialidad()
{
    $conectar = parent::conexion();
    parent::set_names();

    $sql = "
        SELECT 
            COALESCE(e.nombre, 'Sin especialidad') AS especialidad,

            -- ✅ Casos completados
            SUM(CASE 
                WHEN UPPER(TRIM(cp.estado_ejecucion)) = 'COMPLETADO' 
                THEN 1 ELSE 0 END
            ) AS completado,

            -- ✅ Casos observados
            SUM(CASE 
                WHEN UPPER(TRIM(cp.estado_ejecucion)) = 'OBSERVADO' 
                THEN 1 ELSE 0 END
            ) AS observado,

            -- ✅ Casos pendientes o en ejecución
            SUM(CASE 
                WHEN UPPER(TRIM(cp.estado_ejecucion)) IN ('PENDIENTE', 'EN EJECUCION', 'EN EJECUCIÓN') 
                THEN 1 ELSE 0 END
            ) AS pendiente

        FROM caso_prueba cp
        LEFT JOIN requerimiento r 
            ON cp.id_requerimiento = r.id_requerimiento
        LEFT JOIN requerimiento_especialidad re 
            ON re.id_requerimiento = r.id_requerimiento
        LEFT JOIN especialidad e 
            ON e.id_especialidad = re.id_especialidad
        GROUP BY e.nombre
        ORDER BY e.nombre ASC
    ";

    $stmt = $conectar->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* ============================================================
       REPORTE CONSOLIDADO POR ESPECIALIDAD
       (Total de requerimientos y casos de prueba)
    ============================================================ */
    public function get_resumen_por_especialidad()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "
            SELECT 
                e.nombre AS especialidad,
                COUNT(DISTINCT r.id_requerimiento) AS total_requerimientos,
                COUNT(DISTINCT cp.id_caso) AS total_casos_prueba
            FROM especialidad e
            LEFT JOIN requerimiento_especialidad re 
                ON re.id_especialidad = e.id_especialidad
            LEFT JOIN requerimiento r 
                ON r.id_requerimiento = re.id_requerimiento
            LEFT JOIN caso_prueba cp 
                ON cp.id_requerimiento = r.id_requerimiento
            WHERE e.estado = 1
            GROUP BY e.id_especialidad, e.nombre
            ORDER BY e.nombre ASC
        ";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    // ==========================================================
//  OBTENER ESPECIALIDADES ACTIVAS
// ==========================================================
    public function get_especialidades_activas()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT id_especialidad, nombre 
            FROM especialidad 
            WHERE estado = 1 
            ORDER BY nombre ASC;";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_analisis_funcionalidad()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    organo_jurisdiccional,
                    funcionalidad,
                    COUNT(DISTINCT codigo_requisito) AS total_requisitos,
                    COUNT(DISTINCT codigo_requerimiento) AS total_requerimientos
                FROM analisis_funcionalidad
                GROUP BY organo_jurisdiccional, funcionalidad
                ORDER BY organo_jurisdiccional";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


 // ==========================================================
//  LISTAR CASOS DE PRUEBA POR ESPECIALIDAD (USANDO TABLA INTERMEDIA)
// ==========================================================
public function get_casos_por_especialidad($id_especialidad = "", $estado = "") {
    $conectar = parent::conexion();
    parent::set_names();

    $sql = "SELECT 
                e.nombre AS especialidad,
                r.codigo AS codigo_requerimiento,
                r.nombre AS nombre_requerimiento,
                c.codigo AS codigo_caso,
                c.nombre AS nombre_caso,
                c.version,
                c.estado_ejecucion AS estado,
                c.elaborado_por AS responsable,
                DATE_FORMAT(c.fecha_creacion, '%d/%m/%Y') AS fecha_registro
            FROM caso_prueba c
            INNER JOIN requerimiento r 
                ON c.id_requerimiento = r.id_requerimiento
            INNER JOIN requerimiento_especialidad re 
                ON re.id_requerimiento = r.id_requerimiento
            INNER JOIN especialidad e 
                ON re.id_especialidad = e.id_especialidad
            WHERE 1=1";

    // 🔹 Filtrar por especialidad si se selecciona una
    if (!empty($id_especialidad)) {
        $sql .= " AND e.id_especialidad = :id_especialidad";
    }

    // 🔹 Filtrar por estado de ejecución
    if (!empty($estado) && $estado != "Todos") {
        $sql .= " AND c.estado_ejecucion = :estado";
    }

    $sql .= " ORDER BY e.nombre, r.codigo, c.codigo";

    $stmt = $conectar->prepare($sql);

    if (!empty($id_especialidad)) {
        $stmt->bindParam(":id_especialidad", $id_especialidad, PDO::PARAM_INT);
    }
    if (!empty($estado) && $estado != "Todos") {
        $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Formatear estado con badge visual
    foreach ($result as &$row) {
        $badgeClass = match ($row["estado"]) {
            "Completado"   => "bg-success",
            "Observado"    => "bg-warning text-dark",
            "En ejecución" => "bg-info text-dark",
            default        => "bg-secondary"
        };
        $row["estado_badge"] = "<span class='badge {$badgeClass}'>{$row["estado"]}</span>";
    }

    return ["aaData" => $result];
}






}

