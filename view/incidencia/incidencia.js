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
// GUARDAR O EDITAR INCIDENCIA
// =======================================================
function guardaryeditar(e) {
  e.preventDefault();

  var formData = new FormData($("#mnt_form")[0]);

  $.ajax({
    url: "../../controller/incidencia.php?op=guardar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (data) {
      if (data.status === "ok") {
        Swal.fire("Éxito", "Incidencia registrada correctamente", "success");
        $("#mnt_modal").modal("hide");
        $("#mnt_form")[0].reset();
        tabla.ajax.reload();
      } else {
        Swal.fire("Error", data.msg || "No se pudo registrar la incidencia", "error");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error AJAX:", error);
      Swal.fire("Error", "No se pudo guardar la incidencia", "error");
    }
  });
}

// =======================================================
// MOSTRAR INCIDENCIA (para futuro uso de edición)
// =======================================================
function mostrar(id_incidencia) {
  $.ajax({
    url: "../../controller/incidencia.php?op=mostrar",
    type: "POST",
    data: { id_incidencia: id_incidencia },
    dataType: "json",
    success: function (data) {
      if (data) {
        $("#id_incidencia").val(data.id_incidencia);
        $("#actividad").val(data.actividad);
        $("#descripcion").val(data.descripcion);
        $("#accion_recomendada").val(data.accion_recomendada);
        $("#prioridad").val(data.prioridad);
        $("#tipo_incidencia").val(data.tipo_incidencia);
        $("#base_datos").val(data.base_datos);
        $("#modulo").val(data.modulo);
        $("#version_origen").val(data.version_origen);
        $("#fecha_registro").val(data.fecha_registro);
        $("#fecha_recepcion").val(data.fecha_recepcion);
        $("#mnt_modal").modal("show");
        $("#modalLabel").html("Editar Incidencia");
      }
    },
    error: function (xhr, status, error) {
      Swal.fire("Error", "No se pudo mostrar la incidencia", "error");
    }
  });
}

// =======================================================
// ELIMINAR INCIDENCIA (si implementas botón eliminar)
// =======================================================
function eliminar(id_incidencia) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "La incidencia será eliminada permanentemente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33"
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../../controller/incidencia.php?op=eliminar", { id_incidencia: id_incidencia }, function (data) {
        data = JSON.parse(data);
        if (data.success) {
          Swal.fire("Eliminado", data.success, "success");
          tabla.ajax.reload();
        } else {
          Swal.fire("Error", data.error || "No se pudo eliminar la incidencia", "error");
        }
      });
    }
  });
}

