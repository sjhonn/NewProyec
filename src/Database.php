<?php

declare(strict_types=1);

namespace Acme;

use PDO;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $url = Config::get('DATABASE_URL');
        if ($url === null || trim($url) === '') {
            throw new RuntimeException('DATABASE_URL no está configurada.');
        }

        $parts = parse_url($url);
        if ($parts === false || !isset($parts['host'], $parts['user'], $parts['path'])) {
            throw new RuntimeException('DATABASE_URL no tiene un formato válido.');
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
            $parts['host'],
            (int) ($parts['port'] ?? 5432),
            ltrim($parts['path'], '/'),
            Config::get('DATABASE_SSLMODE', 'require')
        );

        self::$connection = new PDO(
            $dsn,
            urldecode($parts['user']),
            urldecode($parts['pass'] ?? ''),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_TIMEOUT => 10,
            ]
        );

        return self::$connection;
    }
}
