<?php 
namespace app\mail;
use Leaf\Mail\Mailer;

class Email {

    private $emailConnector;

    public function __construct(EmailConnector $emailConnector) {
        $this->emailConnector = $emailConnector;
    }

    public function sendEmail($subject, $body, $recipientEmail, $recipientName, $senderEmail, $senderName) {
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

        Mailer::mailer()
            ->create([
                'subject' => $subject,
                'body' => $body,
            ])
            ->send();
    }
}