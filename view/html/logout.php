<?php
require_once("../../config/conexion.php");
session_start();  // Asegúrate de iniciar la sesión

// Guarda el rol_id en una variable antes de destruir la sesión
$rol_id = isset($_SESSION["rol_id"]) ? $_SESSION["rol_id"] : null;

// Destruye la sesión
session_destroy();

// Redirige según el rol_id
if ($rol_id == 1) {
    header("Location:".Conectar::ruta()."index.php");
} elseif (in_array($rol_id, [2, 3, 4, 5])) { //Aca se agregan mas roles que podamos permitir cerrar la sesion
    header("Location:".Conectar::ruta()."view/accesopersonal/index.php");
} else {
    // Si no hay rol definido o es otro caso, puedes redirigir a una página genérica
    header("Location:".Conectar::ruta()."view/accesopersonal");
}

exit();
?>
