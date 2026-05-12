<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Modifier le code argent</span>
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

        <form action="<?= base_url('admin/codes/update/' . $code['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-section-title">1. Modification du code</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="field-label" for="code">
                        Code (15 chiffres) <span class="required">*</span>
                    </label>
                    <input type="text" id="code" name="code"
                           value="<?= esc(old('code', $code['code'])) ?>"
                           maxlength="15"
                           style="font-family:monospace;letter-spacing:2px;font-size:15px"
                           class="<?= isset($errors['code']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['code'])) : ?>
                        <span class="error-msg"><?= esc($errors['code']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="field-label" for="valeur">
                        Valeur (Ar) <span class="required">*</span>
                    </label>
                    <input type="number" id="valeur" name="valeur"
                           step="0.01" min="1"
                           value="<?= esc(old('valeur', $code['valeur'])) ?>"
                           class="<?= isset($errors['valeur']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['valeur'])) : ?>
                        <span class="error-msg"><?= esc($errors['valeur']) ?></span>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Statut actuel -->
            <div style="margin-top:20px;padding:14px 16px;background:var(--c-bg);
                        border:1px solid var(--c-border);border-radius:var(--radius);
                        display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:12px;color:var(--c-muted);margin-bottom:3px">Statut actuel</div>
                    <?php if ((int) $code['est_valide'] === 1) : ?>
                        <span class="badge badge-green">✅ Actif</span>
                    <?php else : ?>
                        <span class="badge badge-red">❌ Désactivé</span>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('admin/codes/toggle/' . $code['id']) ?>"
                   class="btn btn-sm <?= (int) $code['est_valide'] === 1 ? 'btn-warning' : 'btn-success-outline' ?>">
                    <?= (int) $code['est_valide'] === 1 ? '⏸ Désactiver' : '▶ Activer' ?>
                </a>
            </div>

            <div class="form-actions">
                <a href="<?= base_url('admin/codes') ?>" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<style>
  .badge-red             { background:rgba(239,68,68,.12); color:var(--c-danger); padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; }
  .btn-warning           { background:rgba(245,158,11,.12); color:#b45309; border:1.5px solid rgba(245,158,11,.25); }
  .btn-warning:hover     { background:#f59e0b; color:#fff; }
  .btn-success-outline           { background:rgba(34,197,94,.12); color:var(--c-success); border:1.5px solid rgba(34,197,94,.25); }
  .btn-success-outline:hover     { background:var(--c-success); color:#fff; }
</style>

<?= $this->endSection() ?>