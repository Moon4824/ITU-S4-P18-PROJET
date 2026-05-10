<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php $role = (string) ($user['role'] ?? ''); ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2>Tableau de bord</h2>
        <div class="breadcrumb">Accueil / <span>Tableau de bord</span></div>
    </div>
    <div style="display:flex;gap:10px">
        <a href="<?= base_url('user/imc') ?>" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:currentColor;fill:none"><circle cx="12" cy="12" r="10"/><path d="M12 6v6m0 0l3-3m-3 3l-3-3"/></svg>
            Calculer IMC
        </a>
    </div>
</div>

<!-- KPI Grid (4 colonnes) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-label">Profil utilisateur</div>
            <div class="kpi-icon bg-blue">
                <svg viewBox="0 0 24 24" style="width:20px;height:20px"><circle cx="12" cy="8" r="4" stroke="currentColor" fill="none"/><path d="M4 20a8 8 0 0 1 16 0" stroke="currentColor" fill="none"/></svg>
            </div>
        </div>
        <div class="kpi-value"><?= ucfirst(esc($role)) ?></div>
        <div class="kpi-delta up">Compte actif</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-label">IMC actuel</div>
            <div class="kpi-icon bg-green">
                <svg viewBox="0 0 24 24" style="width:20px;height:20px"><path d="M12 2l3 7h7l-5.5 4.2L18.5 21 12 16.8 5.5 21l2-7.8L2 9h7z" stroke="currentColor" fill="none"/></svg>
            </div>
        </div>
        <div class="kpi-value">—</div>
        <div class="kpi-delta up">À calculer</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-label">Objectifs assignés</div>
            <div class="kpi-icon bg-amber">
                <svg viewBox="0 0 24 24" style="width:20px;height:20px"><circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/><polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/></svg>
            </div>
        </div>
        <div class="kpi-value">—</div>
        <div class="kpi-delta up">En attente</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-label">Statut compte</div>
            <div class="kpi-icon bg-green">
                <svg viewBox="0 0 24 24" style="width:20px;height:20px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" fill="none"/><polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" fill="none"/></svg>
            </div>
        </div>
        <div class="kpi-value">Actif</div>
        <div class="kpi-delta up">Tous les accès</div>
    </div>
</div>

<!-- Dashboard Grid (2 colonnes) -->
<div class="dash-grid">
    <div class="card">
        <div style="display:flex;gap:20px;align-items:center;flex-wrap:wrap">
            <div class="avatar" style="width:88px;height:88px;font-size:28px;background:var(--c-primary);color:#fff;display:flex;align-items:center;justify-content:center"><?= substr(esc((string) ($user['name'] ?? 'U')), 0, 2) ?></div>
            <div style="flex:1;min-width:240px">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px">
                    <h3 style="font-size:22px">Bienvenue, <?= esc((string) ($user['name'] ?? 'Utilisateur')) ?></h3>
                    <span class="badge badge-blue">Utilisateur</span>
                    <span class="badge badge-green">Actif</span>
                </div>
                <p style="color:var(--c-muted);line-height:1.6;max-width:720px">
                    Vous êtes connecté en tant que <strong><?= esc($role) ?></strong>. 
                    Consultez votre profil, suivez vos objectifs et gérez votre programme nutritionnel.
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Actions rapides</div>
        <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px">
            <a href="<?= base_url('user/profile') ?>" class="btn btn-primary btn-full">Voir mon profil</a>
            <a href="<?= base_url('user/objectifs') ?>" class="btn btn-ghost btn-full">Mes objectifs</a>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-secondary btn-full">Se déconnecter</a>
        </div>
    </div>
</div>

<!-- Modules utilisateur -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Modules disponibles</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:16px">
        <a href="<?= base_url('user/imc') ?>" style="padding:16px;border:1.5px solid var(--c-border);border-radius:8px;text-decoration:none;color:var(--c-text);transition:all 0.2s;text-align:center">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:currentColor;fill:none;margin:0 auto 8px"><path d="M12 2l3 7h7l-5.5 4.2L18.5 21 12 16.8 5.5 21l2-7.8L2 9h7z"/></svg>
            <div style="font-weight:600;font-size:14px">Calcul IMC</div>
            <div style="font-size:12px;color:var(--c-muted)">Suivi santé</div>
        </a>
        <a href="<?= base_url('user/objectifs') ?>" style="padding:16px;border:1.5px solid var(--c-border);border-radius:8px;text-decoration:none;color:var(--c-text);transition:all 0.2s;text-align:center">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:currentColor;fill:none;margin:0 auto 8px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div style="font-weight:600;font-size:14px">Objectifs</div>
            <div style="font-size:12px;color:var(--c-muted)">Vos cibles</div>
        </a>
        <a href="<?= base_url('user/profile') ?>" style="padding:16px;border:1.5px solid var(--c-border);border-radius:8px;text-decoration:none;color:var(--c-text);transition:all 0.2s;text-align:center">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:currentColor;fill:none;margin:0 auto 8px"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>
            <div style="font-weight:600;font-size:14px">Profil</div>
            <div style="font-size:12px;color:var(--c-muted)">Mes infos</div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>