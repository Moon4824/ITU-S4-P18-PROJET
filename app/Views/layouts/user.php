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
            </nav>
        </aside>

        <main class="main-panel main-panel-user">
            <header class="topbar">
                <div class="topbar-search">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Rechercher…" />
                </div>
                
                <div class="topbar-actions">
                    <a href="/user/profile" class="icon-btn" title="Mon profil">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>
                    </a>
                </div>
            </header>

            <section class="dashboard-content">
                <?= $this->renderSection('content') ?>
            </section>
        </main>
    </div>
</body>
</html>