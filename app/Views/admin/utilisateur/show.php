<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title"><?= esc($subtitle) ?></span>
        <div class="actions">
            <a href="<?= base_url('admin/utilisateurs') ?>" class="btn btn-ghost btn-sm">
                <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:6px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Retour
            </a>
            <a href="<?= base_url('admin/utilisateurs/edit/' . $user['id']) ?>" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:6px"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="profile-header" style="display:flex; align-items:center; gap:20px; margin-bottom:30px;">
            <div class="avatar" style="width:80px; height:80px; font-size:24px;">
                <?= strtoupper(substr($user['nom'], 0, 1)) ?>
            </div>
            <div>
                <h2 style="margin:0; font-size:24px; font-weight:600;"><?= esc($user['nom']) ?></h2>
                <p style="margin:0; color:var(--c-muted);"><?= esc($user['email']) ?></p>
                <div style="margin-top:8px;">
                    <?php if (($user['role_label'] ?? '') === 'admin') : ?>
                        <span class="badge badge-blue">Admin</span>
                    <?php else : ?>
                        <span class="badge badge-green">Utilisateur</span>
                    <?php endif; ?>
                    
                    <?php if ((int)$user['est_gold'] === 1) : ?>
                        <span class="badge badge-gold">⭐ Gold</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="details-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
            
            <div class="detail-item">
                <label style="display:block; font-size:12px; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">Date de naissance</label>
                <div style="font-weight:500;">
                    <?= date('d/m/Y', strtotime($user['date_naissance'])) ?>
                </div>
            </div>

            <div class="detail-item">
                <label style="display:block; font-size:12px; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">Genre</label>
                <div style="font-weight:500;"><?= ucfirst(esc($user['genre'])) ?></div>
            </div>

            <div class="detail-item">
                <label style="display:block; font-size:12px; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">Taille</label>
                <div style="font-weight:500;"><?= esc($user['taille']) ?> m</div>
            </div>

            <div class="detail-item">
                <label style="display:block; font-size:12px; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">Poids actuel</label>
                <div style="font-weight:500;"><?= esc($user['poids_actuel']) ?> kg</div>
            </div>

            <div class="detail-item">
                <label style="display:block; font-size:12px; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">Solde Monnaie</label>
                <div style="font-weight:500; color:var(--c-primary);"><?= number_format((float)$user['solde_monnaie'], 2, ',', ' ') ?> Ar</div>
            </div>

            <div class="detail-item">
                <label style="display:block; font-size:12px; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">Inscrit le</label>
                <div style="font-weight:500;">
                    <?= isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'N/A' ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>