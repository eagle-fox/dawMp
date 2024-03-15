<?php
// Importar la clase PHPMailer
namespace app\mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Definir la clase para el envío de correos electrónicos
class Tls {
    // Método para enviar un correo electrónico
    public static function enviar($destinatario, $asunto, $cuerpo) {
        // Instanciar un nuevo objeto de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurar el servidor SMTP
            // $mail->isSMTP();
            // $mail->Host       = getenv('MAIL_HOST'); // Cambia 'smtp.example.com' por tu servidor SMTP
            // $mail->SMTPAuth   = false;
            // $mail->Username   = getenv('MAIL_USERNAME'); // Cambia 'tu_correo@example.com' por tu correo electrónico
            // $mail->Password   = getenv('MAIL_PASSWORD'); // Cambia 'tu_contraseña' por tu contraseña de correo electrónico
            // $mail->SMTPAutoTLS = false;
            // $mail->SMTPSecure = '';
            // $mail->Port       = getenv('MAIL_PORT');

            $mail->isSMTP();
            $mail->Host       = getenv('MAIL_HOST'); // Cambia 'smtp.example.com' por tu servidor SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('MAIL_USERNAME'); // Cambia 'tu_correo@example.com' por tu correo electrónico
            $mail->Password   = getenv('MAIL_PASSWORD'); // Cambia 'tu_contraseña' por tu contraseña de correo electrónico
            $mail->SMTPSecure = 'tls';
            $mail->Port       = getenv('MAIL_PORT');
            $mail->SMTPDebug=2;


        
            // Configurar el remitente y el destinatario
            $mail->setFrom(getenv('MAIL_USERNAME'), 'Tu Nombre');
            $mail->addAddress($destinatario);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;

            // Enviar el correo
            $mail->send();
            return true; // Envío exitoso
        } catch (Exception $e) {
            error_log(getenv('MAIL_HOST'));
            error_log($e);
            return false; // Error en el envío
        }
    }
}

?>
