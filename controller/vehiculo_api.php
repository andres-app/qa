<?php
require_once("../config/conexion.php");

class Vehiculo extends Conectar {
    
    public function get_total_vehiculos() {
        $conexion = parent::conexion();
        $sql = "SELECT COUNT(*) as total FROM vehiculos";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }
    
    public function get_vehiculos() {
        $conexion = parent::conexion();
        $sql = "SELECT * FROM vehiculos";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get_proximos_mantenimientos() {
        $conexion = parent::conexion();
        $sql = "SELECT * FROM vehiculos WHERE mantenimiento_pendiente = 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Instancia de Vehiculo
$vehiculo = new Vehiculo();

// Verificar si se está ejecutando en localhost y realizar pruebas directas
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    echo "<h3>Pruebas en Localhost</h3>";
    
    // Obtener el total de vehículos
    echo "<h4>Total de Vehículos:</h4>";
    echo $vehiculo->get_total_vehiculos();
    
    // Obtener la lista completa de vehículos
    echo "<h4>Lista de Vehículos:</h4>";
    echo "<pre>";
    print_r($vehiculo->get_vehiculos());
    echo "</pre>";
    
    // Obtener próximos mantenimientos
    echo "<h4>Próximos Mantenimientos:</h4>";
    echo "<pre>";
    print_r($vehiculo->get_proximos_mantenimientos());
    echo "</pre>";
} else {
    // Switch para operaciones basadas en el valor 'op'
    $op = isset($_GET['op']) ? $_GET['op'] : '';

    switch ($op) {
        case 'total':
            $total = $vehiculo->get_total_vehiculos();
            echo json_encode(['total' => $total]);
            break;

        case 'listar':
            $vehiculos = $vehiculo->get_vehiculos();
            echo json_encode($vehiculos);
            break;

        case 'proximos_mantenimientos':
            $proximos = $vehiculo->get_proximos_mantenimientos();
            echo json_encode($proximos);
            break;

        default:
            echo json_encode(['error' => 'Operación no válida']);
            break;
    }
}
?>
