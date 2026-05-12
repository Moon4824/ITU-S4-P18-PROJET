<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');
$flashToString = static function ($message): string {
    if (is_array($message)) {
        return implode(' ', array_map(static fn ($item) => is_scalar($item) ? (string) $item : '', $message));
    }

    return (string) ($message ?? '');
};
$keyword = $keyword ?? '';
$statut = $statut ?? '';
?>

<?php if ($successMessage) : ?>
    <div class="alert alert-success"><?= esc($flashToString($successMessage)) ?></div>
<?php endif; ?>
<?php if ($errorMessage) : ?>
    <div class="alert alert-danger"><?= esc($flashToString($errorMessage)) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Liste des codes argent</span>
        <div class="search-row">
            <form method="get" action="<?= base_url('admin/codes') ?>" class="search-form">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Rechercher un code…">
                </div>
                <select name="statut" style="width:150px">
                    <option value="">Tous les statuts</option>
                    <option value="valide"   <?= ($statut ?? '') === 'valide'   ? 'selected' : '' ?>>✅ Actifs</option>
                    <option value="invalide" <?= ($statut ?? '') === 'invalide' ? 'selected' : '' ?>>❌ Désactivés</option>
                </select>
                <button class="btn btn-ghost btn-sm" type="submit">Filtrer</button>
                <?php if ($keyword || $statut) : ?>
                    <a href="<?= base_url('admin/codes') ?>" class="btn btn-ghost btn-sm">✕ Reset</a>
                <?php endif; ?>
            </form>
            <a href="<?= base_url('admin/codes/create') ?>" class="btn btn-primary">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Ajouter
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Valeur</th>
                    <th>Statut</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($codes)) : ?>
                    <tr><td colspan="5" class="empty-row">Aucun code trouvé.</td></tr>
                <?php else : ?>
                    <?php foreach ($codes as $c) : ?>
                        <?php $actif = (int) $c['est_valide'] === 1; ?>
                        <?php $codeId = (string) ($c['id'] ?? ''); ?>
                        <?php $codeValue = (string) ($c['code'] ?? ''); ?>
                        <tr>
                            <td><?= esc($codeId) ?></td>
                            <td>
                                <code style="background:var(--c-bg);padding:3px 10px;border-radius:6px;
                                             font-size:13px;letter-spacing:2px;font-weight:700">
                                    <?= esc($codeValue) ?>
                                </code>
                            </td>
                            <td>
                                <strong style="color:var(--c-success)">
                                    <?= number_format((float) $c['valeur'], 2) ?> Ar
                                </strong>
                            </td>
                            <td>
                                <?php if ($actif) : ?>
                                    <span class="badge badge-green">✅ Actif</span>
                                <?php else : ?>
                                    <span class="badge badge-red">❌ Désactivé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions" style="justify-content:flex-end">
                                    <a href="<?= base_url('admin/codes/show/' . $c['id']) ?>" class="btn btn-ghost btn-sm">
                                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Voir
                                    </a>
                                    <a href="<?= base_url('admin/codes/edit/' . $c['id']) ?>" class="btn btn-ghost btn-sm">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Modifier
                                    </a>
                                    <a href="<?= base_url('admin/codes/toggle/' . $c['id']) ?>"
                                       class="btn btn-sm <?= $actif ? 'btn-warning' : 'btn-success-outline' ?>">
                                        <?= $actif ? '⏸ Désactiver' : '▶ Activer' ?>
                                    </a>
                                    <button class="btn btn-danger btn-sm"
                                            onclick="openDeleteModal('<?= base_url('admin/codes/delete/' . $codeId) ?>', '<?= esc($codeValue) ?>')">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pager)) : ?>
        <div class="pagination-wrap"><?= $pager->links() ?></div>
    <?php endif; ?>
</div>

<!-- Modal suppression -->
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </div>
        <h3>Confirmer la suppression</h3>
        <p>Supprimer le code <strong id="modal-code-val"></strong> ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeDeleteModal()">Annuler</button>
            <a href="#" id="modal-delete-link" class="btn btn-danger">Supprimer</a>
        </div>
    </div>
</div>

<style>
  .badge-red         { background:rgba(239,68,68,.12); color:var(--c-danger); padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; }
  .btn-warning       { background:rgba(245,158,11,.12); color:#b45309; border:1.5px solid rgba(245,158,11,.25); }
  .btn-warning:hover { background:#f59e0b; color:#fff; }
  .btn-success-outline       { background:rgba(34,197,94,.12); color:var(--c-success); border:1.5px solid rgba(34,197,94,.25); }
  .btn-success-outline:hover { background:var(--c-success); color:#fff; }
</style>

<script>
    function openDeleteModal(url, code) {
        document.getElementById('modal-code-val').textContent = code;
        document.getElementById('modal-delete-link').href = url;
        document.getElementById('modal-delete').classList.add('open');
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.remove('open');
    }
    document.getElementById('modal-delete').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

<?= $this->endSection() ?>