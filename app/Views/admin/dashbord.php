<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $role = (string) ($user['role'] ?? ''); ?>

<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <div class="breadcrumb">Votre espace de supervision</div>
    </div>
</div>

<div class="dashboard-grid">
    <article class="dashboard-card dashboard-card-wide">
        <h2>Bienvenue, <?= esc((string) ($user['name'] ?? 'Utilisateur')) ?></h2>
        <p>Vous êtes connecté en tant que <strong><?= esc($role) ?></strong>.</p>
        <div class="dashboard-badges">
            <span class="badge badge-blue">ID utilisateur: <?= esc((string) ($user['id'] ?? '-')) ?></span>
            <span class="badge badge-green">Rôle: <?= esc($role) ?></span>
        </div>
    </article>

    <article class="dashboard-card">
        <h3>Espace administrateur</h3>
        <p>Cette zone est visible uniquement pour les administrateurs.</p>
        <ul class="dashboard-list">
            <li>Gestion des utilisateurs</li>
            <li>Validation des accès</li>
            <li>Supervision des données</li>
        </ul>
    </article>

    <article class="dashboard-card">
        <h3>Vérification session</h3>
        <p>Email: <?= esc((string) ($user['email'] ?? '-')) ?></p>
        <p>Role ID: <?= esc((string) ($user['role_id'] ?? '-')) ?></p>
    </article>
</div>
<?= $this->endSection() ?>