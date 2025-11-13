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

  cargarColaboradores(); 
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
    error: function (xhr, status, error) {
      console.error("Error AJAX:", error);
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
    data: { id_actividad: id_actividad },
    dataType: "json",
    success: function (data) {
      if (data) {
        $("#id_actividad").val(data.id_actividad);
        $("#id_actividad_visible").val(data.id_actividad);

        $("#colaborador_id").val(data.colaborador_id);
        $("#actividad").val(data.actividad);
        $("#descripcion").val(data.descripcion);

        $("#fecha_recepcion").val(data.fecha_recepcion);
        $("#fecha_inicio").val(data.fecha_inicio);
        $("#fecha_respuesta").val(data.fecha_respuesta);

        $("#estado").val(data.estado);
        $("#avance").val(data.avance);
        $("#prioridad").val(data.prioridad);

        $("#modalLabel").html("Editar Actividad");
        $("#mnt_modal").modal("show");
      }
    },
    error: function () {
      Swal.fire("Error", "No se pudo obtener los datos de la actividad", "error");
    }
  });
}

// =======================================================
// ELIMINAR ACTIVIDAD
// =======================================================
function eliminar(id_actividad) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "La actividad será eliminada permanentemente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33"
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../../controller/actividad.php?op=eliminar",
        { id_actividad: id_actividad },
        function (data) {
          data = JSON.parse(data);
          if (data.success) {
            Swal.fire("Eliminado", data.success, "success");
            tabla.ajax.reload();
          } else {
            Swal.fire("Error", data.error || "No se pudo eliminar la actividad", "error");
          }
        }
      );
    }
  });
}

// =======================================================
// CARGAR COLABORADORES EN EL SELECT
// =======================================================
function cargarColaboradores() {
  $.ajax({
    url: "../../controller/usuario.php?op=combo",
    type: "GET",
    dataType: "json",
    success: function (data) {
      let select = $("#colaborador_id");
      select.empty();
      select.append('<option value="">Seleccione...</option>');

      data.forEach(function (u) {
        select.append(`<option value="${u.usu_id}">${u.usu_nomape}</option>`);
      });
    },
    error: function (xhr) {
      console.error("Error combo colaboradores:", xhr.responseText);
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
      error: function () {
        Swal.fire("Error", "No se pudo cargar la lista de actividades", "error");
      }
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
        }
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
        }
      },
      {
        targets: -1,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function (data, type, row) {
          return `
            <div class="d-flex gap-1 justify-content-center">
              <button class="btn btn-soft-primary btn-sm" onclick="mostrar(${row.id_actividad})" title="Editar">
                <i class="bx bx-edit"></i>
              </button>

              <button class="btn btn-soft-danger btn-sm" onclick="eliminar(${row.id_actividad})" title="Eliminar">
                <i class="bx bx-trash"></i>
              </button>
            </div>
          `;
        }
      }
    ],
    columns: [
      { data: "id_actividad" },
      { data: "colaborador" },
      { data: "actividad" },
      { data: "descripcion" },
      { data: "fecha_recepcion" },
      { data: "fecha_inicio" },
      { data: "fecha_respuesta" },
      { data: "avance" },
      { data: "estado" },
      { data: "prioridad" },
      { data: null }
    ]
  });

  // =======================================================
  // BOTÓN NUEVA ACTIVIDAD
  // =======================================================
  $("#btnnuevo").on("click", function () {
    $("#id_actividad").val("");
    $("#mnt_form")[0].reset();
    $("#modalLabel").html("Nueva Actividad");
    $("#mnt_modal").modal("show");

    // FECHA ACTUAL
    const hoy = new Date().toISOString().split("T")[0];
    $("#fecha_recepcion").val(hoy);
    $("#fecha_inicio").val(hoy);

    // 🔢 Obtener correlativo
    $.ajax({
      url: "../../controller/actividad.php?op=correlativo",
      type: "POST",
      dataType: "json",
      success: function (info) {
        $("#id_actividad_visible").val(info.id);
        $("#id_actividad").val(info.id);
      }
    });
  });
});

// =======================================================
// EJECUTAR INICIALIZACIÓN
// =======================================================
init();
