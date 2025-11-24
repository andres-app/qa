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
                Swal.fire("Error", data.msg || "No se pudo registrar", "error");
            }
        },
        error: function () {
            Swal.fire("Error", "No se pudo guardar la incidencia", "error");
        }
    });
}

// =======================================================
// MOSTRAR INCIDENCIA
// =======================================================
function mostrar(id_incidencia) {
    $.ajax({
        url: "../../controller/incidencia.php?op=mostrar",
        type: "POST",
        data: { id_incidencia },
        dataType: "json",
        success: function (data) {

            cargarModulos();

            setTimeout(() => {
                $("#id_incidencia").val(data.id_incidencia);
                $("#actividad").val(data.actividad);
                $("#descripcion").val(data.descripcion);
                $("#accion_recomendada").val(data.accion_recomendada);
                $("#prioridad").val(data.prioridad);
                $("#tipo_incidencia").val(data.tipo_incidencia);
                $("#base_datos").val(data.base_datos);
                $("#id_modulo").val(data.id_modulo);
                $("#version_origen").val(data.version_origen);
                $("#fecha_registro").val(data.fecha_registro);
                $("#fecha_recepcion").val(data.fecha_recepcion);
            }, 200);

            $("#modalLabel").html("Editar Incidencia");
            $("#mnt_modal").modal("show");
        },
        error: function () {
            Swal.fire("Error", "No se pudo mostrar la incidencia", "error");
        }
    });
}

// =======================================================
// ELIMINAR INCIDENCIA
// =======================================================
function eliminar(id_incidencia) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "La incidencia será anulada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, anular",
        cancelButtonText: "Cancelar"
    }).then(result => {

        if (result.isConfirmed) {
            $.ajax({
                url: "../../controller/incidencia.php?op=eliminar",
                type: "POST",
                data: { id_incidencia },
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        Swal.fire("Anulada", data.success, "success");
                        tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire("Error", data.error, "error");
                    }
                }
            });
        }
    });
}

