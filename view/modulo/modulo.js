var tabla;

function init() {
    $("#mnt_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();

    var formData = new FormData($("#mnt_form")[0]);

    $.ajax({
        url: "../../controller/modulo.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(resp) {
            if (resp.status === "ok") {
                $("#mnt_form")[0].reset();
                $("#mnt_modal").modal("hide");
                tabla.ajax.reload();

                Swal.fire("Éxito", resp.msg, "success");
            } else {
                Swal.fire("Error", resp.msg, "error");
            }
        }
    });
}

$(document).ready(function() {

    tabla = $("#modulo_table").DataTable({
        ajax: {
            url: "../../controller/modulo.php?op=listar",
            type: "GET",
            dataType: "json",
            dataSrc: "aaData"
        },
        columns: [
            { data: "id_modulo" },
            { data: "nombre" },
            {
                data: null,
                className: "text-center",
                render: function(data) {
                    return `
                        <button class="btn btn-warning btn-sm" onclick="editar(${data.id_modulo})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminar(${data.id_modulo})">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

});

// NUEVO
$(document).on("click", "#btnnuevo", function() {
    $("#id_modulo").val('');
    $("#mnt_form")[0].reset();
    $("#modalLabel").html("Nuevo Módulo");
    $("#mnt_modal").modal("show");
});

// EDITAR
function editar(id_modulo) {
    $.post("../../controller/modulo.php?op=mostrar",
        { id_modulo: id_modulo },
        function(data) {

            $("#modalLabel").html("Editar Módulo");
            $("#id_modulo").val(data.id_modulo);
            $("#nombre").val(data.nombre);
            $("#mnt_modal").modal("show");

        }, "json");
}

// ELIMINAR
function eliminar(id_modulo) {
    Swal.fire({
        title: "¿Eliminar módulo?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then(result => {
        if (result.isConfirmed) {

            $.post("../../controller/modulo.php?op=eliminar",
                { id_modulo: id_modulo },
                function(resp) {
                    tabla.ajax.reload();
                    Swal.fire("Éxito", "Módulo eliminado correctamente", "success");
                });
        }
    });
}

init();
