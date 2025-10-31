$(document).ready(function(){

    
});

$(document).on("click","#btnrecuperar",function(){

    var usu_correo = $('#usu_correo').val();

    if(usu_correo === ""){
        Swal.fire({
            title: "Recuperar",
            text: "El campo esta vacio, por favor validar.",
            icon: "error",
            confirmButtonColor: "#5156be",
        });
    }else{
        $.ajax({
            url:"../../controller/email.php?op=recuperar",
            type: "POST",
            data: {usu_correo : usu_correo,rol_id : 1},
            success: function(datos){

                if (datos == 1){

                    Swal.fire({
                        title: "Recuperar",
                        text: "Se cambio la contraseña, y se envio a su correo electronico.",
                        icon: "success",
                        confirmButtonColor: "#5156be",
                    });

                    $('#btnrecuperar').prop("disabled",false);
                    $('#btnrecuperar').html('Recuperar');

                }else{
                    Swal.fire({
                        title: "Recuperar",
                        text: "El correo electronico no existe.",
                        icon: "error",
                        confirmButtonColor: "#5156be",
                    });
                }
            },beforeSend: function(){
                $('#btnrecuperar').prop("disabled",true);
                $('#btnrecuperar').html('<i class="bx bx-hourglass bx-spin font-size-16 align-middle me-2"></i>Espere..');
            },
        });
    }
});