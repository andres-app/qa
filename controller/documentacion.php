<?php
require_once("../config/conexion.php");
require_once("../models/Documentacion.php");

$documentacion = new Documentacion();

header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {

    // 🟩 Listar registros para DataTable
    case "listar":
        echo json_encode(["aaData" => $documentacion->listar()]);
        break;

    // 🟦 Registrar o actualizar
    case "guardar":
        if (empty($_POST["id_documentacion"])) {
            $documentacion->insertar(
                $_POST["nombre"],
                $_POST["descripcion"],
                $_POST["fecha_recepcion"],
                $_POST["tipo_documento"]
            );
            echo json_encode(["status" => "ok", "msg" => "Documentación registrada correctamente"]);
        } else {
            $documentacion->actualizar(
                $_POST["id_documentacion"],
                $_POST["nombre"],
                $_POST["descripcion"],
                $_POST["fecha_recepcion"],
                $_POST["tipo_documento"]
            );
            echo json_encode(["status" => "ok", "msg" => "Documentación actualizada correctamente"]);
        }
        break;

    // 🟨 Mostrar un registro específico
    case "mostrar":
        if (!isset($_POST["id_documentacion"])) {
            echo json_encode(["error" => "ID no recibido"]);
            exit;
        }
        echo json_encode($documentacion->mostrar($_POST["id_documentacion"]));
        break;

    // 🟧 Eliminar lógico
    case "eliminar":
        $documentacion->eliminar($_POST["id_documentacion"]);
        echo json_encode(["status" => "ok", "msg" => "Documento eliminado correctamente"]);
        break;

    // 🟪 Combo para otros módulos
    case "combo":
        echo json_encode($documentacion->combo());
        break;

    // 🟥 PDF CONCATENADO PRINCIPAL (este usarás)
    case "pdf":
        require_once("../libs/tcpdf/tcpdf.php");
        require_once("../models/Incidencia.php");

        $inc = new Incidencia();

        $lista = $documentacion->listar_incidencias_x_documentacion($_GET["id_documentacion"]);

        if (count($lista) == 0) {
            die("No hay incidencias para esta documentación.");
        }

        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8", false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 12);

        foreach ($lista as $item) {

            $data = $inc->mostrar($item["id_incidencia"]);

            $pdf->AddPage();

            ob_start();
            include("pdf_incidencia_template.php");
            $html = ob_get_clean();

            $pdf->writeHTML($html, true, false, true, false, '');
        }

        $pdf->Output("Consolidado.pdf", "I");
        exit;
        break;

    // Variante opcional
    case "pdf_consolidado":
        require_once("../models/Incidencia.php");
        require_once("../libs/tcpdf/tcpdf.php");

        $id_documentacion = $_GET["id_documentacion"];

        $inc = new Incidencia();
        $lista = $inc->listar_incidencias_x_documentacion($id_documentacion);

        if (count($lista) == 0) {
            die("No hay incidencias registradas para esta documentación.");
        }

        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8", false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 12);

        foreach ($lista as $fila) {

            $data = $inc->mostrar($fila["id_incidencia"]);

            $pdf->AddPage();

            ob_start();
            include("pdf_incidencia_template.php");
            $html = ob_get_clean();

            $pdf->writeHTML($html, true, false, true, false, '');
        }

        $pdf->Output("Consolidado_Documentacion_" . $id_documentacion . ".pdf", "I");
        exit;
        break;

    // 🚨 DEBE IR AL FINAL
    default:
        echo json_encode(["error" => "Operación no reconocida"]);
        break;
}
?>
