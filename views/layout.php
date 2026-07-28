<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#f4f5f7">
    <title><?= \Acme\Security::e($title) ?> | Acme Services</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <span class="brand-mark">AS</span>
                <span><strong>Acme Services</strong><small>Gestión comercial</small></span>
                <button class="sidebar-close d-lg-none" data-sidebar-close aria-label="Cerrar menú"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <nav class="main-nav">
                <span class="nav-caption">General</span>
                <a class="<?= $active === 'dashboard' ? 'active' : '' ?>" href="/?module=dashboard"><i class="fa-solid fa-table-columns"></i>Resumen</a>
                <span class="nav-caption spaced">Gestión</span>
                <?php foreach ($catalog as $key => $item): ?>
                    <a class="<?= $active === $key ? 'active' : '' ?>" href="/?module=<?= \Acme\Security::e($key) ?>">
                        <i class="fa-solid <?= \Acme\Security::e($item['icon']) ?>"></i><?= \Acme\Security::e($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <div><i class="fa-regular fa-circle-question"></i><span><strong>Centro de gestión</strong><small>Información comercial centralizada</small></span></div>
            </div>
        </aside>
        <button class="sidebar-overlay" data-sidebar-close aria-label="Cerrar menú"></button>
        <main class="main-area">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-button d-lg-none" data-sidebar-open aria-label="Abrir menú"><i class="fa-solid fa-bars"></i></button>
                    <span class="breadcrumb-label">Acme Services <i class="fa-solid fa-chevron-right"></i> <strong><?= \Acme\Security::e($title) ?></strong></span>
                </div>
                <div class="topbar-actions">
                    <a class="quick-create" href="/?module=activities&amp;action=create"><i class="fa-solid fa-plus"></i><span>Nueva actividad</span></a>
                    <span class="profile-avatar">AS</span>
                    <span class="profile-copy"><strong>Administración</strong><small>Equipo comercial</small></span>
                </div>
            </header>
            <div class="content-area"><?= $content ?></div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
