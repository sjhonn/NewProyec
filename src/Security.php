<?php

declare(strict_types=1);

namespace Acme;

final class Security
{
    private const COOKIE = 'acme_csrf';

    public static function token(): string
    {
        [$token, $signature] = array_pad(explode('.', $_COOKIE[self::COOKIE] ?? '', 2), 2, '');
        if ($token !== '' && hash_equals(self::sign($token), $signature)) {
            return $token;
        }

        $token = bin2hex(random_bytes(24));
        $value = $token . '.' . self::sign($token);
        setcookie(self::COOKIE, $value, [
            'expires' => time() + 7200,
            'path' => '/',
            'secure' => ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[self::COOKIE] = $value;
        return $token;
    }

    public static function valid(?string $submitted): bool
    {
        [$token, $signature] = array_pad(explode('.', $_COOKIE[self::COOKIE] ?? '', 2), 2, '');
        return $submitted !== null
            && $token !== ''
            && hash_equals(self::sign($token), $signature)
            && hash_equals($token, $submitted);
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private static function sign(string $token): string
    {
        return hash_hmac('sha256', $token, Config::get('APP_KEY', 'acme-development-key') ?? '');
    }
}
