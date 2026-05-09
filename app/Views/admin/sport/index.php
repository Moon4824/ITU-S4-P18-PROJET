<?php
/**
 * @var array<int,array<string,mixed>> $sports
 * @var \CodeIgniter\Pager\Pager|null $pager
 * @var string|null $keyword
 */
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success">
        <?= esc((string) session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= esc((string) session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Sports enregistrés</span>
        <div class="search-row">
            <form method="get" action="<?= base_url('admin/sports') ?>" class="search-form">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Rechercher un sport…">
                </div>
                <button class="btn btn-ghost btn-sm" type="submit">Filtrer</button>
            </form>
            <a href="<?= base_url('admin/sports/create') ?>" class="btn btn-primary">
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
                    <th>Nom du sport</th>
                    <th>Effet sur le poids</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sports)) : ?>
                    <tr>
                        <td colspan="4" class="empty-row">Aucun sport enregistré.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($sports as $sport) : ?>
                        <?php
                            $apport = (int) $sport['apport_poids'];
                            if ($apport === 1) {
                                $badgeClass = 'badge-up';
                                $badgeLabel = '↑ Prise de poids';
                            } elseif ($apport === -1) {
                                $badgeClass = 'badge-down';
                                $badgeLabel = '↓ Perte de poids';
                            } else {
                                $badgeClass = 'badge-neutral';
                                $badgeLabel = '→ Neutre';
                            }
                        ?>
                        <tr>
                            <td><?= esc((string) $sport['id']) ?></td>
                            <td><?= esc((string) $sport['nom']) ?></td>
                            <td>
                                <span class="badge <?= $badgeClass ?>">
                                    <?= $badgeLabel ?> (<?= $apport > 0 ? '+' . $apport : $apport ?>)
                                </span>
                            </td>
                            <td>
                                <div class="actions" style="justify-content:flex-end">
                                    <a href="<?= base_url('admin/sports/show/' . $sport['id']) ?>" class="btn btn-ghost btn-sm">
                                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Voir
                                    </a>
                                    <a href="<?= base_url('admin/sports/edit/' . $sport['id']) ?>" class="btn btn-ghost btn-sm">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Modifier
                                    </a>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="openDeleteModal('<?= base_url('admin/sports/delete/' . (string) $sport['id']) ?>', '<?= esc((string) $sport['nom']) ?>')">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
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
        <div class="pagination-wrap">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal confirmation suppression -->
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
        </div>
        <h3>Confirmer la suppression</h3>
        <p>Voulez-vous vraiment supprimer le sport <strong id="modal-sport-name"></strong> ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeDeleteModal()">Annuler</button>
            <a href="#" id="modal-delete-link" class="btn btn-danger">
                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                Supprimer
            </a>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(url, name) {
        document.getElementById('modal-sport-name').textContent = name;
        document.getElementById('modal-delete-link').href = url;
        document.getElementById('modal-delete').classList.add('open');
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.remove('open');
    }
    document.getElementById('modal-delete').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

<?= $this->endSection() ?>