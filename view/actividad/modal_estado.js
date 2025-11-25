function abrirModalEstado(id)
{
    $("#estado_id").val(id);

    $.ajax({
        url: "../../controller/actividad.php?op=mostrar",
        type: "POST",
        data: { id_actividad: id },
        dataType: "json",
        success: function (a) {

            let estadoActual = a.estado || "Pendiente";

            let opciones = "";

            if (estadoActual === "Pendiente") {
                opciones = `<option value="En Progreso">En Progreso</option>`;
            }
            else if (estadoActual === "En Progreso") {
                opciones = `<option value="Atendido">Atendido</option>`;
            }
            else if (estadoActual === "Atendido") {
                opciones = `<option value="Cerrado">Cerrado</option>`;
            }
            else if (estadoActual === "Cerrado") {
                opciones = "";
                Swal.fire("Estado final", "Esta actividad ya está cerrada", "info");
                return;
            }

            $("#estado").html(opciones);

            // Mostrar observación solo cuando se pase a Atendido o Cerrado
            $("#observacion_box").hide();

            $("#estado").off("change").on("change", function () {
                const nuevo = $(this).val();
                if (nuevo === "Atendido" || nuevo === "Cerrado") {
                    $("#observacion_box").show();
                } else {
                    $("#observacion_box").hide();
                }
            });

            $("#modal_estado").modal("show");
        }
    });
}


$("#form_estado").on("submit", function (e) {

    e.preventDefault();

    let nuevoEstado = $("#estado").val();
    let obs = $("#observacion").val();

    let avance = "0%";
    let fecha_inicio = null;
    let fecha_respuesta = null;

    const ahora = new Date().toISOString().slice(0, 19).replace("T", " ");

    if (nuevoEstado === "En Progreso") {
        avance = "50%";
        fecha_inicio = ahora;
    }

    if (nuevoEstado === "Atendido") {
        avance = "75%";
    }

    if (nuevoEstado === "Cerrado") {
        avance = "100%";
        fecha_respuesta = ahora;
    }

    $.ajax({
        url: "../../controller/actividad.php?op=actualizar_estado",
        type: "POST",
        data: {
            id_actividad: $("#estado_id").val(),
            estado: nuevoEstado,
            avance,
            fecha_inicio,
            fecha_respuesta,
            observacion: obs
        },
        dataType: "json",
        success: function () {
            $("#modal_estado").modal("hide");
            tabla.ajax.reload(null, false);
            Swal.fire("OK", "Estado actualizado", "success");
        }
    });
});
