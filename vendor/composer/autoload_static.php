<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit77ce6c0adfe4df016c1d1e73fa21c468
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Snipworks\\Smtp\\' => 15,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Snipworks\\Smtp\\' => 
        array (
            0 => __DIR__ . '/..' . '/snipworks/php-smtp/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit77ce6c0adfe4df016c1d1e73fa21c468::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit77ce6c0adfe4df016c1d1e73fa21c468::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
