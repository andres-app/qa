var tabla;
var tabla_permiso;

function init(){
    $("#mnt_form").on("submit",function(e){
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mnt_form")[0]); // Recoge todos los datos del formulario
    $.ajax({
        url: "../../controller/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            if(datos == 1){
                // Cuando se crea un nuevo colaborador
                $("#mnt_form")[0].reset();
                $("#mnt_modal").modal('hide');
                Swal.fire({
                    title: "Exito",
                    html: "Usuario registrado con éxito.",
                    icon: "success",
                    confirmButtonColor: "#5156be",
                });
            } else if(datos == 2) {
                // Cuando se actualiza un colaborador (incluida la edición de la contraseña)
                $("#mnt_form")[0].reset();
                $("#mnt_modal").modal('hide');
                Swal.fire({
                    title: "Exito",
                    html: "Usuario actualizado con éxito.",
                    icon: "success",
                    confirmButtonColor: "#5156be",
                });
            } else if(datos == 0) {
                Swal.fire({
                    title: "Error",
                    html: "El usuario ya existe, por favor valide.",
                    icon: "error",
                    confirmButtonColor: "#5156be",
                });
            }
        },
        beforeSend: function() {
            $('#btnguardar').prop("disabled",true);
        },
        complete: function() {
            $('#btnguardar').prop("disabled",false);
        }
    });
}


$(document).ready(function(){

    $.post("../../controller/rol.php?op=combo",function(data){
        $('#rol_id').html(data);
    });

    tabla = $("#listado_table").dataTable({
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
        "ajax":{
            url: '../../controller/usuario.php?op=listar',
            type : "get",
            dataType : "json",
            error:function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }).DataTable();

});

$(document).on("click","#btnnuevo",function(){
    $("#usu_id").val('');
    $("#mnt_form")[0].reset();
    $("#myModalLabel").html('Nuevo Registro');
    $("#mnt_modal").modal('show');
});

function editar(usu_id) {
    $("#myModalLabel").html('Editar Registro');
    $.post("../../controller/usuario.php?op=mostrar", { usu_id: usu_id }, function (data) {
        data = JSON.parse(data);
        $("#usu_id").val(data.usu_id);
        $("#usu_nomape").val(data.usu_nomape);
        $("#usu_correo").val(data.usu_correo);
        $("#rol_id").val(data.rol_id);
        // Deja el campo de contraseña vacío, solo si el usuario lo llena se modificará.
        $("#usu_pass").val('');
        $("#mnt_modal").modal('show');
    });
}

function eliminar(usu_id){
    Swal.fire({
        title: "Esta seguro de eliminar el registro?",
        icon: "question",
        showDenyButton: true,
        confirmButtonText: "Si",
        denyButtonText: `No`
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/usuario.php?op=eliminar",{usu_id:usu_id},function(data){
                $("#listado_table").DataTable().ajax.reload();
                Swal.fire({
                    title: "TEMPLATE",
                    html: "Se elimino con exito.",
                    icon: "success",
                    confirmButtonColor: "#5156be",
                });
            });
        }
    });
}

function permiso(usu_id){

    tabla_permiso = $("#listado_table_permiso").dataTable({
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
        "ajax":{
            url: '../../controller/area.php?op=permiso',
            type : "post",
            data : {usu_id:usu_id},
            dataType : "json",
            error:function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 15,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }).DataTable();

    $("#mnt_modal_permiso").modal('show');
}

function habilitar(aread_id){
    $.post("../../controller/area.php?op=habilitar",{aread_id:aread_id},function(data){
        $("#listado_table_permiso").DataTable().ajax.reload();
    });
}

function deshabilitar(aread_id){
    $.post("../../controller/area.php?op=deshabilitar",{aread_id:aread_id},function(data){
        $("#listado_table_permiso").DataTable().ajax.reload();
    });
}

init();