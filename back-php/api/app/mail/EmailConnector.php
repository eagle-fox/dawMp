<?php

namespace app\mail;

use Leaf\Mail\Mailer;
use PHPMailer\PHPMailer\PHPMailer;


class EmailConnector {

    public function getConfig(): array
    {
        return [
            'host' => getenv('MAIL_HOST'),
            'port' => getenv('MAIL_PORT'),
            'auth' => [
                'username' => getenv('MAIL_USERNAME'),
                'password' => getenv('MAIL_PASSWORD')
            ]
        ];
    }



}
