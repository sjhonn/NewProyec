<?php

declare(strict_types=1);

$autoload = dirname(__DIR__) . '/vendor/autoload.php';

if (is_file($autoload)) {
    require $autoload;
} else {
    spl_autoload_register(static function (string $class): void {
        if (!str_starts_with($class, 'Acme\\')) {
            return;
        }

        $file = dirname(__DIR__) . '/src/' . str_replace('\\', '/', substr($class, 5)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
}

use Acme\App;
use Acme\Config;

Config::load(dirname(__DIR__) . '/.env');
date_default_timezone_set(Config::get('APP_TIMEZONE', 'America/Lima'));

(new App())->run();
