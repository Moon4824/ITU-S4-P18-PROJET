<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace admin') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <?php $currentPath = trim(service('uri')->getPath(), '/'); ?>
    <?php $role = (string) ($user['role'] ?? ''); ?>
    <div class="app-shell app-shell-admin">
        <aside class="sidebar sidebar-admin">
            <div class="sidebar-brand">
                <div class="logo-mark">A</div>
                <div>
                    <div class="brand-name">NutriStep Admin</div>
                    <div class="brand-sub">Supervision</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a class="sidebar-link <?= $currentPath === 'admin' ? 'active' : '' ?>" href="/admin">Dashboard</a>
                <a class="sidebar-link <?= str_starts_with($currentPath, 'admin/regime') ? 'active' : '' ?>" href="/admin/regime">Régimes</a>
                <a class="sidebar-link <?= str_starts_with($currentPath, 'admin/sports') ? 'active' : '' ?>" href="/admin/sports">Sport</a>
                <a class="sidebar-link <?= str_starts_with($currentPath, 'admin/codes') ? 'active' : '' ?>" href="/admin/codes">Code argent</a>
                <a class="sidebar-link <?= str_starts_with($currentPath, 'admin/utilisateurs') ? 'active' : '' ?>" href="/admin/utilisateurs">Utilisateurs</a>
                <div style="margin:18px 0 8px;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--c-muted);">Paramètres</div>
                <a class="sidebar-link <?= str_starts_with($currentPath, 'admin/gold') ? 'active' : '' ?>" href="/admin/gold">Configuration Gold</a>
                <a class="sidebar-link <?= str_starts_with($currentPath, 'admin/imc/interpretations') ? 'active' : '' ?>" href="/admin/imc/interpretations">Interprétations IMC</a>
                <a class="sidebar-link" href="/auth/logout" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">Déconnexion</a>
            </nav>

            <form id="logout-form-admin" action="/auth/logout" method="post" style="display:none;"></form>
        </aside>

        <main class="main-panel main-panel-admin">
            <header class="topbar topbar-admin-shell">
                <div class="topbar-search">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Rechercher…" />
                </div>
                <div class="topbar-user">
                    <span class="avatar"><?= esc(strtoupper(substr((string) ($user['name'] ?? 'A'), 0, 1))) ?></span>
                    <div>
                        <strong><?= esc((string) ($user['name'] ?? 'Administrateur')) ?></strong>
                        <small><?= esc($role !== '' ? $role : 'admin') ?></small>
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