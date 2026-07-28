<?php

declare(strict_types=1);

namespace Acme;

use DateTimeImmutable;
use Throwable;

final class App
{
    public function run(): void
    {
        try {
            $module = (string) ($_GET['module'] ?? 'dashboard');
            if ($module === 'dashboard') {
                $repository = new Repository();
                View::render('dashboard', array_merge($repository->dashboard(), [
                    'active' => 'dashboard',
                    'title' => 'Resumen comercial',
                ]));
                return;
            }

            $config = Catalog::get($module);
            if ($config === null) {
                $this->redirect('/?module=dashboard');
            }

            $action = (string) ($_GET['action'] ?? 'index');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->mutate($module, $config, $action);
                return;
            }

            if ($action === 'create' || $action === 'edit') {
                $id = $action === 'edit' ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : null;
                $this->form($module, $config, $id ?: null);
                return;
            }

            $query = trim((string) ($_GET['q'] ?? ''));
            $filter = trim((string) ($_GET['filter'] ?? ''));
            $repository = new Repository();
            View::render('list', [
                'active' => $module,
                'title' => $config['label'],
                'module' => $module,
                'config' => $config,
                'records' => $repository->list($module, $query, $filter),
                'query' => $query,
                'filter' => $filter,
                'notice' => (string) ($_GET['notice'] ?? ''),
            ]);
        } catch (Throwable $error) {
            error_log($error->__toString());
            View::render('error', [
                'active' => 'dashboard',
                'title' => 'No se pudo completar la solicitud',
                'detail' => Config::get('APP_ENV') === 'local' ? $error->getMessage() : null,
            ], 500);
        }
    }

    private function form(string $module, array $config, ?int $id, array $record = [], array $errors = []): void
    {
        $repository = new Repository();
        if ($id !== null && $record === []) {
            $record = $repository->find($module, $id) ?? [];
            if ($record === []) {
                View::render('error', ['active' => $module, 'title' => 'Registro no encontrado', 'detail' => null], 404);
                return;
            }
        }

        $options = [];
        foreach ($config['fields'] as $field) {
            if ($field[2] === 'relation') {
                $options[$field[0]] = $repository->options($field[5]);
            }
        }

        View::render('form', [
            'active' => $module,
            'title' => ($id === null ? 'Nuevo ' : 'Editar ') . mb_strtolower($config['singular']),
            'module' => $module,
            'config' => $config,
            'record' => $record,
            'errors' => $errors,
            'id' => $id,
            'options' => $options,
        ], $errors === [] ? 200 : 422);
    }

    private function mutate(string $module, array $config, string $action): void
    {
        if (!Security::valid($_POST['_token'] ?? null)) {
            View::render('error', ['active' => $module, 'title' => 'La solicitud perdió vigencia', 'detail' => null], 419);
            return;
        }

        $repository = new Repository();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: null;
        if (!in_array($action, ['store', 'update', 'delete'], true)) {
            View::render('error', ['active' => $module, 'title' => 'Acción no permitida', 'detail' => null], 405);
            return;
        }
        if (in_array($action, ['update', 'delete'], true) && $id === null) {
            View::render('error', ['active' => $module, 'title' => 'Registro no válido', 'detail' => null], 400);
            return;
        }
        if ($action === 'delete') {
            $repository->delete($module, $id);
            $this->redirect("/?module=$module&notice=deleted");
        }

        [$data, $errors] = $this->validate($config['fields']);
        if ($errors !== []) {
            $this->form($module, $config, $action === 'update' ? $id : null, $_POST, $errors);
            return;
        }

        $repository->save($module, $data, $action === 'update' ? $id : null);
        $this->redirect("/?module=$module&notice=" . ($action === 'update' ? 'updated' : 'created'));
    }

    private function validate(array $fields): array
    {
        $data = [];
        $errors = [];
        foreach ($fields as $field) {
            [$name, $label, $type, $required, $choices] = $field;
            $value = is_string($_POST[$name] ?? null) ? trim($_POST[$name]) : null;
            if ($value === null || $value === '') {
                if ($required) {
                    $errors[$name] = "$label es obligatorio.";
                }
                $data[$name] = null;
                continue;
            }

            if ($type === 'email' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                $errors[$name] = 'Ingresa un correo válido.';
            } elseif ($type === 'url' && filter_var($value, FILTER_VALIDATE_URL) === false) {
                $errors[$name] = 'Ingresa una dirección web válida.';
            } elseif ($type === 'select' && !in_array($value, $choices, true)) {
                $errors[$name] = 'Selecciona una opción válida.';
            } elseif ($type === 'relation' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                $errors[$name] = 'Selecciona una relación válida.';
            } elseif ($type === 'number' && !is_numeric($value)) {
                $errors[$name] = 'Ingresa un valor numérico.';
            } elseif (in_array($type, ['date', 'datetime-local'], true)) {
                $format = $type === 'date' ? 'Y-m-d' : 'Y-m-d\TH:i';
                $date = DateTimeImmutable::createFromFormat($format, $value);
                if (!$date || $date->format($format) !== $value) {
                    $errors[$name] = 'Ingresa una fecha válida.';
                } elseif ($type === 'datetime-local') {
                    $value = $date->format('Y-m-d H:i:s');
                }
            }

            $data[$name] = $type === 'number' ? (float) $value : $value;
        }
        return [$data, $errors];
    }

    private function redirect(string $url): never
    {
        header('Location: ' . $url, true, 303);
        exit;
    }
}
