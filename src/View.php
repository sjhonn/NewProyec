<?php

declare(strict_types=1);

namespace Acme;

final class View
{
    public static function render(string $template, array $data, int $status = 200): void
    {
        http_response_code($status);
        $data['catalog'] = Catalog::all();
        $data['csrf'] = Security::token();
        extract($data, EXTR_SKIP);
        ob_start();
        require dirname(__DIR__) . '/views/' . $template . '.php';
        $content = (string) ob_get_clean();
        require dirname(__DIR__) . '/views/layout.php';
    }
}
