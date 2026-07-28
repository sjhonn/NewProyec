<?php

declare(strict_types=1);

namespace Acme;

final class Config
{
    private static array $values = [];

    public static function load(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode('=', $line, 2));
            if ($key !== '' && getenv($key) === false) {
                self::$values[$key] = trim($value, "\"'");
            }
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        return $value !== false ? $value : (self::$values[$key] ?? $default);
    }
}
