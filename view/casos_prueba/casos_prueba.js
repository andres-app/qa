// =======================================================
// VARIABLE GLOBAL
// =======================================================
var tabla;

// =======================================================
// FUNCIÓN PRINCIPAL DE INICIALIZACIÓN
// =======================================================
function init() {
    $("#mnt_form").on("submit", function (e) {
        guardaryeditar(e);
    });

    // Cargar requerimientos en el select al abrir modal
    cargarRequerimientos();
}

// =======================================================
// GUARDAR O EDITAR CASO DE PRUEBA
// =======================================================
function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mnt_form")[0]);
    var id = $("#id_caso_prueba").val();

    $.ajax({
        url: "../../controller/casos_prueba.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (resp) {
            try {
                let data = JSON.parse(resp);
                if (data.success) {
                    Swal.fire("Éxito", data.success, "success");
                    $("#mnt_modal").modal("hide");
                    tabla.ajax.reload();
                } else {
                    Swal.fire("Error", data.error || "No se pudo guardar el caso de prueba", "error");
                }
            } catch (e) {
                console.error("Respuesta inesperada del servidor:", resp);
                Swal.fire("Error", "El servidor devolvió una respuesta inválida.", "error");
            }}
    });
}

// =======================================================
// MOSTRAR CASO DE PRUEBA
// =======================================================
function editar(id) {
    $.post("../../controller/casos_prueba.php?op=mostrar", { id: id }, function (data) {
        data = JSON.parse(data);
        if (data.error) {
            Swal.fire("Error", data.error, "error");
        } else {
            $("#id_caso_prueba").val(data.id_caso_prueba);
            $("#codigo").val(data.codigo);
            $("#nombre").val(data.nombre);
            $("#id_requerimiento").val(data.id_requerimiento).trigger("change");
            $("#tipo_prueba").val(data.tipo_prueba);
            $("#resultado_esperado").val(data.resultado_esperado);
            $("#version").val(data.version);
            $("#descripcion").val(data.descripcion);
            $("#modalLabel").html("Editar Caso de Prueba");
            $("#mnt_modal").modal("show");
        }
    });
}

// =======================================================
// ELIMINAR CASO DE PRUEBA
// =======================================================
function eliminar(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "El caso de prueba será eliminado permanentemente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/casos_prueba.php?op=eliminar", { id: id }, function (data) {
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
$(document).ready(function () {
    tabla = $("#casos_prueba_table").DataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        ajax: {
            url: "../../controller/casos_prueba.php?op=listar",
            type: "GET",
            dataType: "json",
            error: function () {
                Swal.fire("Error", "No se pudo cargar la lista de casos de prueba", "error");
            }
        },
        bDestroy: true,
        responsive: false,
        scrollX: true,
        autoWidth: false,
        bInfo: true,
        iDisplayLength: 10,
        order: [[0, "desc"]],
        columnDefs: [
            { targets: [0], visible: false, searchable: false }, // ID oculto
            {
                targets: 2, // Nombre del Caso
                render: function (data) {
                    const limite = 10;
                    const palabras = (data || "").split(" ");
                    const textoCorto = palabras.length > limite ? palabras.slice(0, limite).join(" ") + "…" : data;
                    return `<div title="${data || ""}">${textoCorto}</div>`;
                }
            },
            {
                targets: -1, // Columna de acciones
                orderable: false,
                searchable: false,
                className: "text-center",
                render: function (id, type, row) {
                    const id_caso = row[0]; // ID de la primera columna (oculta)
                    return `
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-soft-warning btn-sm" title="Editar" onClick="editar(${id_caso})">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button type="button" class="btn btn-soft-danger btn-sm" title="Eliminar" onClick="eliminar(${id_caso})">
                                <i class="bx bx-trash-alt"></i>
                            </button>
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
            sInfo: "Mostrando _TOTAL_ registros",
            sInfoEmpty: "Mostrando 0 registros",
            sInfoFiltered: "(filtrado de _MAX_ registros)",
            sSearch: "Buscar:",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        }
    });

    // Botón nuevo caso
    $("#btnnuevo").on("click", function () {
        $("#id_caso_prueba").val("");
        $("#mnt_form")[0].reset();
        $("#modalLabel").html("Nuevo Caso de Prueba");
        $("#mnt_modal").modal("show");
    });
});

// =======================================================
// RESTAURAR MODAL AL CERRAR
// =======================================================
$("#mnt_modal").on("hidden.bs.modal", function () {
    $("#mnt_form")[0].reset();
    $("#mnt_form select").val("").trigger("change");
    $("#modalLabel").html("Nuevo Caso de Prueba");
});

// =======================================================
// CARGAR REQUERIMIENTOS EN SELECT
// =======================================================
function cargarRequerimientos() {
    $.get("../../controller/requerimiento.php?op=combo_requerimiento", function (data) {
        $("#id_requerimiento").html(data);
        $("#id_requerimiento").select2({
            theme: "bootstrap-5",
            placeholder: "Seleccione un requerimiento",
            allowClear: true
        });
    });
}

init();
