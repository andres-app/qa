var tabla;

function init() {
    // Guardar o editar
    $("#mnt_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

// ==============================
// GUARDAR O EDITAR
// ==============================
function guardaryeditar(e) {
    e.preventDefault();

    var formData = new FormData($("#mnt_form")[0]);

    $.ajax({
        url: "../../controller/documentacion.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json", // la respuesta ya viene como JSON
        success: function (response) {

            console.log("RESPUESTA GUARDAR:", response);

            if (response.status === "ok") {
                $("#id_documento_mnt").val('');
                $("#mnt_form")[0].reset();
                $("#documentacion_table").DataTable().ajax.reload();
                $("#mnt_modal").modal('hide');

                Swal.fire({
                    title: "TEMPLATE",
                    html: response.msg,
                    icon: "success",
                    confirmButtonColor: "#3A0305",
                });
            } else {
                Swal.fire({
                    title: "TEMPLATE",
                    html: "Error al registrar",
                    icon: "error",
                    confirmButtonColor: "#3A0305",
                });
            }
        },
        error: function (xhr, status, error) {
            console.log("ERROR AJAX GUARDAR:", xhr.responseText);
        }
    });
}

// ==============================
// CARGAR DATATABLE
// ==============================
$(document).ready(function () {

    tabla = $("#documentacion_table").dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "searching": true,
        lengthChange: false,
        colReorder: true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../../controller/documentacion.php?op=listar',
            type: "get",
            dataType: "json",
            dataSrc: "aaData",
            error: function (e) {
                console.log("ERROR AJAX LISTAR:", e.responseText);
            }
        },
        "columns": [
            { data: "id_documentacion" },
            { data: "nombre" },
            { data: "tipo_documento" },
            { data: "fecha_recepcion" },
            { data: "fecha_creacion" },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-primary btn-sm me-1" title="Generar PDF de incidencias consolidado"
                            onclick="generarPDF(${data.id_documentacion})">
                            <i class="bx bxs-file-pdf"></i>
                        </button>
            
                        <button class="btn btn-warning btn-sm me-1" onclick="editar(${data.id_documentacion})">
                            <i class="bx bx-edit"></i>
                        </button>
            
                        <button class="btn btn-danger btn-sm" onclick="eliminar(${data.id_documentacion})">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                },
                className: "text-center"
            }

        ],
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "autoWidth": false,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    }).DataTable();

});

// ==============================
// NUEVO DOCUMENTO
// ==============================
$(document).on("click", "#btnnuevo", function () {
    $("#id_documento_mnt").val('');
    $("#mnt_form")[0].reset();
    $("#modalLabel").html('Nuevo Documento');
    $("#mnt_modal").modal('show');
});

// ==============================
// EDITAR DOCUMENTO
// ==============================
function editar(id_documentacion) {

    $("#modalLabel").html('Editar Documento');

    $.post(
        "../../controller/documentacion.php?op=mostrar",
        { id_documentacion: id_documentacion },
        function (data) {

            console.log("RESPUESTA MOSTRAR:", data);

            // data YA ES OBJETO JSON porque indicamos "json" al final
            $("#id_documento_mnt").val(data.id_documentacion);
            $("#nombre").val(data.nombre);
            $("#descripcion").val(data.descripcion);
            $("#fecha_recepcion").val(data.fecha_recepcion);
            $("#tipo_documento").val(data.tipo_documento);

            $("#mnt_modal").modal('show');
        },
        "json" // <- importante: jQuery convierte automáticamente a objeto
    );
}


// ==============================
// ELIMINAR DOCUMENTO
// ==============================
function eliminar(id_documentacion) {
    Swal.fire({
        title: "¿Está seguro de eliminar el registro?",
        icon: "question",
        showDenyButton: true,
        confirmButtonText: "Si",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(
                "../../controller/documentacion.php?op=eliminar",
                { id_documentacion: id_documentacion },
                function (resp) {
                    console.log("RESPUESTA ELIMINAR:", resp);
                    $("#documentacion_table").DataTable().ajax.reload();
                    Swal.fire("TEMPLATE", "Se eliminó con éxito", "success");
                }
            );
        }
    });
}

function generarPDF(id_documentacion) {
    // Abrir el PDF en una nueva pestaña
    window.open(`../../controller/documentacion.php?op=pdf&id_documentacion=${id_documentacion}`, "_blank");
}


init();
