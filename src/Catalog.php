<?php

declare(strict_types=1);

namespace Acme;

final class Catalog
{
    public static function all(): array
    {
        return [
            'accounts' => [
                'label' => 'Cuentas',
                'singular' => 'Cuenta',
                'icon' => 'fa-building',
                'table' => 'accounts',
                'description' => 'Empresas y organizaciones vinculadas con Acme Services.',
                'search' => ['name', 'industry', 'owner', 'email', 'city'],
                'filter' => 'status',
                'order' => 'r.created_at DESC, r.name ASC',
                'option' => 'name',
                'columns' => [
                    ['name', 'Cuenta', 'primary'],
                    ['industry', 'Industria', 'text'],
                    ['owner', 'Responsable', 'text'],
                    ['status', 'Estado', 'badge'],
                ],
                'fields' => [
                    ['name', 'Nombre de la cuenta', 'text', true, null, null],
                    ['industry', 'Industria', 'select', true, ['Tecnología', 'Servicios profesionales', 'Finanzas', 'Retail', 'Manufactura', 'Salud', 'Educación', 'Otros'], null],
                    ['owner', 'Responsable', 'text', true, null, null],
                    ['status', 'Estado', 'select', true, ['Activa', 'Prospecto', 'Inactiva'], null],
                    ['email', 'Correo corporativo', 'email', false, null, null],
                    ['phone', 'Teléfono', 'text', false, null, null],
                    ['website', 'Sitio web', 'url', false, null, null],
                    ['city', 'Ciudad', 'text', false, null, null],
                ],
            ],
            'contacts' => [
                'label' => 'Contactos',
                'singular' => 'Contacto',
                'icon' => 'fa-address-book',
                'table' => 'contacts',
                'description' => 'Personas de contacto asociadas a las cuentas.',
                'search' => ['first_name', 'last_name', 'job_title', 'email', 'phone'],
                'filter' => 'status',
                'order' => 'r.created_at DESC, r.last_name ASC',
                'option' => "TRIM(first_name || ' ' || last_name)",
                'columns' => [
                    ['first_name', 'Contacto', 'contact'],
                    ['account_id', 'Cuenta', 'relation'],
                    ['job_title', 'Cargo', 'text'],
                    ['status', 'Estado', 'badge'],
                ],
                'fields' => [
                    ['account_id', 'Cuenta relacionada', 'relation', false, null, 'accounts'],
                    ['first_name', 'Nombres', 'text', true, null, null],
                    ['last_name', 'Apellidos', 'text', true, null, null],
                    ['job_title', 'Cargo', 'text', false, null, null],
                    ['email', 'Correo', 'email', true, null, null],
                    ['phone', 'Teléfono', 'text', false, null, null],
                    ['status', 'Estado', 'select', true, ['Activo', 'Potencial', 'Inactivo'], null],
                ],
            ],
            'opportunities' => [
                'label' => 'Oportunidades',
                'singular' => 'Oportunidad',
                'icon' => 'fa-chart-line',
                'table' => 'opportunities',
                'description' => 'Negociaciones activas y proyección comercial.',
                'search' => ['name', 'stage', 'owner', 'notes'],
                'filter' => 'stage',
                'order' => 'r.created_at DESC, r.expected_close ASC',
                'option' => 'name',
                'columns' => [
                    ['name', 'Oportunidad', 'primary'],
                    ['account_id', 'Cuenta', 'relation'],
                    ['amount', 'Valor', 'currency'],
                    ['probability', 'Probabilidad', 'percent'],
                    ['stage', 'Etapa', 'badge'],
                ],
                'fields' => [
                    ['account_id', 'Cuenta relacionada', 'relation', true, null, 'accounts'],
                    ['name', 'Nombre de la oportunidad', 'text', true, null, null],
                    ['stage', 'Etapa', 'select', true, ['Prospección', 'Calificación', 'Propuesta', 'Negociación', 'Ganada', 'Perdida'], null],
                    ['amount', 'Valor estimado', 'number', true, null, null],
                    ['probability', 'Probabilidad', 'number', true, null, null],
                    ['expected_close', 'Cierre estimado', 'date', true, null, null],
                    ['owner', 'Responsable', 'text', true, null, null],
                    ['notes', 'Notas comerciales', 'textarea', false, null, null],
                ],
            ],
            'commitments' => [
                'label' => 'Compromisos',
                'singular' => 'Compromiso',
                'icon' => 'fa-handshake',
                'table' => 'commitments',
                'description' => 'Acuerdos, entregables y fechas comprometidas.',
                'search' => ['title', 'description', 'priority', 'assigned_to'],
                'filter' => 'status',
                'order' => 'r.due_date ASC, r.created_at DESC',
                'option' => 'title',
                'columns' => [
                    ['title', 'Compromiso', 'primary'],
                    ['account_id', 'Cuenta', 'relation'],
                    ['due_date', 'Vencimiento', 'date'],
                    ['priority', 'Prioridad', 'badge'],
                    ['status', 'Estado', 'badge'],
                ],
                'fields' => [
                    ['account_id', 'Cuenta relacionada', 'relation', false, null, 'accounts'],
                    ['contact_id', 'Contacto relacionado', 'relation', false, null, 'contacts'],
                    ['opportunity_id', 'Oportunidad relacionada', 'relation', false, null, 'opportunities'],
                    ['title', 'Título del compromiso', 'text', true, null, null],
                    ['priority', 'Prioridad', 'select', true, ['Alta', 'Media', 'Baja'], null],
                    ['due_date', 'Fecha límite', 'date', true, null, null],
                    ['status', 'Estado', 'select', true, ['Pendiente', 'En curso', 'Cumplido', 'Vencido'], null],
                    ['assigned_to', 'Asignado a', 'text', true, null, null],
                    ['description', 'Descripción', 'textarea', false, null, null],
                ],
            ],
            'activities' => [
                'label' => 'Actividades',
                'singular' => 'Actividad',
                'icon' => 'fa-calendar-check',
                'table' => 'activities',
                'description' => 'Agenda de llamadas, reuniones, tareas y seguimientos.',
                'search' => ['subject', 'type', 'description', 'assigned_to'],
                'filter' => 'status',
                'order' => 'r.scheduled_at ASC, r.created_at DESC',
                'option' => 'subject',
                'columns' => [
                    ['subject', 'Actividad', 'primary'],
                    ['account_id', 'Cuenta', 'relation'],
                    ['scheduled_at', 'Programación', 'datetime'],
                    ['assigned_to', 'Responsable', 'text'],
                    ['status', 'Estado', 'badge'],
                ],
                'fields' => [
                    ['account_id', 'Cuenta relacionada', 'relation', false, null, 'accounts'],
                    ['contact_id', 'Contacto relacionado', 'relation', false, null, 'contacts'],
                    ['opportunity_id', 'Oportunidad relacionada', 'relation', false, null, 'opportunities'],
                    ['commitment_id', 'Compromiso relacionado', 'relation', false, null, 'commitments'],
                    ['type', 'Tipo', 'select', true, ['Llamada', 'Reunión', 'Correo', 'Tarea', 'Seguimiento'], null],
                    ['subject', 'Asunto', 'text', true, null, null],
                    ['scheduled_at', 'Fecha y hora', 'datetime-local', true, null, null],
                    ['duration_minutes', 'Duración en minutos', 'number', false, null, null],
                    ['status', 'Estado', 'select', true, ['Programada', 'Completada', 'Cancelada'], null],
                    ['assigned_to', 'Responsable', 'text', true, null, null],
                    ['description', 'Detalle', 'textarea', false, null, null],
                ],
            ],
        ];
    }

    public static function get(string $resource): ?array
    {
        return self::all()[$resource] ?? null;
    }
}
