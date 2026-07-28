<?php

declare(strict_types=1);

namespace Acme;

use PDO;

final class Repository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function list(string $resource, string $query = '', string $filter = ''): array
    {
        $config = Catalog::get($resource);
        [$select, $joins, $relationSearch] = $this->relationSql($config);
        $where = [];
        $params = [];

        if ($query !== '') {
            $conditions = array_map(
                static fn (string $column): string => "CAST(r.$column AS TEXT) ILIKE :query",
                $config['search']
            );
            foreach ($relationSearch as $expression) {
                $conditions[] = "CAST($expression AS TEXT) ILIKE :query";
            }
            $where[] = '(' . implode(' OR ', $conditions) . ')';
            $params[':query'] = '%' . $query . '%';
        }

        if ($filter !== '') {
            $where[] = 'r.' . $config['filter'] . ' = :filter';
            $params[':filter'] = $filter;
        }

        $sql = sprintf(
            'SELECT %s FROM %s r %s %s ORDER BY %s LIMIT 200',
            $select,
            $config['table'],
            implode(' ', $joins),
            $where === [] ? '' : 'WHERE ' . implode(' AND ', $where),
            $config['order']
        );
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(string $resource, int $id): ?array
    {
        $config = Catalog::get($resource);
        $statement = $this->db->prepare("SELECT * FROM {$config['table']} WHERE id = :id");
        $statement->execute([':id' => $id]);
        $record = $statement->fetch();
        return $record === false ? null : $record;
    }

    public function save(string $resource, array $data, ?int $id = null): void
    {
        $config = Catalog::get($resource);
        $allowed = array_column($config['fields'], 0);
        $columns = array_values(array_intersect($allowed, array_keys($data)));

        if ($id === null) {
            $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);
            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $config['table'],
                implode(', ', $columns),
                implode(', ', $placeholders)
            );
        } else {
            $assignments = array_map(static fn (string $column): string => "$column = :$column", $columns);
            $sql = sprintf('UPDATE %s SET %s WHERE id = :id', $config['table'], implode(', ', $assignments));
        }

        $statement = $this->db->prepare($sql);
        foreach ($columns as $column) {
            $statement->bindValue(':' . $column, $data[$column]);
        }
        if ($id !== null) {
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
        }
        $statement->execute();
    }

    public function delete(string $resource, int $id): void
    {
        $config = Catalog::get($resource);
        $statement = $this->db->prepare("DELETE FROM {$config['table']} WHERE id = :id");
        $statement->execute([':id' => $id]);
    }

    public function options(string $resource): array
    {
        $config = Catalog::get($resource);
        $expression = str_contains($config['option'], '(')
            ? str_replace(['first_name', 'last_name'], ['r.first_name', 'r.last_name'], $config['option'])
            : 'r.' . $config['option'];
        return $this->db
            ->query("SELECT r.id, $expression AS label FROM {$config['table']} r ORDER BY label")
            ->fetchAll();
    }

    public function dashboard(): array
    {
        $stats = $this->db->query(
            "SELECT
                (SELECT COUNT(*) FROM accounts) accounts,
                (SELECT COUNT(*) FROM contacts) contacts,
                (SELECT COUNT(*) FROM opportunities WHERE stage NOT IN ('Ganada','Perdida')) opportunities,
                (SELECT COALESCE(SUM(amount),0) FROM opportunities WHERE stage NOT IN ('Ganada','Perdida')) pipeline,
                (SELECT COUNT(*) FROM commitments WHERE status <> 'Cumplido') commitments,
                (SELECT COUNT(*) FROM activities WHERE status = 'Programada') activities"
        )->fetch();
        $opportunities = $this->db->query(
            'SELECT o.*, a.name account_name FROM opportunities o LEFT JOIN accounts a ON a.id=o.account_id ORDER BY o.created_at DESC LIMIT 5'
        )->fetchAll();
        $activities = $this->db->query(
            "SELECT ac.*, a.name account_name FROM activities ac LEFT JOIN accounts a ON a.id=ac.account_id WHERE ac.status='Programada' ORDER BY ac.scheduled_at LIMIT 5"
        )->fetchAll();
        $commitments = $this->db->query(
            "SELECT c.*, a.name account_name FROM commitments c LEFT JOIN accounts a ON a.id=c.account_id WHERE c.status<>'Cumplido' ORDER BY c.due_date LIMIT 5"
        )->fetchAll();
        return compact('stats', 'opportunities', 'activities', 'commitments');
    }

    private function relationSql(array $config): array
    {
        $select = ['r.*'];
        $joins = [];
        $search = [];
        $index = 0;
        foreach ($config['fields'] as $field) {
            if ($field[2] !== 'relation') {
                continue;
            }
            $related = Catalog::get($field[5]);
            $alias = 'rel' . $index++;
            $expression = str_contains($related['option'], '(')
                ? str_replace(['first_name', 'last_name'], ["$alias.first_name", "$alias.last_name"], $related['option'])
                : "$alias.{$related['option']}";
            $joins[] = "LEFT JOIN {$related['table']} $alias ON $alias.id = r.{$field[0]}";
            $select[] = "$expression AS {$field[0]}_label";
            $search[] = $expression;
        }
        return [implode(', ', $select), $joins, $search];
    }
}
