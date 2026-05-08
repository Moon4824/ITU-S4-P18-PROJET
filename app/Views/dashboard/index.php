<?= $this->extend('layouts/model') ?>

<?= $this->section('content') ?>
<?php $role = (string) ($user['role'] ?? ''); ?>

<div class="dashboard-grid">
    <article class="dashboard-card dashboard-card-wide">
        <h2>Bienvenue, <?= esc((string) ($user['name'] ?? 'Utilisateur')) ?></h2>
        <p>Vous êtes connecté en tant que <strong><?= esc($role) ?></strong>.</p>
        <div class="dashboard-badges">
            <span class="badge badge-blue">ID utilisateur: <?= esc((string) ($user['id'] ?? '-')) ?></span>
            <span class="badge badge-green">Rôle: <?= esc($role) ?></span>
            <?php if (!empty(session()->get('selected_objective_label'))) : ?>
                <span class="badge badge-amber">Objectif: <?= esc((string) session()->get('selected_objective_label')) ?></span>
            <?php endif; ?>
        </div>
    </article>

    <?php if ($role === 'admin') : ?>
        <article class="dashboard-card">
            <h3>Espace administrateur</h3>
            <p>Cette zone est visible uniquement pour les administrateurs.</p>
            <ul class="dashboard-list">
                <li>Gestion des utilisateurs</li>
                <li>Validation des accès</li>
                <li>Supervision des données</li>
            </ul>
        </article>
    <?php else : ?>
        <article class="dashboard-card">
            <h3>Espace utilisateur</h3>
            <p>Cette zone confirme que le compte normal passe bien le filtre de rôle.</p>
            <ul class="dashboard-list">
                <li>Voir le profil</li>
                <li>Consulter les régimes</li>
                <li>Suivre le programme</li>
            </ul>
        </article>
    <?php endif; ?>

    <article class="dashboard-card">
        <h3>Vérification session</h3>
        <p>Email: <?= esc((string) ($user['email'] ?? '-')) ?></p>
        <p>Role ID: <?= esc((string) ($user['role_id'] ?? '-')) ?></p>
    </article>
</div>
<?= $this->endSection() ?>
