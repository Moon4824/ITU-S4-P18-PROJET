<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php 
  $name = (string) ($user['name'] ?? 'Utilisateur');
  $email = (string) ($user['email'] ?? '');
  $role = (string) ($user['role'] ?? '');
  $initials = (string) ($initials ?? 'U');
  $profile = (array) ($profile ?? []);
$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');
?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2>Profil utilisateur</h2>
        <div class="breadcrumb">Accueil / <span>Profil</span></div>
    </div>
    <div style="display:flex;gap:10px">
        <a href="<?= base_url('user/profile/edit') ?>" class="btn btn-secondary btn-sm">Modifier le compte</a>
        <a href="<?= base_url('user/') ?>" class="btn btn-primary btn-sm">Retour au tableau de bord</a>
    </div>
</div>

<?php if (is_string($successMessage) && $successMessage !== '') : ?>
    <div class="alert alert-success"><?= esc($successMessage) ?></div>
<?php endif; ?>

<?php if (is_string($errorMessage) && $errorMessage !== '') : ?>
    <div class="alert alert-error"><?= esc($errorMessage) ?></div>
<?php endif; ?>

<!-- Profile Card & Actions -->
<div class="dash-grid">
    <div class="card" style="display:flex;gap:20px;align-items:center;flex-wrap:wrap">
        <div class="avatar" style="width:88px;height:88px;font-size:28px;background:var(--c-primary);color:#fff;display:flex;align-items:center;justify-content:center"><?= esc($initials) ?></div>
        <div style="flex:1;min-width:240px">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px">
                <h3 style="font-size:22px"><?= esc($name) ?></h3>
                <span class="badge badge-blue"><?= esc($role) ?></span>
                <span class="badge badge-green">Compte actif</span>
            </div>
            <p style="color:var(--c-muted);line-height:1.6;max-width:720px">
                Bienvenue sur votre profil utilisateur. Gérez vos informations personnelles, votre santé et vos préférences.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Actions rapides</div>
        <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px">
            <a href="<?= base_url('user/profile/edit') ?>" class="btn btn-primary btn-full">Éditer le profil</a>
            <a href="<?= base_url('user/') ?>" class="btn btn-ghost btn-full">Tableau de bord</a>
            <form action="<?= base_url('auth/logout') ?>" method="post" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-secondary btn-full" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>

<!-- KPI Cards (2 colonnes) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-label">Poids actuel</div>
            <div class="kpi-icon bg-amber">
                <svg viewBox="0 0 24 24" style="width:20px;height:20px"><path d="M12 2l3 7h7l-5.5 4.2L18.5 21 12 16.8 5.5 21l2-7.8L2 9h7z" stroke="currentColor" fill="none"/></svg>
            </div>
        </div>
        <div class="kpi-value"><?= esc((string) ($profile['poids_actuel'] ?? '-')) ?></div>
        <div class="kpi-delta up">kg</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-label">Statut</div>
            <div class="kpi-icon bg-green">
                <svg viewBox="0 0 24 24" style="width:20px;height:20px"><circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/><polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/></svg>
            </div>
        </div>
        <div class="kpi-value">Actif</div>
        <div class="kpi-delta up">Compte autorisé</div>
    </div>
</div>

<!-- Information Cards (2 colonnes) -->
<div class="dash-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informations personnelles</div>
        </div>
        <div class="form-grid">
            <div>
                <div class="field-label">Nom complet</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc($name) ?></div>
            </div>
            <div>
                <div class="field-label">Adresse e-mail</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc($email) ?></div>
            </div>
            <div>
                <div class="field-label">Date de naissance</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc((string) ($profile['date_naissance'] ?? '-')) ?></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Mesures santé</div>
        </div>
        <div class="form-grid">
            <div>
                <div class="field-label">Genre</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc((string) ($profile['genre'] ?? '-')) ?></div>
            </div>
            <div>
                <div class="field-label">Taille</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc((string) ($profile['taille'] ?? '-')) ?> cm</div>
            </div>
            <div>
                <div class="field-label">Poids</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc((string) ($profile['poids_actuel'] ?? '-')) ?> kg</div>
            </div>
            <div>
                <div class="field-label">Solde monnaie</div>
                <div class="field-hint" style="font-size:13px;color:var(--c-text)"><?= esc((string) ($profile['solde_monnaie'] ?? '0')) ?> €</div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
