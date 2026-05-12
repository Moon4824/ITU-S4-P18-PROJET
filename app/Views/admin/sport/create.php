<?php
/**
 * @var array<string,mixed>|null $errors
 */
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Ajouter un sport</span>
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
                        <li><?= esc((string) $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/sports/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-section-title">1. Informations du sport</div>

            <div class="form-grid">

                <div class="form-group">
                    <label class="field-label" for="nom">
                        Nom du sport <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= esc((string) old('nom')) ?>"
                        placeholder="Ex : Natation"
                        class="<?= isset($errors['nom']) ? 'input-error' : '' ?>"
                    >
                    <?php if (isset($errors['nom'])) : ?>
                        <span class="error-msg"><?= esc((string) $errors['nom']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="field-label" for="apport_poids">
                        Effet sur le poids <span class="required">*</span>
                    </label>
                    <select
                        id="apport_poids"
                        name="apport_poids"
                        class="<?= isset($errors['apport_poids']) ? 'input-error' : '' ?>"
                    >
                        <option value="" disabled <?= old('apport_poids') === null ? 'selected' : '' ?>>
                            -- Sélectionner --
                        </option>
                        <option value="1"  <?= old('apport_poids') == '1'  ? 'selected' : '' ?>>↑ Prise de poids (+1)</option>
                        <option value="-1" <?= old('apport_poids') == '-1' ? 'selected' : '' ?>>↓ Perte de poids (−1)</option>
                        <option value="0"  <?= old('apport_poids') == '0'  ? 'selected' : '' ?>>→ Neutre (0)</option>
                    </select>
                    <?php if (isset($errors['apport_poids'])) : ?>
                        <span class="error-msg"><?= esc((string) $errors['apport_poids']) ?></span>
                    <?php endif; ?>
                </div>

            </div><!-- /.form-grid -->

            <div class="form-actions">
                <a href="<?= base_url('admin/sports') ?>" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Enregistrer
                </button>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>