<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="logo-mark">N</div>
                <div>
                    <div class="brand-name">NutriStep</div>
                    <div class="brand-sub">Espace sécurisé</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a class="sidebar-link active" href="/dashboard">Tableau de bord</a>
                <a class="sidebar-link" href="/profile">Mon profil</a>
                <a class="sidebar-link" href="/admin/regime">Régimes</a>
                <a class="sidebar-link" href="/auth/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
            </nav>

            <form id="logout-form" action="/auth/logout" method="post" style="display:none;"></form>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div>
                    <h1><?= esc($title ?? 'Dashboard') ?></h1>
                    <p><?= esc($subtitle ?? 'Vue protégée par authentification et rôle') ?></p>
                </div>
                <div class="topbar-user">
                    <span class="avatar"><?= esc(strtoupper(substr((string) ($user['name'] ?? 'U'), 0, 1))) ?></span>
                    <div>
                        <strong><?= esc((string) ($user['name'] ?? 'Utilisateur')) ?></strong>
                        <small><?= esc((string) ($user['role'] ?? 'role inconnu')) ?></small>
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