<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre  = $_POST['nombre'];
    $correo  = $_POST['correo'];
    $mensaje = $_POST['mensaje'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'amlozano055@gmail.com'; 
        $mail->Password   = 'zrso svcr fxvg gdpt'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('amlozano055@gmail.com', 'Web RuedaSport');
        $mail->addAddress('amlozano055@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = "Nuevo contacto de: $nombre";
        $mail->Body    = "<h2>Nuevo Mensaje</h2><p>Nombre: $nombre</p><p>Email: $correo</p><p>Mensaje: $mensaje</p>";

        $mail->send();
        echo "exito";
              
    } catch (Exception $e) {
        echo "error";
    }
}
?>