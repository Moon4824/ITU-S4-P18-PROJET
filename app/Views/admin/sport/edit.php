<?= $this->extend('layouts/model') ?>
<?= $this->section('content') ?>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Modifier le sport</span>
        <a href="<?= base_url('admin/sports') ?>" class="btn btn-ghost btn-sm">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Retour à la liste
        </a>
    </div>

    <div class="card-body">
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <ul style="margin:0; padding-left:16px">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/sports/update/' . $sport['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-section-title">1. Modification des informations</div>

            <div class="form-grid">

                <div class="form-group">
                    <label class="field-label" for="nom">
                        Nom du sport <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= esc(old('nom', $sport['nom'])) ?>"
                        placeholder="Ex : Natation"
                        class="<?= isset($errors['nom']) ? 'input-error' : '' ?>"
                    >
                    <?php if (isset($errors['nom'])) : ?>
                        <span class="error-msg"><?= esc($errors['nom']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="field-label" for="apport_poids">
                        Effet sur le poids <span class="required">*</span>
                    </label>
                    <?php $currentApport = old('apport_poids', (string) $sport['apport_poids']); ?>
                    <select
                        id="apport_poids"
                        name="apport_poids"
                        class="<?= isset($errors['apport_poids']) ? 'input-error' : '' ?>"
                    >
                        <option value="1"  <?= $currentApport === '1'  ? 'selected' : '' ?>>↑ Prise de poids (+1)</option>
                        <option value="-1" <?= $currentApport === '-1' ? 'selected' : '' ?>>↓ Perte de poids (−1)</option>
                        <option value="0"  <?= $currentApport === '0'  ? 'selected' : '' ?>>→ Neutre (0)</option>
                    </select>
                    <?php if (isset($errors['apport_poids'])) : ?>
                        <span class="error-msg"><?= esc($errors['apport_poids']) ?></span>
                    <?php endif; ?>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <a href="<?= base_url('admin/sports') ?>" class="btn btn-ghost">Annuler</a>
                <a href="<?= base_url('admin/sports/show/' . $sport['id']) ?>" class="btn btn-ghost">
                    <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Voir
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Mettre à jour
                </button>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>