// =======================================================
// CONFIGURACIÓN DEL DATATABLE
// =======================================================
$(document).ready(function () {

    cargarDocumentacion();
    cargarModulos();

    tabla = $("#incidencia_table").DataTable({
        processing: true,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                exportOptions: {
                    // Orden correcto en Excel (sin botones)
                    columns: [0,1,2,3,4,5,11,6,7,8,9,10],
                
                    format: {
                        body: function (data, row, column) {
                
                            // Estado
                            if (column === 10) {
                                let div = document.createElement("div");
                                div.innerHTML = data;
                                return div.textContent || "";
                            }
                
                            // Documentación
                            if (column === 3) {
                                return tabla.row(row).data().documentacion || "";
                            }
                
                            // Módulo
                            if (column === 4) {
                                return tabla.row(row).data().modulo || "";
                            }
                
                            // Descripción
                            if (column === 5) {
                                return tabla.row(row).data().descripcion || "";
                            }
                
                            // Acción Recomendada (columna oculta real: 11)
                            if (column === 6) { 
                                return tabla.row(row).data().accion_recomendada || "";
                            }
                
                            // Default: limpiar HTML
                            let div = document.createElement("div");
                            div.innerHTML = data;
                            return div.textContent || "";
                        }
                    }
                }  
                
            },
            "pdfHtml5"
        ],        

        ajax: {
            url: "../../controller/incidencia.php?op=listar",
            type: "GET",
            dataType: "json"
        },

        scrollX: false,
        autoWidth: false,
        iDisplayLength: 10,
        order: [[0, "desc"]],

        columnDefs: [
            { targets: 0, width: "60px", className: "text-center fw-semibold" },
            { targets: 1, width: "60px", className: "text-center fw-semibold" },

            // recorte documentación
            {
                targets: 3,
                render: function (data, type) {
                    if (type === "export" || type === "filter" || type === "sort") return data;
                    if (!data) return "";
                    let corto = data.length > 20 ? data.substring(0, 20) + "…" : data;
                    return `<span title="${data}">${corto}</span>`;
                }
            },

            // recorte módulo
            {
                targets: 4,
                render: function (data, type) {
                    if (type === "export" || type === "filter" || type === "sort") return data;
                    if (!data) return "";
                    let corto = data.length > 20 ? data.substring(0, 20) + "…" : data;
                    return `<span title="${data}">${corto}</span>`;
                }
            },

            // recorte descripción
            {
                targets: 5,
                render: function (data, type) {
                    if (type === "export" || type === "filter" || type === "sort") return data;
                    if (!data) return "";
                    let corto = data.length > 20 ? data.substring(0, 20) + "…" : data;
                    let safeTitle = data.replace(/"/g, "&quot;");
                    return `<span title="${safeTitle}">${corto}</span>`;
                }
            },

            // estado
            {
                targets: 10,
                render: function (data) {
                    let badge = "border-secondary text-muted";

                    if (data === "Pendiente") badge = "border-warning text-warning";
                    if (data === "Resuelto") badge = "border-success text-success";

                    return `<span class="badge rounded-pill ${badge} bg-white">${data}</span>`;
                }
            },

            // acciones
            {
                targets: 12,
                orderable: false,
                render: function (_, __, row) {
                    return `
                    <div class="d-flex justify-content-center gap-1">
                        <a href="detalle.php?id=${row.id_incidencia}" class="btn btn-soft-info btn-sm">
                            <i class="bx bx-show"></i>
                        </a>

                        <a href="../../controller/incidencia_pdf.php?id=${row.id_incidencia}"
                           target="_blank"
                           class="btn btn-soft-primary btn-sm">
                            <i class="bx bxs-file-pdf"></i>
                        </a>

                        <button class="btn btn-soft-danger btn-sm"
                                onclick="eliminar(${row.id_incidencia})">
                            <i class="bx bx-trash-alt"></i>
                        </button>
                    </div>`;
                }
            }
        ],

        columns: [
            { data: "id_incidencia" },       // 0
            { data: "correlativo_doc" },     // 1
            { data: "actividad" },           // 2
            { data: "documentacion" },       // 3
            { data: "modulo" },              // 4
            { data: "descripcion" },         // 5
            { data: "analista" },            // 6
            { data: "prioridad" },           // 7
            { data: "tipo_incidencia" },     // 8
            { data: "fecha_registro" },      // 9
            { data: "estado_incidencia" },   // 10
        
            // 🔥 COLUMNA OCULTA AHORA ESTÁ AQUÍ (11)
            { data: "accion_recomendada", visible: false },
        
            // 🔥 COLUMNA DE BOTONES (12)
            { data: null }
        ]
        
    });

    // =======================================================
    // FILTROS
    // =======================================================
    tabla.on("xhr", function () {
        let data = tabla.ajax.json().data;
        if (!data) return;

        llenarSelect("#filtro_actividad", data.map(d => d.actividad));
        llenarSelect("#filtro_modulo", data.map(d => d.modulo));
        llenarSelect("#filtro_prioridad", data.map(d => d.prioridad));
        llenarSelect("#filtro_tipo", data.map(d => d.tipo_incidencia));
        llenarSelect("#filtro_estado", data.map(d => d.estado_incidencia));
    });

    function llenarSelect(selector, valores) {
        const unicos = [...new Set(valores.filter(v => v))].sort();
        const select = $(selector);

        select.empty().append('<option value="">Todos</option>');
        unicos.forEach(v => select.append(`<option value="${v}">${v}</option>`));
    }

    // eventos filtros
    $("#filtro_documentacion").on("change", () => tabla.column(3).search($("#filtro_documentacion").val()).draw());
    $("#filtro_modulo").on("change", () => tabla.column(4).search($("#filtro_modulo").val()).draw());
    $("#filtro_prioridad").on("change", () => tabla.column(7).search($("#filtro_prioridad").val()).draw());
    $("#filtro_tipo").on("change", () => tabla.column(8).search($("#filtro_tipo").val()).draw());
    $("#filtro_actividad").on("change", () => tabla.column(2).search($("#filtro_actividad").val()).draw());
    $("#filtro_estado").on("change", () => tabla.column(10).search($("#filtro_estado").val()).draw());

    // toggle filtros
    $("#btnFiltros").on("click", function () {
        $("#panelFiltros").slideToggle(200);
    });

    // =======================================================
    // NUEVA INCIDENCIA
    // =======================================================
    $("#btnnuevo").on("click", function () {
        cargarModulos();
        $("#mnt_form")[0].reset();
        $("#modalLabel").html("Nueva Incidencia");
        $("#mnt_modal").modal("show");

        const hoy = new Date().toISOString().split("T")[0];
        $("#fecha_registro").val(hoy);
        $("#fecha_recepcion").val(hoy);

        $.ajax({
            url: "../../controller/incidencia.php?op=correlativo",
            type: "POST",
            dataType: "json",
            success: function (info) {
                $("#id_incidencia, #id_incidencia_visible").val(info.id_incidencia);
            }
        });
    });
});

