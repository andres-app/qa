<?php
class Organo extends Conectar
{
    // ============================================================
    // LISTAR ÓRGANOS ACTIVOS PARA COMBO SELECT
    // ============================================================
    public function get_organos_combo()
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
}
?>
