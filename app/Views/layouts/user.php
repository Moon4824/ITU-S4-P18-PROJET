<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace utilisateur') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <?php $role = (string) ($user['role'] ?? ''); ?>
    <div class="app-shell app-shell-user">
        <aside class="sidebar sidebar-user">
            <div class="sidebar-brand">
                <div class="logo-mark">N</div>
                <div>
                    <div class="brand-name">NutriStep</div>
                    <div class="brand-sub">Espace utilisateur</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a class="sidebar-link" href="/user">Dashboard</a>
                <a class="sidebar-link" href="/user/imc">IMC</a>
                <a class="sidebar-link" href="/user/objectifs">Objectifs</a>
                <a class="sidebar-link" href="/auth/logout" onclick="event.preventDefault(); document.getElementById('logout-form-user').submit();">Déconnexion</a>
            </nav>

            <form id="logout-form-user" action="/auth/logout" method="post" style="display:none;"></form>
        </aside>

        <main class="main-panel main-panel-user">
            <header class="topbar topbar-user-shell">
                <div>
                    <h1><?= esc($title ?? 'Dashboard') ?></h1>
                    <p><?= esc($subtitle ?? 'Votre espace santé et objectifs') ?></p>
                </div>
                <div class="topbar-user">
                    <span class="avatar"><?= esc(strtoupper(substr((string) ($user['name'] ?? 'U'), 0, 1))) ?></span>
                    <div>
                        <strong><?= esc((string) ($user['name'] ?? 'Utilisateur')) ?></strong>
                        <small><?= esc($role !== '' ? $role : 'utilisateur') ?></small>
                    </div>
                </div>
            </header>

            <section class="dashboard-content">
                <?= $this->renderSection('content') ?>
            </section>
        </main>
    </div>
</body>
</html>