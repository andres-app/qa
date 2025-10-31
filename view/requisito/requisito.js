var tabla;

// =======================================================
// FUNCIÓN PRINCIPAL DE INICIALIZACIÓN
// =======================================================
function init() {
    $("#mnt_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

// =======================================================
// GUARDAR O EDITAR REQUISITO
// =======================================================
function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mnt_form")[0]);
    var url = "../../controller/requisito.php?op=guardar";

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
                Swal.fire("Error", data.error || "No se pudo guardar el requisito", "error");
            }
        },
        error: function() {
            Swal.fire("Error", "No se pudo procesar la solicitud", "error");
        }
    });
}

// =======================================================
// EDITAR REQUISITO
// =======================================================
function editar(id) {
    $.post("../../controller/requisito.php?op=mostrar", { id: id }, function(data) {
        data = JSON.parse(data);
        if (data.error) {
            Swal.fire("Error", data.error, "error");
        } else {
            $("#id_requisito").val(data.id_requisito);
            $("#codigo").val(data.codigo);
            $("#nombre").val(data.nombre);
            $("#version").val(data.version);
            $("#descripcion").val(data.descripcion);
            $("#modalLabel").html("Editar Requisito");
            $("#mnt_modal").modal("show");
        }
    });
}

// =======================================================
// ELIMINAR REQUISITO
// =======================================================
function eliminar(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "El requisito será eliminado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/requisito.php?op=eliminar", { id: id }, function(data) {
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
    tabla = $("#requisito_table").DataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        ajax: {
            url: "../../controller/requisito.php?op=listar",
            type: "GET",
            dataType: "json",
            error: function() {
                Swal.fire("Error", "No se pudo cargar la lista de requisitos", "error");
            }
        },
        bDestroy: true,
        responsive: false, // Evita vista comprimida
        scrollX: true,
        autoWidth: false,
        iDisplayLength: 10,
        order: [[0, "desc"]],
        columnDefs: [
            { targets: [0], visible: false, searchable: false }, // id oculto
            { width: "15%", targets: 1 }, // Código
            {
                targets: 2, // Nombre (truncado)
                width: "60%",
                render: function (data, type, row) {
                    if (type === 'display' && data.length > 100) {
                        return `<span title="${data}">${data.substr(0, 100)}...</span>`;
                    }
                    return data;
                }
            },
            { width: "10%", targets: 3 }, // Versión
            { width: "15%", targets: 4 }  // Acciones
        ],
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla",
            sInfo: "Mostrando un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando 0 registros",
            sInfoFiltered: "(filtrado de _MAX_ registros)",
            sSearch: "Buscar:",
            sLoadingRecords: "Cargando...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        }
    });

    // Botón nuevo
    $("#btnnuevo").on("click", function() {
        $("#id_requisito").val("");
        $("#mnt_form")[0].reset();
        $("#modalLabel").html("Nuevo Requisito");
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
    $("#modalLabel").html("Nuevo Requisito");
});

init();
