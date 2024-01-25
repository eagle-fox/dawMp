<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitefa814e18104fa39d51e375874c27717
{
    public static $files = array (
        'f7a40c1f1f5eb11aee5f7554cb0c8ea7' => __DIR__ . '/..' . '/leafs/form/src/functions.php',
        'cfb7c780793bfa1138356bbe97dc66da' => __DIR__ . '/..' . '/leafs/http/src/functions.php',
        'cd18aec96aea037961c7c777fe0159ab' => __DIR__ . '/..' . '/leafs/leaf/src/functions.php',
        '84fa349a1628385c5b280dcf0df93015' => __DIR__ . '/..' . '/ycms/krumo/class.krumo.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'L' => 
        array (
            'Leaf\\Http\\' => 10,
            'Leaf\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Leaf\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/leafs/http/src',
        ),
        'Leaf\\' => 
        array (
            0 => __DIR__ . '/..' . '/leafs/anchor/src',
            1 => __DIR__ . '/..' . '/leafs/exception/src',
            2 => __DIR__ . '/..' . '/leafs/form/src',
            3 => __DIR__ . '/..' . '/leafs/leaf/src',
            4 => __DIR__ . '/..' . '/leafs/router/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitefa814e18104fa39d51e375874c27717::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitefa814e18104fa39d51e375874c27717::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitefa814e18104fa39d51e375874c27717::$classMap;

        }, null, ClassLoader::class);
    }
}