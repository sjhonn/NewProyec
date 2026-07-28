<section class="page-heading">
    <div><span class="eyebrow">Panel principal</span><h1>Resumen comercial</h1><p>Indicadores y prioridades de la operación comercial.</p></div>
    <div class="heading-actions">
        <a class="btn btn-light-outline" href="/?module=activities"><i class="fa-regular fa-calendar"></i>Ver agenda</a>
        <a class="btn btn-dark-accent" href="/?module=opportunities&amp;action=create"><i class="fa-solid fa-plus"></i>Nueva oportunidad</a>
    </div>
</section>
<section class="metric-grid">
    <?php
    $metrics = [
        ['accounts', 'fa-building', 'blue', 'Cuentas registradas', $stats['accounts'], 'Organizaciones en cartera'],
        ['contacts', 'fa-address-book', 'violet', 'Contactos activos', $stats['contacts'], 'Relaciones comerciales'],
        ['opportunities', 'fa-chart-line', 'green', 'Oportunidades abiertas', $stats['opportunities'], 'S/ ' . number_format((float) $stats['pipeline'], 2, '.', ',') . ' en proyección'],
        ['commitments', 'fa-handshake', 'amber', 'Compromisos pendientes', $stats['commitments'], $stats['activities'] . ' actividades programadas'],
    ];
    ?>
    <?php foreach ($metrics as $metric): ?>
        <a class="metric-card" href="/?module=<?= $metric[0] ?>">
            <span class="metric-top"><span class="metric-icon <?= $metric[2] ?>"><i class="fa-solid <?= $metric[1] ?>"></i></span><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
            <span class="metric-label"><?= \Acme\Security::e($metric[3]) ?></span><strong><?= number_format((int) $metric[4]) ?></strong><small><?= \Acme\Security::e($metric[5]) ?></small>
        </a>
    <?php endforeach; ?>
</section>
<div class="dashboard-grid">
    <section class="panel">
        <div class="panel-header"><div><span class="panel-kicker">Actividad reciente</span><h2>Oportunidades destacadas</h2></div><a href="/?module=opportunities">Ver todas</a></div>
        <div class="table-responsive">
            <table class="table data-table mb-0"><thead><tr><th>Oportunidad</th><th>Etapa</th><th>Valor</th><th>Cierre</th></tr></thead><tbody>
                <?php foreach ($opportunities as $row): ?>
                    <tr>
                        <td><a class="primary-cell" href="/?module=opportunities&amp;action=edit&amp;id=<?= (int) $row['id'] ?>"><strong><?= \Acme\Security::e($row['name']) ?></strong><span><?= \Acme\Security::e($row['account_name'] ?? 'Sin cuenta') ?></span></a></td>
                        <td><span class="status <?= \Acme\Ui::badge((string) $row['stage']) ?>"><?= \Acme\Security::e($row['stage']) ?></span></td>
                        <td>S/ <?= number_format((float) $row['amount'], 2, '.', ',') ?></td><td><?= \Acme\Ui::date($row['expected_close']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody></table>
        </div>
    </section>
    <section class="panel">
        <div class="panel-header"><div><span class="panel-kicker">Agenda</span><h2>Próximas actividades</h2></div><a href="/?module=activities">Abrir agenda</a></div>
        <div class="timeline-list">
            <?php foreach ($activities as $row): ?>
                <a class="timeline-item" href="/?module=activities&amp;action=edit&amp;id=<?= (int) $row['id'] ?>"><span class="timeline-icon"><i class="fa-regular fa-calendar-check"></i></span><span><strong><?= \Acme\Security::e($row['subject']) ?></strong><small><?= \Acme\Security::e($row['account_name'] ?? 'Sin cuenta') ?> · <?= \Acme\Security::e($row['assigned_to']) ?></small></span><time><?= \Acme\Ui::date($row['scheduled_at'], true) ?></time></a>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<section class="panel commitments-panel">
    <div class="panel-header"><div><span class="panel-kicker">Seguimiento</span><h2>Compromisos prioritarios</h2></div><a href="/?module=commitments">Ver todos</a></div>
    <div class="commitment-list">
        <?php foreach ($commitments as $row): ?>
            <a href="/?module=commitments&amp;action=edit&amp;id=<?= (int) $row['id'] ?>"><i class="fa-regular fa-circle"></i><span><strong><?= \Acme\Security::e($row['title']) ?></strong><small><?= \Acme\Security::e($row['account_name'] ?? 'Sin cuenta') ?></small></span><span class="status <?= \Acme\Ui::badge((string) $row['priority']) ?>"><?= \Acme\Security::e($row['priority']) ?></span><time><?= \Acme\Ui::date($row['due_date']) ?></time></a>
        <?php endforeach; ?>
    </div>
</section>
