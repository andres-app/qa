function abrirModalEstado(id) {

  $("#estado_id").val(id);

  // Cargar estado actual desde la fila
  const fila = tabla.row($(`button[onclick="abrirModalEstado(${id})"]`).parents('tr')).data();

  $("#estado").val(fila.estado);
  $("#observacion").val("");

  $("#modal_estado").modal("show");
}

$("#form_estado").on("submit", function(e){
  e.preventDefault();

  const estado = $("#estado").val();
  const obs = $("#observacion").val();

  let avance = "0%";
  let fecha_inicio = null;
  let fecha_respuesta = null;

  const ahora = new Date().toISOString().slice(0,19).replace("T"," ");

  if (estado === "En Progreso") {
      avance = "50%";
      fecha_inicio = ahora;
  }

  if (estado === "Atendido" || estado === "Cerrado") {
      avance = "100%";
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
    success: function(){
        $("#modal_estado").modal("hide");
        tabla.ajax.reload();
        Swal.fire("Listo", "Estado actualizado correctamente", "success");
    }
  });

});
