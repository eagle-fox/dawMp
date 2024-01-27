<?php
// Importa el paquete Leaf
require 'vendor/autoload.php';

$app = new Leaf\App;

$app->get('/demo', function() {
    echo "hola mundo";
});

$app->run();

//echo phpinfo();