// =======================================================
// CONFIGURACIÓN DEL DATATABLE
// =======================================================
$(document).ready(function () {
  cargarDocumentacion(); // 🔹 ahora sí carga el combo al iniciar
  tabla = $("#incidencia_table").DataTable({
    aProcessing: true,
    aServerSide: true,
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
    ajax: {
      url: "../../controller/incidencia.php?op=listar",
      type: "GET",
      dataType: "json",
      error: function () {
        Swal.fire("Error", "No se pudo cargar la lista de incidencias", "error");
      }
    },
    bDestroy: true,
    responsive: false,   // 👈 evita colapso en escritorio
    scrollX: true,       // 👈 activa desplazamiento horizontal
    autoWidth: false,    // 👈 evita anchos erróneos
    bInfo: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
    columnDefs: [
      {
        targets: 0,
        className: "text-center fw-semibold",
        width: "60px"
      },
      {
        // 🔹 Mostrar solo 20 caracteres en la columna Descripción
        targets: 3,
        render: function (data, type, row) {
          if (!data) return "";
          const limite = 20;
          const textoCorto =
            data.length > limite ? data.substring(0, limite) + "…" : data;

          return `
            <div class="descripcion-columna" title="${data}">
                ${textoCorto}
            </div>
          `;
        }
      },

      {
        // 🔹 Mostrar badge de estado con color
        targets: 8,
        render: function (data, type, row) {
          if (!data) return "";
          let badgeStyle = "";
          const estado = data.toLowerCase();

          switch (estado) {
            case "pendiente":
              badgeStyle = "border border-warning text-warning bg-white";
              break;
            case "resuelto":
              badgeStyle = "border border-success text-success bg-white";
              break;
            default:
              badgeStyle = "border border-secondary text-muted bg-white";
          }

          return `<span class="badge rounded-pill ${badgeStyle} px-3 py-2 fw-semibold">${data}</span>`;
        }
      },
      {
        targets: -1,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function (data, type, row) {
          return `
            <div class="d-flex justify-content-center gap-1">
                <a href="detalle.php?id=${row.id_incidencia}" class="btn btn-soft-info btn-sm" title="Ver Detalle">
                    <i class="bx bx-show"></i>
                </a>
      
                <a href="../../controller/incidencia_pdf.php?id=${row.id_incidencia}" 
                   target="_blank" 
                   class="btn btn-soft-primary btn-sm" 
                   title="Generar PDF">
                    <i class="bx bxs-file-pdf"></i>
                </a>
      
                <button class="btn btn-soft-danger btn-sm" 
                        onClick="eliminar(${row.id_incidencia})" 
                        title="Eliminar">
                    <i class="bx bx-trash-alt"></i>
                </button>
            </div>
          `;
        }
      }

    ],

    columns: [
      { data: "id_incidencia", title: "ID" },
      { data: "actividad", title: "Actividad" },
      { data: "modulo", title: "Módulo" },
      { data: "descripcion", title: "Descripción" },
      { data: "analista", title: "Analista" },
      { data: "prioridad", title: "Prioridad" },
      { data: "tipo_incidencia", title: "Tipo" },
      { data: "fecha_registro", title: "Fecha Registro" },
      { data: "estado_incidencia", title: "Estado" },
      { data: null, title: "Acciones" }
    ],
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando un total de _TOTAL_ registros",
      sInfoEmpty: "Mostrando un total de 0 registros",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sSearch: "Buscar:",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior"
      },
      oAria: {
        sSortAscending: ": Activar para ordenar ascendentemente",
        sSortDescending: ": Activar para ordenar descendentemente"
      }
    }
  });

  // =======================================================
  // BOTÓN NUEVA INCIDENCIA
  // =======================================================
  $("#btnnuevo").on("click", function () {
    $("#id_incidencia").val("");
    $("#mnt_form")[0].reset();
    $("#modalLabel").html("Nueva Incidencia");
    $("#mnt_modal").modal("show");

    // 📅 Asignar fecha actual automáticamente
    const hoy = new Date().toISOString().split("T")[0];
    $("#fecha_registro").val(hoy);
    $("#fecha_recepcion").val(hoy);

    // 🔢 Obtener correlativo desde backend
    $.ajax({
      url: "../../controller/incidencia.php?op=correlativo",
      type: "POST",
      dataType: "json",
      success: function (info) {
        if (info && info.id_incidencia !== undefined) {
          $("#id_incidencia_visible").val(info.id_incidencia);
          $("#id_incidencia").val(info.id_incidencia);
        }
      },
      error: function (xhr) {
        console.error("Error correlativo:", xhr.responseText);
      }
    });
  });
});

// =======================================================
// CARGAR COMBO DE DOCUMENTACIÓN ASOCIADA
// =======================================================
function cargarDocumentacion() {
  $.ajax({
    url: "../../controller/documentacion.php?op=combo",
    type: "GET",
    dataType: "json",
    success: function (docs) {
      console.log("📁 Documentación recibida:", docs);

      let $select = $("#id_documentacion");
      $select.empty().append('<option value="">Seleccione documentación</option>');

      if (Array.isArray(docs) && docs.length > 0) {
        docs.forEach(function (d) {
          let nombre = d.nombre || "Sin nombre";
          let fecha = d.fecha_recepcion || "";
          $select.append(
            `<option value="${d.id_documentacion}" data-fecha="${fecha}">
                ${nombre}
            </option>`
          );
        });
      } else {
        $select.append('<option value="">No hay documentos disponibles</option>');
      }
    },
    error: function (xhr, status, error) {
      console.error("❌ Error cargando documentación:", error);
      Swal.fire("Error", "No se pudo cargar la documentación asociada", "error");
    }
  });
}

// =======================================================
// ACTUALIZAR FECHA DE RECEPCIÓN AL SELECCIONAR DOCUMENTO
// =======================================================
$("#id_documentacion").on("change", function () {
  const fecha = $(this).find(":selected").data("fecha") || new Date().toISOString().split("T")[0];
  $("#fecha_recepcion").val(fecha);
});





// =======================================================
// EJECUTAR INICIALIZACIÓN
// =======================================================
init();
