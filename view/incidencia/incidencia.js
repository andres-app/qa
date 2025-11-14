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
    text: "La incidencia será ANULADA.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, anular",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
        url: "../../controller/incidencia.php?op=eliminar",
        type: "POST",
        data: { id_incidencia },
        dataType: "json", // 🔥 Importante
        success: function (data) {

          console.log("DATA CRUDA:", data); // 👉 ya es un OBJETO

          if (data.success) {
            Swal.fire("Anulada", data.success, "success").then(() => {
              tabla.ajax.reload(null, false); // 🔥 se refresca sin F5
            });
          } else {
            Swal.fire("Error", data.error || "No se pudo anular", "error");
          }
        },
        error: function (xhr) {
          console.error("Error AJAX:", xhr.responseText);
          Swal.fire("Error", "No se pudo procesar la solicitud", "error");
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
    aServerSide: false,
    processing: true,
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

      // ✔ Columna ID
      {
        targets: 0,
        className: "text-center fw-semibold",
        width: "60px"
      },

      // ✔ Columna N° Doc
      {
        targets: 1,
        className: "text-center fw-semibold",
        width: "60px"
      },

      // ✔ Descripción (recorte a 20 caracteres)
      {
        targets: 5, // ahora sí en la posición correcta
        render: function (data) {
          if (!data) return "";
          const limite = 20;
          const textoCorto = data.length > limite ? data.substring(0, limite) + "…" : data;
          return `
            <div class="descripcion-columna" title="${data}">
                ${textoCorto}
            </div>
          `;
        }
      },

      // ✔ Estado (badge)
      {
        targets: 10, // nueva posición correcta
        render: function (data) {
          if (!data) return "";
          let badgeStyle = "";
          switch (data.toLowerCase()) {
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

      // ✔ Acciones
      {
        targets: 11,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function (data, type, row) {
          return `
            <div class="d-flex justify-content-center gap-1">
                <a href="detalle.php?id=${row.id_incidencia}" 
                   class="btn btn-soft-info btn-sm" title="Ver Detalle">
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
      { data: "id_incidencia", title: "ID" },              // 0
      { data: "correlativo_doc", title: "N° Inc." },        // 1
      { data: "actividad", title: "Actividad" },           // 2
      { data: "documentacion", title: "Documentación" },   // 3
      { data: "modulo", title: "Módulo" },                 // 4
      { data: "descripcion", title: "Descripción" },       // 5
      { data: "analista", title: "Analista" },             // 6
      { data: "prioridad", title: "Prioridad" },           // 7
      { data: "tipo_incidencia", title: "Tipo" },          // 8
      { data: "fecha_registro", title: "Fecha Registro" }, // 9
      { data: "estado_incidencia", title: "Estado" },      // 10
      { data: null, title: "Acciones" }                    // 11
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

  const id_doc = $(this).val();

  // 1️⃣ Actualizar la fecha desde el combo
  const fecha = $(this).find(":selected").data("fecha") || new Date().toISOString().split("T")[0];
  $("#fecha_recepcion").val(fecha);

  // 2️⃣ Si no selecciona nada, limpiar el correlativo
  if (!id_doc) {
    $("#correlativo_doc").val("");
    return;
  }

  // 3️⃣ Obtener correlativo por documento desde backend
  $.ajax({
    url: "../../controller/incidencia.php?op=correlativo_doc",
    type: "POST",
    data: { id_documentacion: id_doc },
    dataType: "json",
    success: function (resp) {
      console.log("Correlativo recibido:", resp);
      $("#correlativo_doc").val(resp.correlativo);
    },
    error: function (xhr) {
      console.error("Error obteniendo correlativo_doc:", xhr.responseText);
    }
  });

});






// =======================================================
// EJECUTAR INICIALIZACIÓN
// =======================================================
init();
