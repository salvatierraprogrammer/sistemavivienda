<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer.php';
require 'PHPMailer-master/SMTP.php';
require 'PHPMailer-master/Exception.php';

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'salvatierraprogrammer@gmail.com';                     //SMTP username
    $mail->Password   = 'tjbqczxotfuwroub';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('dieguin6348@gmail.com', 'Mailer');
    $mail->addAddress('dieguin6348@gmail.com', 'Joe User');     //Add a recipient               //Name is optional
   

   
    //Content
    //Contenido del correo
	$mail->isHTML(true); // Establece el formato del correo electrónico como HTML
	$mail->Subject = 'Recordatorio de medicación para Tobias';
	$mail->Body = '<html>
		<head>
			<style>
				/* Estilos para mejorar la apariencia del correo */
				body {
					font-family: Arial, sans-serif;
				}
				.message {
					background-color: #f2f2f2;
					padding: 10px;
					border-radius: 5px;
				}
			</style>
		</head>
		<body>
			<h1>¡Es hora de tomar la medicación de Tobias!</h1>
			<p>No olvides tomar una foto de este momento y subirla a la aplicación.</p>
			<p>Gracias por tu atención.</p>
			<div class="message">
				Este es el contenido HTML del mensaje <strong>en negrita</strong>.
			</div>
		</body>
	</html>';
	$mail->AltBody = 'Es hora de tomar la medicación de Tobias. No olvides tomar una foto y subirla a la aplicación.';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>