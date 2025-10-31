<?php
require '../include/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../config/conexion.php");
require_once("../models/Usuario.php");
require_once("../models/Documento.php");

class Email extends PHPMailer{

    protected $gCorreo = 'info@andercode.net';
    protected $gContrasena = 'Mesadepartes@1';

    private $key = "MesaDePartesAnderCode";
    private $cipher = "aes-256-cbc";

    public function registrar($usu_id){

        $conexion = new Conectar();

        $usuario = new Usuario();
        $datos = $usuario -> get_usuario_id($usu_id);

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $cifrado = openssl_encrypt($usu_id, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        $textoCifrado = base64_encode($iv . $cifrado);

        $this->IsSMTP();
        $this->Host = 'smtp.hostinger.com';
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';

        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->setFrom($this->gCorreo,"Registro en TEMPLATE AnderCode");

        $this->CharSet = 'UTF8';
        $this->addAddress($datos[0]["usu_correo"]);
        $this->IsHTML(true);
        $this->Subject = "TEMPLATE";

        $url = $conexion->ruta() . "view/confirmar/?id=" . $textoCifrado;

        $cuerpo = file_get_contents("../assets/email/registrar.html");
        $cuerpo = str_replace("xlinkcorreourl",$url,$cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Confirmar Registro");

        try{
            $this->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function recuperar($usu_correo,$rol_id){

        $conexion = new Conectar();

        $usuario = new Usuario();
        $datos = $usuario -> get_usuario_correo($usu_correo);

        $this->IsSMTP();
        $this->Host = 'smtp.hostinger.com';
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';

        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->setFrom($this->gCorreo,"Recuperar Contraseña TEMPLATE AnderCode");

        $this->CharSet = 'UTF8';
        $this->addAddress($datos[0]["usu_correo"]);
        $this->IsHTML(true);
        $this->Subject = "TEMPLATE";

        if ($rol_id == 1){
            $url = $conexion->ruta();
        }elseif($rol_id == 2){
            $url = $conexion->ruta()."view/accesopersonal/";
        }

        //TODO: Generar la cadena alfanumérica
        $xpassusu = $this->generarXPassUsu();

        $usuario -> recuperar_usuario($usu_correo,$xpassusu);

        $cuerpo = file_get_contents("../assets/email/recuperar.html");
        $cuerpo = str_replace("xpassusu",$xpassusu,$cuerpo);
        $cuerpo = str_replace("xlinksistema",$url,$cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Recupera Contraseña");

        try{
            $this->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function nuevo_colaborador($usu_id){

        $conexion = new Conectar();

        $usuario = new Usuario();
        $datos = $usuario -> get_usuario_id($usu_id);

        $this->IsSMTP();
        $this->Host = 'smtp.hostinger.com';
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';

        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->setFrom($this->gCorreo,"Bienvenido Colaborador TEMPLATE AnderCode");

        $this->CharSet = 'UTF8';
        $this->addAddress($datos[0]["usu_correo"]);
        $this->IsHTML(true);
        $this->Subject = "TEMPLATE";

        $url = $conexion->ruta()."view/accesopersonal/";

        //TODO: Generar la cadena alfanumérica
        $xpassusu = $this->generarXPassUsu();

        $usuario -> recuperar_usuario($datos[0]["usu_correo"],$xpassusu);

        $cuerpo = file_get_contents("../assets/email/nuevocolaborador.html");
        $cuerpo = str_replace("xemail",$datos[0]["usu_correo"],$cuerpo);
        $cuerpo = str_replace("xpassusu",$xpassusu,$cuerpo);
        $cuerpo = str_replace("xlinksistema",$url,$cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Recupera Contraseña");

        try{
            $this->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    private function generarXPassUsu() {
        $parteAlfanumerica = substr(md5(rand()), 0, 3);
        $parteNumerica = str_pad(rand(0,999),3,'0',STR_PAD_LEFT);
        $resultado = $parteAlfanumerica . $parteNumerica;
        return substr($resultado,0,6);
    }

    public function enviar_registro($doc_id){

        $conexion = new Conectar();

        $documento = new Documento();
        $datos = $documento -> get_documento_x_id($doc_id);

        $this->IsSMTP();
        $this->Host = 'smtp.hostinger.com';
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';

        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->setFrom($this->gCorreo,"Nuevo Tramite TEMPLATE AnderCode");

        $this->CharSet = 'UTF8';
        $this->addAddress($datos[0]["usu_correo"]);
        $this->addAddress($datos[0]["area_correo"]);
        $this->IsHTML(true);
        $this->Subject = "TEMPLATE";

        $url = $conexion->ruta();

        $cuerpo = file_get_contents("../assets/email/enviar.html");
        $cuerpo = str_replace("xlinksistema",$url,$cuerpo);

        $cuerpo = str_replace("xnrotramite",$datos[0]["nrotramite"],$cuerpo);
        $cuerpo = str_replace("xarea",$datos[0]["area_nom"],$cuerpo);
        $cuerpo = str_replace("xtramite",$datos[0]["tra_nom"],$cuerpo);
        $cuerpo = str_replace("xnroexterno",$datos[0]["doc_externo"],$cuerpo);
        $cuerpo = str_replace("xtipo",$datos[0]["tip_nom"],$cuerpo);
        $cuerpo = str_replace("xcant",$datos[0]["cant"],$cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Enviar Registro");

        try{
            $this->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function respuesta_registro($doc_id){

        $conexion = new Conectar();

        $documento = new Documento();
        $datos = $documento -> get_documento_x_id($doc_id);

        $this->IsSMTP();
        $this->Host = 'smtp.hostinger.com';
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';

        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->setFrom($this->gCorreo,"Respuesta Tramite TEMPLATE AnderCode");

        $this->CharSet = 'UTF8';
        $this->addAddress($datos[0]["usu_correo"]);
        $this->IsHTML(true);
        $this->Subject = "TEMPLATE";

        $url = $conexion->ruta();

        $cuerpo = file_get_contents("../assets/email/respuesta.html");
        $cuerpo = str_replace("xlinksistema",$url,$cuerpo);

        $cuerpo = str_replace("xnrotramite",$datos[0]["nrotramite"],$cuerpo);
        $cuerpo = str_replace("xarea",$datos[0]["area_nom"],$cuerpo);
        $cuerpo = str_replace("xtramite",$datos[0]["tra_nom"],$cuerpo);
        $cuerpo = str_replace("xnroexterno",$datos[0]["doc_externo"],$cuerpo);
        $cuerpo = str_replace("xtipo",$datos[0]["tip_nom"],$cuerpo);
        $cuerpo = str_replace("xcant",$datos[0]["cant"],$cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Respuesta Registro");

        try{
            $this->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

}
?>
