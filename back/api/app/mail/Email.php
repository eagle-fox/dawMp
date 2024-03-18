<?php
namespace app\mail;
use Leaf\Mail;
use Leaf\Mail\Mailer;

class Email {

    private EmailConnector $emailConnector;

    public function __construct(EmailConnector $emailConnector) {
        $this->emailConnector = $emailConnector;
    }

    public function sendEmail($subject, $body, $recipientEmail, $recipientName, $senderEmail, $senderName): bool
    {
        $config = $this->emailConnector->getConfig();

        Mailer::config([
            'keepAlive' => true,
            'debug' => 'SERVER',
            'defaults' => [
                'recipientEmail' => $recipientEmail,
                'recipientName' => $recipientName,
                'senderName' => $senderName,
                'senderEmail' => $senderEmail,
            ],
        ]);

        Mailer::connect($config);

        $mail = new Mail([
            'subject' => $subject,
            'body' => $body,
            'recipientEmail' => $recipientEmail,
            'recipientName' => $recipientName,
            'senderName' => $senderName,
            'senderEmail' => $senderEmail,
        ]);

        if (!$mail->send()) {
            error_log('Message could not be sent. Mailer Error: ' . $mail->errors());
            return false;
        }
        error_log('Message has been sent');
        return true;
    }
}
