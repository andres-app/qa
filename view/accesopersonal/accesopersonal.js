function startGoogleSignIn(){
    //TODO: Obtener la instancia de autenticación de Google
    const auth = gapi.auth2.getAuthInstance();

    //TODO: Iniciar sesión con Google
    auth.signIn();
}

function handleCredentialResponse(response){

    $.ajax({
        type: 'POST',
        url: '../../controller/usuario.php?op=colaboradorgoogle',
        contentType: 'application/json',
        headers: {"Content-Type": "application/json"},
        data: JSON.stringify({
            request_type:'user_auth',
            credential: response.credential
        }),
        success: function(data){
            console.log(data);
            if(data === "0"){
                window.location.href = '../../view/homecolaborador/'
            }else if (data === "1"){
                Swal.fire({
                    title: "Ingreso Colaborador",
                    text: "Su cuenta no tiene acceso.",
                    icon: "error",
                    confirmButtonColor: "#5156be",
                });
            }
        }
    })

    if(response && response.credential){
        const credentialToken = response.credential;

        //TODO: Decodificar el token manualmente para obtener datos del usuario
        const decodedToken = JSON.parse(atob(credentialToken.split('.')[1]));

        //TODO: Imprimir en la consola los datos del usuario
        /* console.log(decodedToken); */

    }
}