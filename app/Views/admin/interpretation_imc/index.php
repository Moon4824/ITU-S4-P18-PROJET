<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success"><?= esc((string) session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= esc((string) session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Interprétations IMC</span>
        <div class="search-row">
            <form method="get" action="<?= base_url('admin/imc/interpretations') ?>" class="search-form">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="<?= esc((string) ($keyword ?? '')) ?>" placeholder="Rechercher une interprétation…">
                </div>
                <button class="btn btn-ghost btn-sm" type="submit">Filtrer</button>
            </form>
            <a href="<?= base_url('admin/imc/interpretations/create') ?>" class="btn btn-primary">
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
                    <th>Libellé</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($interpretations)) : ?>
                    <tr>
                        <td colspan="5" class="empty-row">Aucune interprétation IMC trouvée.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($interpretations as $interpretation) : ?>
                        <tr>
                            <td><?= esc((string) $interpretation['id']) ?></td>
                            <td><strong><?= esc((string) $interpretation['libelle']) ?></strong></td>
                            <td><?= $interpretation['min'] !== null ? esc(number_format((float) $interpretation['min'], 2, ',', ' ')) : '—' ?></td>
                            <td><?= $interpretation['max'] !== null ? esc(number_format((float) $interpretation['max'], 2, ',', ' ')) : '—' ?></td>
                            <td>
                                <div class="actions" style="justify-content:flex-end">
                                    <a href="<?= base_url('admin/imc/interpretations/edit/' . $interpretation['id']) ?>" class="btn btn-ghost btn-sm">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Modifier
                                    </a>
                                    <button class="btn btn-danger btn-sm"
                                            onclick="openDeleteModal('<?= base_url('admin/imc/interpretations/delete/' . $interpretation['id']) ?>', '<?= esc((string) $interpretation['libelle']) ?>')">
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
        <div class="pagination-wrap"><?= $pager->links() ?></div>
    <?php endif; ?>
</div>

<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </div>
        <h3>Confirmer la suppression</h3>
        <p>Supprimer l'interprétation <strong id="modal-label"></strong> ? Cette action est irréversible.</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeDeleteModal()">Annuler</button>
            <a href="#" id="modal-delete-link" class="btn btn-danger">Supprimer</a>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(url, label) {
        document.getElementById('modal-label').textContent = label;
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