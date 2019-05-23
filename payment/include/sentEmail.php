<?php


/*Lo primero es añadir al script la clase phpmailer desde la ubicación en que esté*/
require 'phpmailer/class.phpmailer.php';

//Crear una instancia de PHPMailer
$mail = new PHPMailer();
//Definir que vamos a usar SMTP
$mail->IsSMTP();
//Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
// 0 = off (producción)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug  = 0;
//Ahora definimos gmail como servidor que aloja nuestro SMTP
$mail->Host       = 'smtp.gmail.com';
//El puerto será el 587 ya que usamos encriptación TLS
$mail->Port       = 587;
//Definmos la seguridad como TLS
$mail->SMTPSecure = 'tls';
//Tenemos que usar gmail autenticados, así que esto a TRUE
$mail->SMTPAuth   = true;
//Definimos la cuenta que vamos a usar. Dirección completa de la misma
$mail->Username   = "pilotto.co@gmail.com";
//Introducimos nuestra contraseña de gmail
$mail->Password   = "zL9LgEy2kT1";
//Definimos el remitente (dirección y, opcionalmente, nombre)
$mail->SetFrom('pilotto.co@gmail.com', 'Invertir Mejor');

