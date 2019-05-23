<?php

$ApiKey = '4Vj8eK4rloUd272L48hsrarnUA'; // Obtener este dato dela cuenta de Payu
$merchantId = '508029'; // Obtener este dato dela cuenta de Payu    
//Se valida que exista al menos un parametro
if (!isset($_POST["response_code_pol"])) {
    die("No existen Parametros completos");
}

//se guarda log con todos los pagos.    
$file = fopen("log/data_" . date("Y-m-d") . ".log", "a");
fwrite($file, "#-------------------------------------------------------\n");
fwrite($file, "# Nuevo Pago Confirmado \n");
fwrite($file, "#-------------------------------------------------------\n");
foreach ($_POST as $id => $responseValue) {
    fwrite($file, "/---" . $id . " => " . $responseValue . "---/\n");
}
fclose($file);
// end se guarda log con todos los pagos.    


$referenceCode = $_REQUEST['reference_sale'];
$reference_pol = $_REQUEST['reference_pol'];
$transaction_date = $_REQUEST['transaction_date'];
$txtValue = $_REQUEST['value'];
$newValue = number_format($txtValue, 1, '.', '');
$currency = $_REQUEST['currency'];
$statePol = $_REQUEST['state_pol'];
$sign = $_REQUEST['sign'];
$email = $_REQUEST['email_buyer'];
$estadoTxt = 'ALTERADO'; //Firma no concuerda
$firma = "$ApiKey~$merchantId~$referenceCode~$newValue~$currency~$statePol";
$firmaMd5 = md5($firma);
$datosIngreso = '';
$msg='';


// Si la firma concuerda
if ($firmaMd5 === $sign) {

    switch ($statePol) {
        case 4:
            $estadoTxt = "APROBADO";
            break;
        case 6:
            $estadoTxt = "RECHAZADO";
            break;
        case 7:
            $estadoTxt = "PENDIENTE";
            break;
        case 104:
            $estadoTxt = "ERROR";
            break;
        default:
            $estadoTxt = $_REQUEST['response_message_pol'];
    }
}

//Se busca el id del cliente
include "../model/Cliente.php";
$cliente = new Cliente();
$respuesta = $cliente->getClientePorEmail($email);

if (is_numeric($respuesta->getId())) {
    $idCliente = $respuesta->getId();
    $passWord = $respuesta->getContrasena();
    $usuario = $respuesta->getUsuario();
} else {
    //se guarda log con todos los pagos.    
    $file = fopen("data_" . date("Y-m-d") . ".log", "a");
    fwrite($file, "Error: no se encontro correo para el Cliente, no se puede actualizar.\n");
    fclose($file);
    die("Error");
}
$fecha_fin = $date = date("Y-m-d H:i:s");

$estado_pago = $estadoTxt;
$estado_cuenta = "INACTIVO";
$numero_ref_1 = $referenceCode;
$numero_ref_2 = $reference_pol;
if ($statePol == 4) {
    $estado_cuenta = "ACTIVO";
    
    //Credenciales de acceso
     $datosIngreso .="<b>Pago Exitoso, Tus datos de ingreso al Conversatorio Mundial Online</b><br><br>
                    <b>Felicidades</b>, hemos registrado con éxito la compra de tu acceso
                    al Conversatorio Mundial Online “Propósito de
                    vida” con Juan Diego Gómez este 5 de agosto de
                    2019 a las 7:00 p.m. Bogotá / CDMX / Lima / Quito <br><br>";
     $datosIngreso .="<b>Se realizó exitosamente el proceso de Pago.</b> <br><br>";
    
    $datosIngreso .= "Sus datos de acceso son: <br>";
    $datosIngreso .= "Url Ingreso: <a href='www.ConversatorioMundial.com/payment/login.html'>www.ConversatorioMundial.com/payment/login.html</a> <br>";
    $datosIngreso .= "Usuario: " . $usuario . "<br>";
    $datosIngreso .= "Contraseña Temporal: " . $passWord . "<br><br>";
    
    $datosIngreso .="Debes conectarte el dia <b>5 de agosto de 2019 a las 7:00 p.m. </b> Bogotá / CDMX / Lima / Quito con estos datos que
                    enviamos para poder disfrutar de este evento en vivo con Juan Diego Gómez, una vez terminada la trasmisión
                    durante 3 meses podrás acceder con los mismos datos de acceso a ver la grabación del conversatorio.<br><br>";
    
}
else{
    $datosIngreso .="<b>Pago Rechazado, Conversatorio Mundial Online</b><br><br>
                   Su pago para el conversatorio mundial Online con Juan Diego Gómez
                   ha sido rechazado, lo invitamos a inténselo de nuevo. <br><br>";
}

$respuesta = $cliente->actualizarEstado($idCliente, $estado_cuenta, $estado_pago, $fecha_fin, $numero_ref_1, $numero_ref_2);
if ($respuesta != "Exito") {
    die("error actualizando estado");
}

//Se escribe el HTML del correo.
$msg .= $datosIngreso;
$msg .= "<b>Estado:</b> $estadoTxt <br>";
$msg .= "<b>Fecha Transacción:</b> $transaction_date <br><br>";
$msg .= "En caso de presentar inconvenientes contacte a soporte: <br>";
$msg .= "Correo: conversatorio@invertirmejor.com o al WhatsApp +57 304 6374848 <br><br>";

$msg .= "Cordialmente<br><br>
        Juan Diego Gómez G.<br>
        Coach Financiero.<br>
        Director Invertir Mejor.<br>";

echo 'Ok incia correo';

//envia correo        
include "../include/sentEmail.php";
//Definimos el tema del email
//Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
$mail->AddAddress($email, 'Usuario Invertir Mejor');
$mail->Subject = 'Pago Conversatorio Mundial';
$mail->Body = $msg;
$mail->IsHTML(true);
//Enviamos el correo
if ($mail->Send()) {
    echo 'Enviado';
} else {
    $message = $mail->ErrorInfo;
    echo 'No enviado: ' . $message;
}


die('End');
?>
   