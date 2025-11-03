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
                        case "observado": // ✅ ahora en minúsculas
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
                targets: -1,
                orderable: false,
                searchable: false,
                className: "text-center",
                render: function (id, type, row) {
                    const id_caso = row[0]; // ID oculto
                    return `
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-soft-warning btn-sm btn-editar" data-estado="${row[5]}" data-id="${id_caso}" title="Editar">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button type="button" class="btn btn-soft-info btn-sm" title="Iteraciones" onClick="irIteraciones(${id_caso})">
                                <i class="bx bx-history"></i>
                            </button>
                            <button type="button" class="btn btn-soft-danger btn-sm btn-eliminar" data-estado="${row[5]}" data-id="${id_caso}" title="Eliminar">
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

        // Valor por defecto del campo Elaborado por
        $("#elaborado_por").val("Equipo de Calidad");

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

    // 🔹 Limpiar buscador de requerimiento
    $("#buscarRequerimiento").val("").prop("readonly", false);
    $("#id_requerimiento").val("");
    $("#resultadosRequerimiento").hide();
    $("#clearRequerimiento").remove();
});


// =======================================================
// CARGAR REQUERIMIENTOS EN SELECT
// =======================================================
let requerimientos = [];
let reqLoaded = false;

// =======================================================
// Cargar requerimientos en formato JSON
// =======================================================
function cargarRequerimientos() {
    $.ajax({
        url: "../../controller/requerimiento.php?op=combo_requerimiento_json",
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (Array.isArray(data)) {
                requerimientos = data;
                reqLoaded = true;
            } else {
                console.error("Respuesta inesperada:", data);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX:", error, xhr.responseText);
        }
    });
}

// =======================================================
// Buscador en tiempo real (solo por código)
// =======================================================
$(document).on("input", "#buscarRequerimiento", function () {
    const q = this.value.trim().toLowerCase();
    const lista = $("#resultadosRequerimiento");
    lista.empty();

    if (q.length < 2) {
        lista.hide();
        return;
    }

    if (!reqLoaded || !Array.isArray(requerimientos)) {
        lista.append('<li class="list-group-item text-muted">Cargando…</li>').show();
        return;
    }

    const matches = requerimientos.filter(r =>
        (r.codigo || "").toLowerCase().includes(q)
    );

    if (matches.length === 0) {
        lista.append('<li class="list-group-item text-muted">Sin resultados</li>').show();
        return;
    }

    matches.forEach(r => {
        const id = r.id_requerimiento || "";
        const codigo = r.codigo || "";
        const nombre = r.nombre || "";
        const html = `
      <li class="list-group-item list-group-item-action" 
          data-id="${id}" 
          data-codigo="${codigo.replace(/"/g, "&quot;")}" 
          data-nombre="${nombre.replace(/"/g, "&quot;")}">
          <strong>${codigo}</strong> — ${nombre}
      </li>`;
        lista.append(html);
    });

    lista.show();
});

// =======================================================
// Seleccionar una opción del buscador
// =======================================================
// =======================================================
// Seleccionar una opción del buscador
// =======================================================
$(document).on("click", "#resultadosRequerimiento li", function () {
    const id = $(this).attr("data-id");
    const codigo = $(this).attr("data-codigo");
    const nombre = $(this).attr("data-nombre");

    // Mostrar selección en el input y bloquearlo
    $("#buscarRequerimiento")
        .val(codigo && nombre ? `${codigo} — ${nombre}` : codigo)
        .prop("readonly", true);

    $("#id_requerimiento").val(id);
    $("#resultadosRequerimiento").hide();

    // Agregar botón X para limpiar
    if (!$("#clearRequerimiento").length) {
        const clearBtn = $('<button type="button" id="clearRequerimiento" class="btn btn-outline-secondary"><i class="bx bx-x"></i></button>');
        $("#buscarRequerimiento").closest(".input-group").append(clearBtn);
    }

    // 🔹 Llamar backend para generar código automático
    $.ajax({
        url: "../../controller/casos_prueba.php?op=generar_codigo",
        type: "POST",
        data: { id_requerimiento: id },
        success: function (resp) {
            try {
                let data = JSON.parse(resp);
                if (data.codigo) {
                    $("#codigo").val(data.codigo).prop("readonly", true);
                } else if (data.error) {
                    Swal.fire("Error", data.error, "error");
                }
            } catch (e) {
                console.error("Error al generar código:", resp);
            }
        }
    });
});


// =======================================================
// Botón para limpiar selección y volver a buscar
// =======================================================
$(document).on("click", "#clearRequerimiento", function () {
    $("#buscarRequerimiento").val("").prop("readonly", false).focus();
    $("#id_requerimiento").val("");
    $("#resultadosRequerimiento").hide();
    $(this).remove();
});

// =======================================================
// Cerrar lista al hacer clic fuera
// =======================================================
$(document).on("click", function (e) {
    const container = $(".position-relative");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        $("#resultadosRequerimiento").hide();
    }
});


function irIteraciones(id_caso) {
    window.location.href = `iteraciones.php?id=${id_caso}`;
}

// =======================================================
// BLOQUEAR EDICIÓN Y ELIMINACIÓN SI EL CASO ESTÁ COMPLETADO
// =======================================================

// Editar
$(document).on("click", ".btn-editar", function () {
    const estado = $(this).data("estado");
    const id = $(this).data("id");

    if (estado && estado.toLowerCase() === "completado") {
        Swal.fire({
            icon: "info",
            title: "Acción no permitida",
            text: "Este caso de prueba está completado y no puede ser editado.",
            confirmButtonText: "Entendido"
        });
    } else {
        editar(id); // ejecuta normalmente si no está completado
    }
});

// Eliminar
$(document).on("click", ".btn-eliminar", function () {
    const estado = $(this).data("estado");
    const id = $(this).data("id");

    if (estado && estado.toLowerCase() === "completado") {
        Swal.fire({
            icon: "info",
            title: "Acción no permitida",
            text: "Este caso de prueba está completado y no puede ser eliminado.",
            confirmButtonText: "Entendido"
        });
    } else {
        eliminar(id); // ejecuta normalmente si no está completado
    }
});


init();
