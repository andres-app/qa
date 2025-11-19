function abrirModalEstado(id) {

  $("#estado_id").val(id);

  // valor por defecto
  $("#estado").val("En Progreso");

  const ahora = new Date().toISOString().slice(0,16);
  $("#fecha_inicio").val(ahora);
  $("#fecha_respuesta").val("");
  $("#observacion").val("");

  $("#modal_estado").modal("show");
}

$("#form_estado").on("submit", function(e){
  e.preventDefault();

  const estado = $("#estado").val();
  let avance = "0%";

  if (estado === "En Progreso") avance = "50%";
  if (estado === "Atendido" || estado === "Cerrado") avance = "100%";

  $.ajax({
      url: "../../controller/actividad.php?op=actualizar_estado",
      type: "POST",
      data: {
        id_actividad: $("#estado_id").val(),
        estado: estado,
        avance: avance,
        fecha_inicio: $("#fecha_inicio").val(),
        fecha_respuesta: $("#fecha_respuesta").val(),
        observacion: $("#observacion").val()
      },
      dataType: "json",
      success: function(res){
          Swal.fire("Listo", "Estado actualizado correctamente", "success");
          $("#modal_estado").modal("hide");
          tabla.ajax.reload();
      },
      error: function(e){
          console.log(e.responseText);
          Swal.fire("Error", "No se pudo actualizar", "error");
      }
  });

});
