<?php
$messages = ['created' => 'Registro creado correctamente.', 'updated' => 'Registro actualizado correctamente.', 'deleted' => 'Registro eliminado correctamente.'];
$filters = [];
foreach ($config['fields'] as $field) {
    if ($field[0] === $config['filter']) {
        $filters = $field[4] ?? [];
    }
}
?>
<section class="page-heading">
    <div><span class="eyebrow">Gestión de registros</span><h1><?= \Acme\Security::e($config['label']) ?></h1><p><?= \Acme\Security::e($config['description']) ?></p></div>
    <a class="btn btn-dark-accent" href="/?module=<?= \Acme\Security::e($module) ?>&amp;action=create"><i class="fa-solid fa-plus"></i>Nuevo <?= \Acme\Security::e(mb_strtolower($config['singular'])) ?></a>
</section>
<?php if (isset($messages[$notice])): ?><div class="alert alert-success app-alert"><i class="fa-solid fa-circle-check"></i><?= $messages[$notice] ?></div><?php endif; ?>
<section class="panel records-panel">
    <form class="record-toolbar" method="get">
        <input type="hidden" name="module" value="<?= \Acme\Security::e($module) ?>">
        <label class="search-field"><i class="fa-solid fa-magnifying-glass"></i><span class="sr-only">Buscar</span><input type="search" name="q" value="<?= \Acme\Security::e($query) ?>" placeholder="Buscar registros..."></label>
        <label class="filter-field"><i class="fa-solid fa-filter"></i><span class="sr-only">Filtrar</span><select name="filter"><option value="">Todos los estados</option><?php foreach ($filters as $option): ?><option value="<?= \Acme\Security::e($option) ?>" <?= $filter === $option ? 'selected' : '' ?>><?= \Acme\Security::e($option) ?></option><?php endforeach; ?></select></label>
        <button class="btn btn-light-outline" type="submit">Aplicar</button><span class="record-count"><?= count($records) ?> registros</span>
    </form>
    <?php if ($records === []): ?>
        <div class="empty-state"><span><i class="fa-solid <?= \Acme\Security::e($config['icon']) ?>"></i></span><h2>No se encontraron registros</h2><p>Modifica la búsqueda o registra un nuevo elemento.</p></div>
    <?php else: ?>
        <div class="table-responsive desktop-table"><table class="table data-table records-table mb-0"><thead><tr><?php foreach ($config['columns'] as $column): ?><th><?= \Acme\Security::e($column[1]) ?></th><?php endforeach; ?><th class="text-right">Acciones</th></tr></thead><tbody>
            <?php foreach ($records as $record): ?><tr>
                <?php foreach ($config['columns'] as $column): ?><td>
                    <?php if ($column[2] === 'badge'): ?><span class="status <?= \Acme\Ui::badge((string) ($record[$column[0]] ?? '')) ?>"><?= \Acme\Security::e(\Acme\Ui::value($record, $column)) ?></span>
                    <?php elseif (in_array($column[2], ['primary', 'contact'], true)): ?><a class="primary-cell" href="/?module=<?= \Acme\Security::e($module) ?>&amp;action=edit&amp;id=<?= (int) $record['id'] ?>"><strong><?= \Acme\Security::e(\Acme\Ui::value($record, $column)) ?></strong><?php if (!empty($record['email'])): ?><span><?= \Acme\Security::e($record['email']) ?></span><?php endif; ?></a>
                    <?php else: ?><?= \Acme\Security::e(\Acme\Ui::value($record, $column)) ?><?php endif; ?>
                </td><?php endforeach; ?>
                <td class="record-actions"><a class="icon-button" href="/?module=<?= \Acme\Security::e($module) ?>&amp;action=edit&amp;id=<?= (int) $record['id'] ?>" aria-label="Editar"><i class="fa-regular fa-pen-to-square"></i></a><form method="post" action="/?module=<?= \Acme\Security::e($module) ?>&amp;action=delete&amp;id=<?= (int) $record['id'] ?>" data-confirm-delete><input type="hidden" name="_token" value="<?= \Acme\Security::e($csrf) ?>"><button class="icon-button danger" aria-label="Eliminar"><i class="fa-regular fa-trash-can"></i></button></form></td>
            </tr><?php endforeach; ?>
        </tbody></table></div>
        <div class="mobile-records"><?php foreach ($records as $record): ?><article><div><span class="mobile-icon"><i class="fa-solid <?= \Acme\Security::e($config['icon']) ?>"></i></span><strong><?= \Acme\Security::e(\Acme\Ui::value($record, $config['columns'][0])) ?></strong><a class="icon-button" href="/?module=<?= \Acme\Security::e($module) ?>&amp;action=edit&amp;id=<?= (int) $record['id'] ?>"><i class="fa-regular fa-pen-to-square"></i></a></div><dl><?php foreach (array_slice($config['columns'], 1) as $column): ?><div><dt><?= \Acme\Security::e($column[1]) ?></dt><dd><?= \Acme\Security::e(\Acme\Ui::value($record, $column)) ?></dd></div><?php endforeach; ?></dl></article><?php endforeach; ?></div>
    <?php endif; ?>
</section>
