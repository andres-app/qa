$(function () {
    const id_caso = $("#id_caso").val();

    // Tabla de iteraciones
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

    function estadoBadge(val){
        if(!val) return "-";
        const map = {
            "Ejecutado": "bg-success",
            "Observado": "bg-warning",
            "No ejecutado": "bg-secondary"
        };
        const cls = map[val] || "bg-secondary";
        return `<span class="badge ${cls}">${val}</span>`;
    }

    // Guardar iteración
    $("#form_iteracion").on("submit", function(e){
        e.preventDefault();

        const payload = {
            id_caso: id_caso,
            ejecutor_nombre: $("#ejecutor_nombre").val().trim(),
            fecha_ejecucion: $("#fecha_ejecucion").val(),
            resultado: $("#resultado").val(),
            comentario: $("#comentario").val().trim(),
            cerrar_caso: $("#cerrar_caso").is(":checked") ? 1 : 0
        };

        if(!payload.resultado){
            Swal.fire("Validación", "Seleccione el estado de la iteración.", "warning");
            return;
        }

        $.post("../../controller/iteraciones.php?op=guardar", payload, function(resp){
            try {
                const data = JSON.parse(resp);
                if(data.success){
                    Swal.fire("Éxito", data.success, "success");
                    tabla.ajax.reload(null, false);
                    $("#comentario").val("");
                    $("#resultado").val("");
                    $("#fecha_ejecucion").val("");
                    $("#cerrar_caso").prop("checked", false);

                    // actualizar badge de estado del caso si vino en la respuesta
                    if(data.estado_caso){
                        $("#badge_estado_caso").text(data.estado_caso);
                        $("#badge_estado_caso").attr("class", "badge " + badgeCasoClass(data.estado_caso));
                    }
                }else{
                    Swal.fire("Error", data.error || "No se pudo guardar la iteración", "error");
                }
            } catch(err){
                console.error(resp);
                Swal.fire("Error", "Respuesta inválida del servidor", "error");
            }
        });
    });

    function badgeCasoClass(est){
        const map = {
            "Pendiente": "bg-secondary",
            "En ejecución": "bg-info",
            "Observado": "bg-warning",
            "Completado": "bg-success"
        };
        return map[est] || "bg-secondary";
    }
});
