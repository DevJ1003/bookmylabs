<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInita57db1159f0ccd59ca0e58fb1cca7b31
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInita57db1159f0ccd59ca0e58fb1cca7b31', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInita57db1159f0ccd59ca0e58fb1cca7b31', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInita57db1159f0ccd59ca0e58fb1cca7b31::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
