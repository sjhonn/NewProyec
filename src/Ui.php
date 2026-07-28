<?php

declare(strict_types=1);

namespace Acme;

use DateTimeImmutable;

final class Ui
{
    public static function value(array $record, array $column): string
    {
        [$key, , $format] = $column;
        $value = $record[$key] ?? null;
        return match ($format) {
            'contact' => trim(($record['first_name'] ?? '') . ' ' . ($record['last_name'] ?? '')),
            'relation' => (string) ($record[$key . '_label'] ?? 'Sin asignar'),
            'currency' => 'S/ ' . number_format((float) $value, 2, '.', ','),
            'percent' => number_format((float) $value) . '%',
            'date' => self::date($value),
            'datetime' => self::date($value, true),
            default => (string) ($value ?? '—'),
        };
    }

    public static function date(mixed $value, bool $time = false): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        return (new DateTimeImmutable((string) $value))->format($time ? 'd/m/Y · H:i' : 'd/m/Y');
    }

    public static function input(mixed $value, string $type): string
    {
        if ($value === null) {
            return '';
        }
        return $type === 'datetime-local'
            ? (new DateTimeImmutable((string) $value))->format('Y-m-d\TH:i')
            : (string) $value;
    }

    public static function badge(string $value): string
    {
        return match ($value) {
            'Activa', 'Activo', 'Ganada', 'Cumplido', 'Completada' => 'success',
            'Prospecto', 'Potencial', 'Prospección', 'Calificación', 'Programada', 'Pendiente' => 'primary',
            'Propuesta', 'Negociación', 'En curso', 'Media' => 'warning',
            'Perdida', 'Vencido', 'Alta', 'Cancelada' => 'danger',
            default => 'secondary',
        };
    }
}
