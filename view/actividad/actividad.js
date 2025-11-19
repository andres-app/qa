// =======================================================
// VARIABLE GLOBAL
// =======================================================
var tabla;

// =======================================================
// FUNCIÓN PRINCIPAL
// =======================================================
function init() {
  $("#mnt_form").on("submit", function (e) {
    guardaryeditar(e);
  });

}

// =======================================================
// GUARDAR O EDITAR ACTIVIDAD
// =======================================================
function guardaryeditar(e) {
  e.preventDefault();

  var formData = new FormData($("#mnt_form")[0]);

  $.ajax({
    url: "../../controller/actividad.php?op=guardar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (data) {
      if (data.status === "ok") {
        Swal.fire("Éxito", "Actividad registrada correctamente", "success");
        $("#mnt_modal").modal("hide");
        $("#mnt_form")[0].reset();
        tabla.ajax.reload();
      } else {
        Swal.fire("Error", data.msg || "No se pudo registrar la actividad", "error");
      }
    },
    error: function () {
      Swal.fire("Error", "Ocurrió un error al guardar la actividad", "error");
    }
  });
}

// =======================================================
// MOSTRAR ACTIVIDAD (para EDITAR)
// =======================================================
function mostrar(id_actividad) {
  $.ajax({
    url: "../../controller/actividad.php?op=mostrar",
    type: "POST",
    data: { id_actividad },
    dataType: "json",
    success: function (data) {

      $("#id_actividad").val(data.id_actividad);
      $("#id_actividad_visible").val(data.id_actividad);

      $("#colaborador_id").val(data.colaborador_id);
      $("#actividad").val(data.actividad);
      $("#descripcion").val(data.descripcion);
      $("#fecha_recepcion").val(data.fecha_recepcion);
      $("#prioridad").val(data.prioridad);

      $("#modalLabel").html("Editar Actividad");
      $("#mnt_modal").modal("show");
    }
  });
}

// =======================================================
// ELIMINAR ACTIVIDAD
// =======================================================
function eliminar(id_actividad) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "La actividad será anulada.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, anular",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
        url: "../../controller/actividad.php?op=eliminar",
        type: "POST",
        data: { id_actividad },
        dataType: "json",
        success: function (data) {

          if (data.status === "ok") {
            Swal.fire("Anulado", data.msg, "success");
            tabla.ajax.reload();
          } else {
            Swal.fire("Error", data.msg || "No se pudo anular", "error");
          }

        },
        error: function (xhr) {
          Swal.fire("Error", "Error en el servidor al anular la actividad", "error");
          console.error(xhr.responseText);
        }
      });

    }
  });
}


// =======================================================
// CONFIGURACIÓN DEL DATATABLE
// =======================================================
$(document).ready(function () {
  tabla = $("#actividad_table").DataTable({
    aProcessing: true,
    aServerSide: true,
    dom: "Bfrtip",
    buttons: ["excelHtml5", "pdfHtml5"],
    ajax: {
      url: "../../controller/actividad.php?op=listar",
      type: "GET",
      dataType: "json",
    },
    bDestroy: true,
    responsive: false,
    scrollX: true,
    autoWidth: false,
    order: [[0, "desc"]],
    columnDefs: [
      {
        targets: 3,
        render: function (data) {
          if (!data) return "";
          let limite = 25;
          let corto = data.length > limite ? data.substring(0, limite) + "…" : data;
          return `<span title="${data}">${corto}</span>`;
        },
      },
      {
        targets: 8,
        render: function (data) {
          if (!data) return "";
          let badge = "";
          let estado = data.toLowerCase();

          if (estado === "pendiente") badge = "badge bg-warning text-dark";
          else if (estado === "en progreso") badge = "badge bg-info text-dark";
          else if (estado === "atendido") badge = "badge bg-primary";
          else if (estado === "cerrado") badge = "badge bg-success";

          return `<span class="${badge}">${data}</span>`;
        },
      },
    ],
    columns: [
      { data: "id_actividad" },
      { data: "colaborador" },
      { data: "actividad" },
      { data: "descripcion" },
      { data: "fecha_recepcion" },
      { data: "fecha_inicio" },
      { data: "fecha_respuesta" },
  
      // 👉 Primero ESTADO
      { data: "estado" },
  
      // 👉 Luego AVANCE
      { data: "avance" },
  
      { data: "prioridad" },
  
      {
        data: "id_actividad",
        render: function (data) {
          return `
            <div class="d-flex gap-1 justify-content-center">
              <button class="btn btn-soft-primary btn-sm" onclick="mostrar(${data})" title="Editar">
                <i class="bx bx-edit"></i>
              </button>
              <button class="btn btn-soft-danger btn-sm" onclick="eliminar(${data})" title="Eliminar">
                <i class="bx bx-trash"></i>
              </button>
            </div>
          `;
        }
      }
  ],
  
  });

  // =======================================================
  // BOTÓN NUEVA ACTIVIDAD
  // =======================================================
  $("#btnnuevo").on("click", function () {
    $("#id_actividad").val("");              // 👈 VACÍO para insertar
    $("#mnt_form")[0].reset();
    $("#modalLabel").html("Nueva Actividad");
    $("#mnt_modal").modal("show");

    const hoy = new Date().toISOString().split("T")[0];
    $("#fecha_recepcion").val(hoy);
    $("#fecha_inicio").val(hoy);

    $.ajax({
        url: "../../controller/actividad.php?op=correlativo",
        type: "POST",
        dataType: "json",
        success: function(info) {
            $("#id_actividad_visible").val(info.id); // 👈 Solo visible
            $("#id_actividad").val("");              // 👈 Importante
        }
    });
});


});


// =======================================================
// EJECUTAR INICIALIZACIÓN
// =======================================================
init();