// =======================================================
// COMBOS
// =======================================================
function cargarDocumentacion() {
    $.ajax({
        url: "../../controller/documentacion.php?op=combo",
        type: "GET",
        dataType: "json",
        success: function (docs) {

            let modal = $("#id_documentacion");
            let filtro = $("#filtro_documentacion");

            modal.empty().append('<option value="">Seleccione documentación</option>');
            filtro.empty().append('<option value="">Todos</option>');

            docs.forEach(d => {
                modal.append(`<option value="${d.id_documentacion}" data-fecha="${d.fecha_recepcion}">
                    ${d.nombre}
                </option>`);

                filtro.append(`<option value="${d.nombre}">${d.nombre}</option>`);
            });
        }
    });
}

$("#id_documentacion").on("change", function () {

    const idDoc = $(this).val();
    const fecha = $(this).find(":selected").data("fecha");

    $("#fecha_recepcion").val(fecha || "");

    if (!idDoc) return;

    $.ajax({
        url: "../../controller/incidencia.php?op=correlativo_doc",
        type: "POST",
        data: { id_documentacion: idDoc },
        dataType: "json",
        success: function (resp) {
            $("#correlativo_doc").val(resp.correlativo);
        }
    });
});

function cargarModulos() {
    $.ajax({
        url: "../../controller/modulo.php?op=combo",
        type: "GET",
        dataType: "json",
        success: function (data) {
            let select = $("#id_modulo");
            select.empty().append('<option value="">Seleccione…</option>');
            data.forEach(m => {
                select.append(`<option value="${m.id_modulo}">${m.nombre}</option>`);
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {

    const dropZone = document.getElementById("dropZone");
    const preview = document.getElementById("preview");
    const inputBase64 = document.getElementById("imagenes_base64");

    let imagenesBase64 = [];

    // -----------------------------
    // CTRL + V (pegar imágenes)
    // -----------------------------
    document.addEventListener("paste", function (event) {
        const items = event.clipboardData.items;

        for (let i = 0; i < items.length; i++) {
            if (items[i].type.includes("image")) {
                const file = items[i].getAsFile();
                procesarImagen(file);
            }
        }
    });

    // -----------------------------
    // Drag Over
    // -----------------------------
    dropZone.addEventListener("dragover", function (event) {
        event.preventDefault();
    });

    // -----------------------------
    // Drop
    // -----------------------------
    dropZone.addEventListener("drop", function (event) {
        event.preventDefault();
        let files = event.dataTransfer.files;

        for (let i = 0; i < files.length; i++) {
            if (files[i].type.includes("image")) {
                procesarImagen(files[i]);
            }
        }
    });

    // -----------------------------
    // Convertir a Base64
    // -----------------------------
    function procesarImagen(file) {
        const reader = new FileReader();

        reader.onload = function (e) {

            imagenesBase64.push(e.target.result);

            // Insertar en el input oculto para el backend
            inputBase64.value = JSON.stringify(imagenesBase64);

            // Mostrar miniatura
            const img = document.createElement("img");
            img.src = e.target.result;
            img.classList.add("img-thumbnail", "m-2");
            img.style.width = "120px";

            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }

});



// Ejecutar init
init();
