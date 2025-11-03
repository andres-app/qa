$(function () {

    // =======================================================
    // Si el caso está completado, ocultar el formulario
    // =======================================================
    const estadoCasoActual = $("#badge_estado_caso").text().trim();
    if (estadoCasoActual === "Completado") {
        $("#form_iteracion").hide();
        $(".card:has(#form_iteracion) .card-header").append(
            `<div class="alert alert-info mt-3 mb-0 text-center">
            Este caso de prueba ya está <strong>Completado</strong> y no admite nuevas iteraciones.
        </div>`
        );
    }

    const id_caso = $("#id_caso").val();

    // =======================================================
    // Tabla de iteraciones
    // =======================================================
    const tabla = $("#iteraciones_table").DataTable({
        aProcessing: true,
        aServerSide: false,
        ajax: {
            url: "../../controller/iteraciones.php?op=listar",
            type: "POST",
            data: { id_caso }
        },
        columns: [
            { data: "numero_iteracion", render: d => `${d}ª Iteración` },
            { data: "fecha_ejecucion", defaultContent: "-" },
            { data: "ejecutor_nombre", defaultContent: "-" },
            { data: "resultado", render: estadoBadge },
            { data: "comentario", defaultContent: "" }
        ],
        order: [[0, "asc"]],
        language: {
            sZeroRecords: "Sin iteraciones registradas",
            sInfo: "Mostrando _TOTAL_ registros",
            sSearch: "Buscar:",
            oPaginate: { sNext: "Siguiente", sPrevious: "Anterior" }
        }
    });

    // =======================================================
    // Cambiar comportamiento del checkbox según el estado seleccionado
    // =======================================================
    $("#resultado").on("change", function () {
        if ($(this).val() === "Ejecutado") {
            $("#cerrar_caso").prop("checked", true);   // Se marca automáticamente
        } else {
            $("#cerrar_caso").prop("checked", false);  // Se desmarca en otros casos
        }
    });

    // =======================================================
    // Función para mostrar badge de estado
    // =======================================================
    function estadoBadge(val) {
        if (!val) return "-";
        const map = {
            "Ejecutado": "bg-success",
            "Observado": "bg-warning",
            "No ejecutado": "bg-secondary"
        };
        const cls = map[val] || "bg-secondary";
        return `<span class="badge ${cls}">${val}</span>`;
    }

    // =======================================================
    // Guardar iteración
    // =======================================================
    $("#form_iteracion").on("submit", function (e) {
        e.preventDefault();

        const payload = {
            id_caso: id_caso,
            ejecutor_nombre: $("#ejecutor_nombre").val().trim(),
            fecha_ejecucion: $("#fecha_ejecucion").val(),
            resultado: $("#resultado").val(),
            comentario: $("#comentario").val().trim(),
            cerrar_caso: $("#cerrar_caso").is(":checked") ? 1 : 0
        };

        if (!payload.resultado) {
            Swal.fire("Validación", "Seleccione el estado de la iteración.", "warning");
            return;
        }

        $.post("../../controller/iteraciones.php?op=guardar", payload, function (resp) {
            try {
                const data = JSON.parse(resp);
                if (data.success) {
                    Swal.fire("Éxito", data.success, "success");
                    tabla.ajax.reload(null, false);
                    $("#comentario").val("");
                    $("#resultado").val("");
                    $("#fecha_ejecucion").val("");
                    $("#cerrar_caso").prop("checked", false);

                    // =======================================================
                    // Actualizar badge de estado del caso y bloquear si se completó
                    // =======================================================
                    if (data.estado_caso) {
                        $("#badge_estado_caso").text(data.estado_caso);
                        $("#badge_estado_caso").attr("class", "badge " + badgeCasoClass(data.estado_caso));

                        // Si el caso se completó, ocultar formulario dinámicamente
                        if (data.estado_caso === "Completado") {
                            $("#form_iteracion").hide();
                            $(".card:has(#form_iteracion) .card-header").append(
                                `<div class="alert alert-info mt-3 mb-0 text-center">
                Este caso de prueba ya está <strong>Completado</strong> y no admite nuevas iteraciones.
            </div>`
                            );
                        }
                    }

                } else {
                    Swal.fire("Error", data.error || "No se pudo guardar la iteración", "error");
                }
            } catch (err) {
                console.error(resp);
                Swal.fire("Error", "Respuesta inválida del servidor", "error");
            }
        });
    });

    // =======================================================
    // Mapeo de colores del estado del caso
    // =======================================================
    function badgeCasoClass(est) {
        const map = {
            "Pendiente": "bg-secondary",
            "En ejecución": "bg-info",
            "Observado": "bg-warning",
            "Completado": "bg-success"
        };
        return map[est] || "bg-secondary";
    }
});
