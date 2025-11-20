<?php
require_once("../config/conexion.php");
require_once("../models/Incidencia.php");
require_once("../models/Documentacion.php");


$incidencia = new Incidencia();
$doc = new Documentacion();

header('Content-Type: application/json; charset=utf-8');

$op = $_POST["op"] ?? $_GET["op"] ?? "";
switch ($op) {

    // ============================================================
    // LISTAR
    // ============================================================
    case "listar":

        $datos = $incidencia->listar();

        echo json_encode([
            "data" => $datos,
            "recordsTotal" => count($datos),
            "recordsFiltered" => count($datos)
        ]);

        break;


    // ============================================================
    // GUARDAR
    // ============================================================
    case "guardar":

        $imagenes = json_decode($_POST["imagenes_base64"], true);
        $rutas_finales = [];

        if ($imagenes && count($imagenes) > 0) {
            foreach ($imagenes as $base64) {

                // Extraer datos
                list($type, $data) = explode(';', $base64);
                list(, $data) = explode(',', $data);

                $binario = base64_decode($data);

                // Nombre único
                $filename = uniqid("inc_") . ".png";
                $ruta = "../uploads/incidencias/" . $filename;

                file_put_contents($ruta, $binario);

                $rutas_finales[] = "uploads/incidencias/" . $filename;
            }
        }

        // Guardar todo en el modelo
        $_POST["imagenes"] = json_encode($rutas_finales);

        $nuevo_id = $incidencia->insertar($_POST);

        echo json_encode([
            "status" => "ok",
            "msg" => "Incidencia registrada correctamente",
            "id_incidencia" => $nuevo_id
        ]);
        break;


    // ============================================================
    // EDITAR
    // ============================================================
    case "editar":

        // 1. IMÁGENES YA GUARDADAS
        $imagenes_guardadas = json_decode($_POST["imagenes_guardadas"], true);
        if (!is_array($imagenes_guardadas)) {
            $imagenes_guardadas = [];
        }

        $imagenes_finales = $imagenes_guardadas;

        // 2. RUTA REAL DONDE SE GUARDAN LAS IMÁGENES
        // __DIR__ = controller/
        $rutaBase = __DIR__ . "/../uploads/incidencias/";

        // Crear carpeta si no existe
        if (!file_exists($rutaBase)) {
            mkdir($rutaBase, 0777, true);
        }

        // 3. SUBIR NUEVAS IMÁGENES
        if (!empty($_FILES["imagenes_nuevas"]["name"][0])) {

            for ($i = 0; $i < count($_FILES["imagenes_nuevas"]["name"]); $i++) {

                // Limpieza del nombre del archivo
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_.-]/', '_', $_FILES["imagenes_nuevas"]["name"][$i]);
                $nombreFinal = time() . "_" . $nombreLimpio;

                // Ruta completa en el servidor (para mover el archivo)
                $rutaServidor = $rutaBase . $nombreFinal;

                // Ruta que se guardará en la BD
                $rutaPublica = "uploads/incidencias/" . $nombreFinal;

                // Mover archivo
                if (move_uploaded_file($_FILES["imagenes_nuevas"]["tmp_name"][$i], $rutaServidor)) {
                    $imagenes_finales[] = $rutaPublica;
                }
            }
        }

        // 4. DATA A ACTUALIZAR
        $data = [
            "id_incidencia" => $_POST["id_incidencia"],
            "descripcion" => $_POST["descripcion"],
            "accion_recomendada" => $_POST["accion_recomendada"],
            "tipo_incidencia" => $_POST["tipo_incidencia"],
            "prioridad" => $_POST["prioridad"],
            "base_datos" => $_POST["base_datos"],
            "version_origen" => $_POST["version_origen"],
            "id_modulo" => $_POST["id_modulo"],
            "estado_incidencia" => $_POST["estado_incidencia"],
            "imagenes" => json_encode($imagenes_finales)
        ];

        // 5. ACTUALIZAR
        $inc = new Incidencia();
        $inc->actualizar($data);

        echo json_encode(["status" => "ok"]);
        break;





    // ============================================================
    // MOSTRAR
    // ============================================================
    case "mostrar":
        echo json_encode($incidencia->mostrar($_POST["id_incidencia"]));
        break;

    // ============================================================
    // ACTUALIZAR ESTADO
    // ============================================================
    case "actualizar_estado":
        $incidencia->actualizar_estado($_POST["id_incidencia"], $_POST["estado"]);
        echo json_encode(["status" => "ok"]);
        break;

    // ============================================================
    // CORRELATIVO
    // ============================================================
    case "correlativo":
        $data = $incidencia->get_correlativo();
        echo json_encode(["id_incidencia" => $data], JSON_UNESCAPED_UNICODE);
        break;

    // ============================================================
    // COMBO DOCUMENTACIÓN
    // ============================================================
    case "combo_documentacion":
        echo json_encode($doc->listar());
        break;

    case "subir_imagen_unica":

        // carpeta real donde guardas las imágenes
        $rutaBase = __DIR__ . "/../uploads/incidencias/";

        // crear carpeta si no existe
        if (!file_exists($rutaBase)) {
            mkdir($rutaBase, 0777, true);
        }

        $files = $_FILES["imagenes_nuevas"];
        $rutaFinal = "";

        for ($i = 0; $i < count($files["name"]); $i++) {

            // limpiar nombre de archivo
            $nombreLimpio = preg_replace('/[^A-Za-z0-9_.-]/', '_', $files["name"][$i]);
            $nombreFinal = time() . "_" . $nombreLimpio;

            // ruta completa para guardar
            $rutaServidor = $rutaBase . $nombreFinal;

            // ruta pública para BD
            $rutaPublica = "uploads/incidencias/" . $nombreFinal;

            if (move_uploaded_file($files["tmp_name"][$i], $rutaServidor)) {
                $rutaFinal = $rutaPublica;
            }
        }

        echo json_encode([
            "status" => "ok",
            "ruta" => $rutaFinal
        ]);
        exit;



    // ============================================================
    // ANULAR (NO ELIMINAR)
    // ============================================================
    case "eliminar":

        $id = $_POST["id_incidencia"];

        $result = $incidencia->anular($id);

        if ($result) {
            echo json_encode(["success" => "Incidencia anulada correctamente"]);
        } else {
            echo json_encode(["error" => "No se pudo anular la incidencia"]);
        }
        break;

    case "correlativo_doc":
        $id_documentacion = $_POST["id_documentacion"];
        $nro = $incidencia->generar_correlativo_doc($id_documentacion);
        echo json_encode(["correlativo" => $nro]);
        break;

    case 'subir_imagen':
        if (isset($_FILES['file'])) {

            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $new_name = uniqid("img_") . "." . $ext;

            $ruta = "../uploads/incidencias/" . $new_name;

            move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
            echo json_encode([
                "status" => "ok",
                "url" => "../../uploads/incidencias/" . $new_name
            ]);
        }
        break;

    case "agregar_seguimiento":

        file_put_contents("debug_seguimiento.txt", "LLEGA OK");

        $incidencia->agregar_seguimiento(
            $_POST["id_incidencia"],
            $_SESSION["usu_id"],
            $_POST["comentario"]
        );

        echo json_encode(["status" => "ok"]);
        break;

    case "listar_seguimiento":
        echo json_encode(
            $incidencia->listar_seguimiento($_POST["id_incidencia"])
        );
        break;




    // ============================================================
    // DEFAULT
    // ============================================================
    default:
        echo json_encode(["status" => "error", "msg" => "Operación no válida"]);
        break;
}
?>