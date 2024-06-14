<?php

namespace app\controllers;

use app\mail\Dinahosting;
use Faker\Factory;

class MailController extends Controller
{
    public function send($recipientEmail, $subject, $body,$name = 'Usuario')
    {
        if (empty($body)) {
            $body = $this->makeFakeData();
        }

        $emailObject = new Dinahosting();
        $emailObject->enviar(
            $recipientEmail,
            $subject,
            $body,
            $name
        );
    }

    private function makeFakeData()
    {
        $fakers = [
            Factory::create('es_ES'),
            Factory::create('en_UK'),
            Factory::create('pt_PT'),
            Factory::create('de_DE'),
        ];

        $msg = "";

        for ($i = 0; $i < 4; $i++) {
            $faker = $fakers[$i];
            if ($i === 0) {
                $language = 'es';
            } else if ($i === 1) {
                $language = 'en';
            } else if ($i === 2) {
                $language = 'pt';
            } else {
                $language = 'de';
            }

            $msg .= "<h1>Idioma: " . strtoupper($language) . "</h1>";
            $msg .= "<h2>Nombre: " . $faker->name . "</h2>";
            $msg .= "<p>" . $faker->realText(1024) . "</p>";

            $avatarUrl = $faker->imageUrl(100, 100, 'people');
            $msg .= "<p><img src='{$avatarUrl}' alt='Avatar aleatorio'></p>";
        }

        return $msg;
    }
}
