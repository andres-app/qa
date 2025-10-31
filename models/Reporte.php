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
                    cp.estado_ejecucion,
                    cp.resultado
                FROM requisito r
                LEFT JOIN requerimiento rq ON rq.id_requisito = r.id_requisito
                LEFT JOIN caso_prueba cp ON cp.id_requerimiento = rq.id_requerimiento
                ORDER BY r.codigo, rq.codigo, cp.codigo";

        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
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

        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       REPORTE 3: CASOS POR REQUERIMIENTO
       (Total de CP agrupado por requerimiento)
    ============================================================ */
    public function reporte_casos_por_requerimiento()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                    rq.codigo AS codigo_requerimiento,
                    rq.nombre AS nombre_requerimiento,
                    COUNT(cp.id_caso) AS total_casos
                FROM requerimiento rq
                LEFT JOIN caso_prueba cp ON cp.id_requerimiento = rq.id_requerimiento
                GROUP BY rq.codigo, rq.nombre
                ORDER BY rq.codigo";

        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
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
   PORCENTAJE DE CASOS EJECUTADOS
   (estado_ejecucion = 'EJECUTADO')
============================================================ */
    public function get_porcentaje_casos_ejecutados()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN estado_ejecucion = 'EJECUTADO' THEN 1 ELSE 0 END) AS ejecutados
            FROM caso_prueba
            WHERE estado = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = (int) $row["total"];
        $ejecutados = (int) $row["ejecutados"];

        $porcentaje = $total > 0 ? round(($ejecutados / $total) * 100, 2) : 0;

        return [
            "total" => $total,
            "ejecutados" => $ejecutados,
            "porcentaje" => $porcentaje
        ];
    }

    /* ============================================================
    CASOS DE PRUEBA POR ÓRGANO JURISDICCIONAL (USANDO ESPECIALIDAD)
 ============================================================ */
    public function get_casos_por_organo_jurisdiccional()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                e.nombre AS organo_jurisdiccional,
                COUNT(cp.id_caso) AS total_casos
            FROM caso_prueba cp
            INNER JOIN especialidad e ON cp.especialidad_id = e.id_especialidad
            WHERE cp.estado = 1
            GROUP BY e.nombre
            ORDER BY e.nombre ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
   SEGUIMIENTO POR ESPECIALIDAD (Aprobado / En Ejecución / Pendiente)
============================================================ */
    public function get_seguimiento_por_especialidad()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
                e.nombre AS especialidad,
                SUM(CASE WHEN cp.estado_ejecucion = 'APROBADO' THEN 1 ELSE 0 END) AS aprobado,
                SUM(CASE WHEN cp.estado_ejecucion = 'EN EJECUCIÓN' THEN 1 ELSE 0 END) AS en_ejecucion,
                SUM(CASE WHEN cp.estado_ejecucion = 'PENDIENTE' THEN 1 ELSE 0 END) AS pendiente
            FROM caso_prueba cp
            INNER JOIN especialidad e ON cp.especialidad_id = e.id_especialidad
            WHERE cp.estado = 1
            GROUP BY e.nombre
            ORDER BY e.nombre ASC";

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

        $sql = "SELECT 
                e.nombre AS especialidad,
                COUNT(DISTINCT rq.id_requerimiento) AS total_requerimientos,
                COUNT(DISTINCT cp.id_caso) AS total_casos_prueba
            FROM especialidad e
            LEFT JOIN caso_prueba cp ON e.id_especialidad = cp.especialidad_id AND cp.estado = 1
            LEFT JOIN requerimiento rq ON rq.id_requerimiento = cp.id_requerimiento AND rq.estado = 1
            GROUP BY e.nombre
            ORDER BY e.nombre ASC";

        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>