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
            }
        }
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
            $("#version").val(data.version);
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
// MOSTRAR ITERACIONES DE UN CASO DE PRUEBA
// =======================================================
function verIteraciones(id_caso) {
    $("#id_caso_iter").val(id_caso);

    $.post("../../controller/iteraciones.php?op=listar", { id_caso: id_caso }, function (resp) {
        try {
            let data = JSON.parse(resp);
            let html = "";

            if (data.length === 0) {
                html = `<p class="text-muted">No hay iteraciones registradas aún.</p>`;
            } else {
                data.forEach((iter, index) => {
                    html += `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Iteración ${index + 1} - ${iter.estado}</span>
                                <small class="text-muted">${iter.fecha}</small>
                            </div>
                            <p class="mb-1">${iter.comentario || "Sin comentarios"}</p>
                            ${iter.estado !== "Completado"
                            ? `<button class="btn btn-sm btn-outline-primary" onClick="nuevaIteracion(${id_caso})">
                                        <i class="bx bx-plus"></i> Nueva Iteración
                                       </button>`
                            : ""
                        }
                            <hr>
                        </div>
                    `;
                });
            }

            $("#timeline_iteraciones").html(html);
            $("#iter_modal").modal("show");

        } catch (e) {
            console.error("Error al cargar iteraciones:", resp);
            Swal.fire("Error", "No se pudo cargar el historial de iteraciones", "error");
        }
    });
}

// =======================================================
// EVENTO: CLIC EN BOTÓN NUEVA ITERACIÓN
// =======================================================
$(document).on("click", "#btn_nueva_iteracion", function () {
    const id_caso = $("#id_caso_iter").val();
    nuevaIteracion(id_caso);
});

// =======================================================
// FUNCIÓN: CREAR NUEVA ITERACIÓN
// =======================================================
function nuevaIteracion(id_caso) {
    Swal.fire({
        title: "Registrar nueva iteración",
        html: `
            <div class="text-start">
                <label class="form-label fw-semibold">Estado</label>
                <select id="estado_iteracion" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="En Ejecución">En Ejecución</option>
                    <option value="Observado">Observado</option>
                    <option value="Completado">Completado</option>
                </select>

                <label class="form-label fw-semibold mt-3">Comentario</label>
                <textarea id="comentario_iteracion" class="form-control" rows="3"
                    placeholder="Ingrese una observación o detalle (opcional)"></textarea>
            </div>
        `,
        focusConfirm: false,
        allowOutsideClick: false,
        stopKeydownPropagation: false,   // ✅ permite escribir libremente
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",

        willOpen: () => {
            // SweetAlert2 bloquea el foco hasta que se abra
            const textArea = document.getElementById("comentario_iteracion");
            textArea.focus();
            textArea.removeAttribute("readonly");
        },

        preConfirm: () => {
            const estado = $("#estado_iteracion").val();
            const comentario = $("#comentario_iteracion").val();
            if (!estado) {
                Swal.showValidationMessage("Debe seleccionar un estado");
            }
            return { estado, comentario };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const estado = result.value.estado;
            const comentario = result.value.comentario;

            $.post("../../../controller/iteraciones.php?op=guardar", {
                id_caso: id_caso,
                estado: estado,
                comentario: comentario
            }, function (resp) {
                try {
                    const data = JSON.parse(resp);
                    if (data.success) {
                        Swal.fire("Éxito", data.success, "success");
                        verIteraciones(id_caso);
                    } else {
                        Swal.fire("Error", data.error || "No se pudo guardar la iteración", "error");
                    }
                } catch (e) {
                    console.error("Error al procesar respuesta:", resp);
                    Swal.fire("Error", "Respuesta inválida del servidor", "error");
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
                    const limite = 20;
                    if (!data) return "";
                    const textoCorto = data.length > limite ? data.substring(0, limite) + "…" : data;
                    return `<div title="${data}">${textoCorto}</div>`;
                }
            },

            {
                targets: 5,
                render: function (data) {
                    if (!data) return "";
                    let badgeStyle = "";
                    let texto = data.toLowerCase();
            
                    switch (texto) {
                        case "pendiente":
                            badgeStyle = "border border-secondary text-secondary bg-white";
                            break;
                        case "en ejecución":
                            badgeStyle = "border border-warning text-warning bg-white";
                            break;
                        case "completado":
                            badgeStyle = "border border-success text-success bg-white";
                            break;
                        default:
                            badgeStyle = "border border-light text-muted bg-white";
                    }
            
                    return `<span class="badge rounded-pill ${badgeStyle} px-3 py-2 fw-semibold">${data}</span>`;
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
                            <button type="button" class="btn btn-soft-info btn-sm" title="Iteraciones" onClick="irIteraciones(${id_caso})">
                                <i class="bx bx-history"></i>
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

        // Estado fijo a Pendiente
        $("#estado_ejecucion").val("Pendiente");

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

function irIteraciones(id_caso) {
    window.location.href = `iteraciones.php?id=${id_caso}`;
}


init();
