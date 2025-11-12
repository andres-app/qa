// Cargar combo de Documentación
$.post("../../controller/documentacion.php?op=combo", function (docs) {
    console.log("✅ Documentación recibida:", docs);
    $("#id_documentacion").empty().append('<option value="">Seleccione documentación</option>');
    docs.forEach(d => {
        $("#id_documentacion").append(
            `<option value="${d.id_documentacion}" data-fecha="${d.fecha_recepcion}">
                ${d.nombre}
             </option>`
        );
    });
}, "json");
