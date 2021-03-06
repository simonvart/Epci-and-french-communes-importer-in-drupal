<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitabb5bd0239c19b02b07cf70548d7ee60
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Drupal\\Epci\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Drupal\\Epci\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitabb5bd0239c19b02b07cf70548d7ee60::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitabb5bd0239c19b02b07cf70548d7ee60::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitabb5bd0239c19b02b07cf70548d7ee60::$classMap;

        }, null, ClassLoader::class);
    }
}
