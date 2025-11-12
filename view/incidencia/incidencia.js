$(function () {
    console.log("incidencia.js cargado");

    // 🔎 Diagnóstico rápido: asegurar que los campos existen
    console.log("#id_incidencia_visible:", $("#id_incidencia_visible").length);
    console.log("#fecha_registro:", $("#fecha_registro").length);
    console.log("#fecha_recepcion:", $("#fecha_recepcion").length);

    // 👉 Se ejecuta SIEMPRE que el modal se vaya a abrir
    $("#mnt_modal").on("show.bs.modal", function () {
        console.log("Modal abriéndose... cargando correlativo y fechas");

        // 📅 Fecha actual
        const hoy = new Date().toISOString().split("T")[0];
        $("#fecha_registro").val(hoy);
        $("#fecha_recepcion").val(hoy); // editable

        // 🔽 Limpia selección de documentación
        $("#id_documentacion").val("");

        // 🔢 Pide el próximo ID al backend
        $.ajax({
            url: "../../controller/incidencia.php?op=correlativo",
            type: "POST",
            dataType: "json"
        })
            .done(function (info) {
                console.log("Correlativo recibido:", info);
                if (info && info.id_incidencia !== undefined) {
                    $("#id_incidencia_visible").val(info.id_incidencia);
                    $("#id_incidencia").val(info.id_incidencia); // oculto (útil si editas/luego guardas)
                } else {
                    console.warn("No vino id_incidencia en la respuesta");
                }
            })
            .fail(function (xhr) {
                console.error("Error correlativo:", xhr.responseText);
            });
    });

    // 📘 Carga de combo de Documentación (una vez)
    $.ajax({
        url: "../../controller/documentacion.php?op=combo",
        type: "POST",
        dataType: "json"
    })
        .done(function (docs) {
            $("#id_documentacion").empty().append('<option value="">Seleccione documentación</option>');
            (docs || []).forEach(d => {
                $("#id_documentacion").append(
                    `<option value="${d.id_documentacion}" data-fecha="${d.fecha_recepcion}">${d.nombre}</option>`
                );
            });
        })
        .fail(function (xhr) {
            console.error("Error combo documentación:", xhr.responseText);
        });

    // 📅 Si eligen documentación, usar su fecha de recepción; si no, dejar la de hoy
    $("#id_documentacion").on("change", function () {
        const fecha = $(this).find(":selected").data("fecha") || new Date().toISOString().split("T")[0];
        $("#fecha_recepcion").val(fecha);
    });
});

$(document).ready(function () {
    $("#btnnuevo").on("click", function () {
      $("#mnt_form")[0].reset();
      $("#mnt_modal").modal("show");
      $("#modalLabel").text("Registro de Incidencia / Actividad");
      $("#tipo_registro").val("Incidencia").trigger("change");
    });
  
    $("#tipo_registro").on("change", function () {
      const tipo = $(this).val();
      const hoy = new Date().toISOString().split("T")[0];
  
      if (tipo === "Actividad") {
        $("#form_incidencia").hide();
        $("#form_actividad").show();
        $.post("../../controller/actividad.php?op=correlativo", d => {
          const info = JSON.parse(d);
          $("#nro_registro").val(info.id);
        });
        $("#fecha_recepcion_act").val(hoy);
      } else {
        $("#form_incidencia").show();
        $("#form_actividad").hide();
        $.post("../../controller/incidencia.php?op=correlativo", d => {
          const info = JSON.parse(d);
          $("#nro_registro").val(info.id);
        });
        $("#fecha_registro, #fecha_recepcion").val(hoy);
      }
    });
  });
  
