function abrirModalEstado(id) {

  $("#estado_id").val(id);

  $.ajax({
    url: "../../controller/actividad.php?op=mostrar",
    type: "POST",
    data: { id_actividad: id },
    dataType: "json",
    success: function (data) {

      $("#estado").val(data.estado || "Pendiente");

      // Guardar las fechas actuales del registro
      $("#fecha_inicio").val(
        data.fecha_inicio ? data.fecha_inicio.replace(" ", "T") : ""
      );

      $("#fecha_respuesta").val(
        data.fecha_respuesta ? data.fecha_respuesta.replace(" ", "T") : ""
      );

      $("#observacion").val(data.observacion || "");

      // Guardar en variables ocultas para no perderlas
      $("#fecha_inicio_actual").val(data.fecha_inicio || "");
      $("#fecha_respuesta_actual").val(data.fecha_respuesta || "");

      $("#modal_estado").modal("show");
    }
  });
}


// =======================================================
// GUARDAR CAMBIO DE ESTADO
// =======================================================

$("#form_estado").on("submit", function (e) {
  e.preventDefault();

  const estado = $("#estado").val();
  const obs = $("#observacion").val();

  let avance = "0%";

  // Traer valores actuales
  let fecha_inicio = $("#fecha_inicio_actual").val();
  let fecha_respuesta = $("#fecha_respuesta_actual").val();

  const ahora = new Date().toISOString().slice(0, 19).replace("T", " ");

  // LÓGICA DE NEGOCIO AUTOMÁTICA
  if (estado === "En Progreso") {
    avance = "50%";

    // SI NO tiene fecha_inicio, recién se la asignamos
    if (!fecha_inicio) {
      fecha_inicio = ahora;
    }
  }

  if (estado === "Atendido" || estado === "Cerrado") {
    avance = "100%";

    // No borrar fecha_inicio, conservarla
    if (!fecha_inicio) {
      fecha_inicio = ahora;
    }

    // siempre marcar fecha de cierre
    fecha_respuesta = ahora;
  }

  $.ajax({
    url: "../../controller/actividad.php?op=actualizar_estado",
    type: "POST",
    data: {
      id_actividad: $("#estado_id").val(),
      estado: estado,
      avance: avance,
      fecha_inicio: fecha_inicio,
      fecha_respuesta: fecha_respuesta,
      observacion: obs
    },
    dataType: "json",
    success: function () {
      $("#modal_estado").modal("hide");
      tabla.ajax.reload();
      Swal.fire("Correcto", "Estado actualizado correctamente", "success");
    }
  });

});
