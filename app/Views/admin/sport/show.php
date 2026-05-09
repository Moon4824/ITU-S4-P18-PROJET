<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
    $apport = (int) $sport['apport_poids'];
    if ($apport === 1) {
        $badgeClass = 'badge-up';
        $badgeLabel = '↑ Prise de poids (+1)';
    } elseif ($apport === -1) {
        $badgeClass = 'badge-down';
        $badgeLabel = '↓ Perte de poids (−1)';
    } else {
        $badgeClass = 'badge-neutral';
        $badgeLabel = '→ Neutre (0)';
    }
?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Détail du sport</span>
        <div style="display:flex; gap:8px">
            <a href="<?= base_url('admin/sports/edit/' . $sport['id']) ?>" class="btn btn-ghost btn-sm">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier
            </a>
            <a href="<?= base_url('admin/sports') ?>" class="btn btn-ghost btn-sm">
                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="form-section-title">Informations enregistrées</div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Identifiant</div>
                <div class="detail-value">#<?= esc($sport['id']) ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Nom du sport</div>
                <div class="detail-value"><?= esc($sport['nom']) ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Effet sur le poids</div>
                <div class="detail-value">
                    <span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                </div>
            </div>
        </div>

        <div class="form-actions" style="margin-top:28px">
            <button
                class="btn btn-danger"
                onclick="document.getElementById('modal-delete').classList.add('open')">
                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                Supprimer ce sport
            </button>
        </div>
    </div>
</div>

<!-- Modal confirmation suppression -->
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
        </div>
        <h3>Confirmer la suppression</h3>
        <p>Voulez-vous vraiment supprimer <strong><?= esc($sport['nom']) ?></strong> ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="document.getElementById('modal-delete').classList.remove('open')">
                Annuler
            </button>
            <a href="<?= base_url('admin/sports/delete/' . $sport['id']) ?>" class="btn btn-danger">
                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                Supprimer
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('modal-delete').addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>

<?= $this->endSection() ?>