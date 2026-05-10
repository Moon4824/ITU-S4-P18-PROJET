<?= $this->extend('layouts/model') ?>
<?= $this->section('content') ?>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Ajouter un code argent</span>
        <a href="<?= base_url('admin/codes') ?>" class="btn btn-ghost btn-sm">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Retour
        </a>
    </div>
    <div class="card-body">

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:16px">
                    <?php foreach ($errors as $e) : ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/codes/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-section-title">1. Informations du code</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="field-label" for="code">
                        Code (15 chiffres) <span class="required">*</span>
                    </label>
                    <div style="display:flex;gap:8px">
                        <input type="text" id="code" name="code"
                               value="<?= esc(old('code')) ?>"
                               maxlength="15"
                               placeholder="Ex : 123456789012345"
                               style="font-family:monospace;letter-spacing:2px;font-size:15px"
                               class="<?= isset($errors['code']) ? 'input-error' : '' ?>">
                        <button type="button" class="btn btn-ghost" onclick="genCode()" title="Générer automatiquement">
                            <svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        </button>
                    </div>
                    <?php if (isset($errors['code'])) : ?>
                        <span class="error-msg"><?= esc($errors['code']) ?></span>
                    <?php endif; ?>
                    <span style="font-size:11px;color:var(--c-muted);margin-top:3px">
                        Exactement 15 chiffres. Utilisez le bouton pour générer automatiquement.
                    </span>
                </div>

                <div class="form-group">
                    <label class="field-label" for="valeur">
                        Valeur (Ar) <span class="required">*</span>
                    </label>
                    <input type="number" id="valeur" name="valeur"
                           step="0.01" min="1"
                           value="<?= esc(old('valeur')) ?>"
                           placeholder="Ex : 50.00"
                           class="<?= isset($errors['valeur']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['valeur'])) : ?>
                        <span class="error-msg"><?= esc($errors['valeur']) ?></span>
                    <?php endif; ?>
                </div>

            </div>

            <div class="form-actions">
                <a href="<?= base_url('admin/codes') ?>" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function genCode() {
    let code = '';
    for (let i = 0; i < 15; i++) code += Math.floor(Math.random() * 10);
    document.getElementById('code').value = code;
}
</script>

<?= $this->endSection() ?>