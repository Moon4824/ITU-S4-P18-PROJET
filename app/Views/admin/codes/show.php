<?= $this->extend('layouts/model') ?>
<?= $this->section('content') ?>

<?php $actif = (int) $code['est_valide'] === 1; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Détail du code argent</span>
        <div style="display:flex;gap:8px">
            <a href="<?= base_url('admin/codes/edit/' . $code['id']) ?>" class="btn btn-ghost btn-sm">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier
            </a>
            <a href="<?= base_url('admin/codes/toggle/' . $code['id']) ?>"
               class="btn btn-sm <?= $actif ? 'btn-warning' : 'btn-success-outline' ?>">
                <?= $actif ? '⏸ Désactiver' : '▶ Activer' ?>
            </a>
            <a href="<?= base_url('admin/codes') ?>" class="btn btn-ghost btn-sm">
                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="form-section-title">Informations du code</div>

        <div class="detail-grid">

            <div class="detail-item">
                <div class="detail-label">Identifiant</div>
                <div class="detail-value">#<?= esc($code['id']) ?></div>
            </div>

            <div class="detail-item" style="grid-column:span 2">
                <div class="detail-label">Code</div>
                <div class="detail-value">
                    <code style="font-size:22px;letter-spacing:3px;font-weight:800;
                                 background:var(--c-bg);padding:8px 16px;
                                 border-radius:8px;display:inline-block;
                                 border:1px solid var(--c-border)">
                        <?= esc($code['code']) ?>
                    </code>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Valeur</div>
                <div class="detail-value" style="color:var(--c-success)">
                    <?= number_format((float) $code['valeur'], 2) ?> Ar
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Statut</div>
                <div class="detail-value">
                    <?php if ($actif) : ?>
                        <span class="badge badge-green">✅ Actif</span>
                    <?php else : ?>
                        <span class="badge badge-red">❌ Désactivé</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Utilisé par</div>
                <div class="detail-value">
                    <?php if ($code['id_utilisateur']) : ?>
                        <span style="color:var(--c-primary);font-weight:600">
                            Utilisateur #<?= esc($code['id_utilisateur']) ?>
                        </span>
                    <?php else : ?>
                        <span style="color:var(--c-muted);font-size:13px">Non utilisé</span>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <button class="btn btn-danger"
                    onclick="document.getElementById('modal-delete').classList.add('open')">
                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                Supprimer ce code
            </button>
        </div>
    </div>
</div>

<!-- Modal suppression -->
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
        </div>
        <h3>Confirmer la suppression</h3>
        <p>Supprimer le code <strong><?= esc($code['code']) ?></strong> définitivement ?</p>
        <div class="modal-actions">
            <button class="btn btn-ghost"
                    onclick="document.getElementById('modal-delete').classList.remove('open')">
                Annuler
            </button>
            <a href="<?= base_url('admin/codes/delete/' . $code['id']) ?>" class="btn btn-danger">
                Supprimer
            </a>
        </div>
    </div>
</div>

<style>
  .badge-red             { background:rgba(239,68,68,.12); color:var(--c-danger); padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; }
  .btn-warning           { background:rgba(245,158,11,.12); color:#b45309; border:1.5px solid rgba(245,158,11,.25); }
  .btn-warning:hover     { background:#f59e0b; color:#fff; }
  .btn-success-outline           { background:rgba(34,197,94,.12); color:var(--c-success); border:1.5px solid rgba(34,197,94,.25); }
  .btn-success-outline:hover     { background:var(--c-success); color:#fff; }
</style>

<script>
    document.getElementById('modal-delete').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
</script>

<?= $this->endSection() ?>