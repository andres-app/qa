// Variable global para la tabla del DataTable
var tabla;

// =======================================================
// FUNCIÓN PRINCIPAL DE INICIALIZACIÓN
// =======================================================
function init() {
    // Evento submit del formulario
    $("#mnt_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

// =======================================================
// GUARDAR O EDITAR REQUERIMIENTO
// =======================================================
function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mnt_form")[0]);

    // Si existe id_requerimiento -> editar, sino insertar
    var id = $("#id_requerimiento").val();
    var url = id 
        ? "../../controller/requerimiento.php?op=guardar"  // editar
        : "../../controller/requerimiento.php?op=guardar"; // insertar

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(resp) {
            let data = JSON.parse(resp);
            if (data.success) {
                Swal.fire("Éxito", data.success, "success");
                $("#mnt_modal").modal("hide");
                tabla.ajax.reload();
            } else {
                Swal.fire("Error", data.error || "No se pudo guardar el requerimiento", "error");
            }
        },
        error: function() {
            Swal.fire("Error", "No se pudo procesar la solicitud", "error");
        }
    });
}

// =======================================================
// EDITAR REQUERIMIENTO
// =======================================================
function editar(id) {
    $.post("../../controller/requerimiento.php?op=mostrar", { id: id }, function(data) {
        data = JSON.parse(data);
        if (data.error) {
            Swal.fire("Error", data.error, "error");
        } else {
            $("#id_requerimiento").val(data.id_requerimiento);
            $("#codigo").val(data.codigo);
            $("#nombre").val(data.nombre);
            $("#tipo").val(data.tipo);
            $("#prioridad").val(data.prioridad);
            $("#estado_validacion").val(data.estado_validacion);
            $("#version").val(data.version);
            $("#funcionalidad").val(data.funcionalidad);
            $("#modalLabel").html("Editar Requerimiento");
            $("#mnt_modal").modal("show");
        }
    });
}

// =======================================================
// ELIMINAR REQUERIMIENTO
// =======================================================
function eliminar(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "El requerimiento será eliminado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/requerimiento.php?op=eliminar", { id: id }, function(data) {
                data = JSON.parse(data);
                if (data.success) {
                    Swal.fire("Eliminado", data.success, "success");
                    tabla.ajax.reload();
                } else {
                    Swal.fire("Error", data.error, "error");
                }
            });
        }
    });
}

// =======================================================
// CONFIGURACIÓN DEL DATATABLE
// =======================================================
$(document).ready(function() {
    tabla = $("#requerimiento_table").DataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        ajax: {
            url: "../../controller/requerimiento.php?op=listar",
            type: "GET",
            dataType: "json",
            error: function() {
                Swal.fire("Error", "No se pudo cargar la lista de requerimientos", "error");
            }
        },
        bDestroy: true,
        responsive: false,   // 👈 evita colapso en escritorio
        scrollX: true,       // 👈 activa desplazamiento horizontal
        autoWidth: false,    // 👈 evita que calcule anchos erróneos
        bInfo: true,
        iDisplayLength: 10,
        order: [[0, "desc"]],
        columnDefs: [
            { targets: [0], visible: false, searchable: false },
            {
                // 🔹 Controlar texto largo del campo “Nombre” (columna 3)
                targets: 2,
                render: function(data, type, row) {
                    // Muestra el texto completo pero permite saltos de línea controlados
                    const limite = 10; // cantidad máxima de palabras visibles
                    const palabras = data.split(" ");
                    const textoCorto = palabras.length > limite 
                        ? palabras.slice(0, limite).join(" ") + "…" 
                        : data;
        
                    // Tooltip + estilo de bloque
                    return `
                        <div class="nombre-columna" title="${data}">
                            ${textoCorto}
                        </div>
                    `;
                }
            }
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

    // Botón nuevo registro
    $("#btnnuevo").on("click", function() {
        $("#id_requerimiento").val("");
        $("#mnt_form")[0].reset();
        $("#modalLabel").html("Nuevo Requerimiento");
        $("#mnt_modal").modal("show");
    });
});


// =======================================================
// RESTAURAR MODAL AL CERRAR
// =======================================================
$("#mnt_modal").on("hidden.bs.modal", function() {
    $("#mnt_form")[0].reset();
    $("#mnt_form input, #mnt_form select, #mnt_form textarea").prop("disabled", false);
    $(".modal-footer .btn-primary").show();
    $("#modalLabel").html("Nuevo Requerimiento");
});

init();
