<?php

namespace app\mail;

use Leaf\Mail\Mailer;


class EmailConnector {

    private static $instance;
    private static $connected = false;

    private function __construct() { }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Conecta al servidor de correo electrónico.
     *
     * @param string $host      Hostname para el servidor de correo.
     * @param int    $port      Puerto para el servidor de correo.
     * @param string $security  Cualquier encriptación admitida por PHPMailer.
     * @param mixed  $auth      Autenticación para el servidor de correo. Un array asociativo con `username` y  `password`.
     *
     * @return void
     */
    public function connect($host, $port, $security, $auth) {
        if (!self::$connected) {
            Mailer::connect([
                'host' => $host,
                'port' => $port,
                'security' => $security,
                'auth' => $auth,
            ]);

            self::$connected = true;
        }
    }

    public function getConfig() {
        return [
            'host' => Mailer::getConfig('host'),
            'port' => Mailer::getConfig('port'),
            'security' => Mailer::getConfig('security'),
            'auth' => Mailer::getConfig('auth'),
        ];
    }

    public function createMailer() {
        return Mailer::mailer();
    }
}