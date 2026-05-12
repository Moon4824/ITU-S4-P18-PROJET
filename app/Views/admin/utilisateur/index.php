<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Utilisateurs enregistrés</span>
        <div class="search-row">
            <form method="get" action="<?= base_url('admin/utilisateurs') ?>" class="search-form">
                <div class="search-wrap">
                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Nom ou email…">
                </div>
                <select name="role" style="width:140px">
                    <option value="">Tous les rôles</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= esc($r['role']) ?>" <?= ($roleFilter ?? '') === $r['role'] ? 'selected' : '' ?>>
                            <?= ucfirst(esc($r['role'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-ghost btn-sm" type="submit">Filtrer</button>
                <?php if ($keyword || $roleFilter): ?>
                    <a href="<?= base_url('admin/utilisateurs') ?>" class="btn btn-ghost btn-sm">✕ Reset</a>
                <?php endif; ?>
            </form>
            <a href="<?= base_url('admin/utilisateurs/create') ?>" class="btn btn-primary">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Ajouter
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Genre</th>
                    <th>Gold</th>
                    <th>Solde</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <!-- ... Début du fichier inchangé ... -->
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="empty-row">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= esc($u['id']) ?></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px">
                                    <div class="avatar" style="width:30px;height:30px;font-size:11px;flex-shrink:0">
                                        <?= strtoupper(substr($u['nom'], 0, 1)) ?>
                                    </div>
                                    <?= esc($u['nom']) ?>
                                </div>
                            </td>
                            <td style="color:var(--c-muted)"><?= esc($u['email']) ?></td>
                            <td>
                                <?php if (($u['role_label'] ?? '') === 'admin'): ?>
                                    <span class="badge badge-blue">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-green">Utilisateur</span>
                                <?php endif; ?>
                            </td>
                            <td><?= ucfirst(esc($u['genre'])) ?></td>
                            <td>
                                <?php if ((int) $u['est_gold'] === 1): ?>
                                    <span class="badge badge-gold">⭐ Gold</span>
                                <?php else: ?>
                                    <span style="color:var(--c-muted);font-size:12px">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format((float) $u['solde_monnaie'], 2) ?> Ar</td>
                            <td>
                                <div class="actions" style="justify-content:flex-end">
                                    <!-- Bouton Voir : Toujours visible -->
                                    <a href="<?= base_url('admin/utilisateurs/show/' . $u['id']) ?>"
                                        class="btn btn-ghost btn-sm">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        Voir
                                    </a>

                                    <?php if (($u['role_label'] ?? '') === 'admin'): ?>
                                        <!-- Bouton Modifier : Visible SEULEMENT pour les admins -->
                                        <a href="<?= base_url('admin/utilisateurs/edit/' . $u['id']) ?>"
                                            class="btn btn-ghost btn-sm">
                                            <svg viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                            Modifier
                                        </a>

                                        <!-- Bouton Supprimer : Visible SEULEMENT pour les admins -->
                                        <?php if ((int) session()->get('user_id') !== (int) $u['id']): ?>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="openDeleteModal('<?= base_url('admin/utilisateurs/delete/' . $u['id']) ?>', '<?= esc($u['nom']) ?>')">
                                                <svg viewBox="0 0 24 24">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                </svg>
                                                Supprimer
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- ... Reste du fichier inchangé ... -->

    <?php if (isset($pager)): ?>
        <div class="pagination-wrap">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal confirmation suppression -->
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="3 6 5 6 21 6" />
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                <path d="M10 11v6M14 11v6" />
                <path d="M9 6V4h6v2" />
            </svg>
        </div>
        <h3>Confirmer la suppression</h3>
        <p>Voulez-vous vraiment supprimer le compte de <strong id="modal-user-name"></strong> ? Cette action est
            irréversible.</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeDeleteModal()">Annuler</button>
            <a href="#" id="modal-delete-link" class="btn btn-danger">Supprimer</a>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(url, name) {
        document.getElementById('modal-user-name').textContent = name;
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