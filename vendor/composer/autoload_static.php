<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit574d2ade3c6c84e21ebbcfa8d2ca932c
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        'e39a8b23c42d4e1452234d762b03835a' => __DIR__ . '/..' . '/ramsey/uuid/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'service\\' => 8,
        ),
        'm' => 
        array (
            'model\\' => 6,
        ),
        'i' => 
        array (
            'infra\\' => 6,
        ),
        'c' => 
        array (
            'controller\\' => 11,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'R' => 
        array (
            'Ramsey\\Uuid\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'service\\' => 
        array (
            0 => __DIR__ . '/../..' . '/service',
        ),
        'model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/model',
        ),
        'infra\\' => 
        array (
            0 => __DIR__ . '/../..' . '/infra',
        ),
        'controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controller',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Ramsey\\Uuid\\' => 
        array (
            0 => __DIR__ . '/..' . '/ramsey/uuid/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit574d2ade3c6c84e21ebbcfa8d2ca932c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit574d2ade3c6c84e21ebbcfa8d2ca932c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit574d2ade3c6c84e21ebbcfa8d2ca932c::$classMap;

        }, null, ClassLoader::class);
    }
}
