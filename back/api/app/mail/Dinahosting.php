<?php
// Importar la clase PHPMailer
namespace app\mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Definir la clase para el envío de correos electrónicos
class Dinahosting
{
    // Método para enviar un correo electrónico
    public static function enviar($destinatario, $asunto, $cuerpo, $nombre)
    {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port = getenv('MAIL_PORT');
            $mail->SMTPDebug = 2;

            $mail->setFrom(getenv('MAIL_USERNAME'), $nombre);
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log(getenv('MAIL_HOST'));
            error_log($e);
            return false;
        }

    }
}
