var tabla;

function init(){
    $("#mnt_form").on("submit",function(e){
        guardaryeditar(e);
    });
}

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData($("#mnt_form")[0]);
    $.ajax({
        url:"../../controller/tipo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){
            console.log(datos);
            if(datos == 1){
                $("#tip_id").val('');
                $("#mnt_form")[0].reset();
                $("#listado_table").DataTable().ajax.reload();
                $("#mnt_modal").modal('hide');
                Swal.fire({
                    title: "TEMPLATE",
                    html: "Se registro con exito.",
                    icon: "success",
                    confirmButtonColor: "#5156be",
                });
            }else if(datos == 0){
                Swal.fire({
                    title: "TEMPLATE",
                    html: "Registro ya existe, por favor validar.",
                    icon: "error",
                    confirmButtonColor: "#5156be",
                });
            }else if(datos == 2){
                $("#tip_id").val('');
                $("#mnt_form")[0].reset();
                $("#listado_table").DataTable().ajax.reload();
                $("#mnt_modal").modal('hide');
                Swal.fire({
                    title: "TEMPLATE",
                    html: "Se actualizo con exito.",
                    icon: "success",
                    confirmButtonColor: "#5156be",
                });
            }
        }
    });
}

$(document).ready(function(){ 

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
            url: '../../controller/tipo.php?op=listar',
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
    $("#tip_id").val('');
    $("#mnt_form")[0].reset();
    $("#myModalLabel").html('Nuevo Registro');
    $("#mnt_modal").modal('show');
});

function editar(tip_id){
    $("#myModalLabel").html('Editar Registro');
    $.post("../../controller/tipo.php?op=mostrar",{tip_id:tip_id},function(data){
        data=JSON.parse(data);
        $("#tip_id").val(data.tip_id);
        $("#tip_nom").val(data.tip_nom);
        $("#mnt_modal").modal('show');
    });
}

function eliminar(tip_id){
    Swal.fire({
        title: "Esta seguro de eliminar el registro?",
        icon: "question",
        showDenyButton: true,
        confirmButtonText: "Si",
        denyButtonText: `No`
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/tipo.php?op=eliminar",{tip_id:tip_id},function(data){
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

init();