<?= $this->extend('layouts/model') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <span class="card-title"><?= esc($title) ?></span>
        <a href="<?= base_url('admin/utilisateurs') ?>" class="btn btn-ghost btn-sm">
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:6px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Retour à la liste
        </a>
    </div>

    <div class="card-body">
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:20px;">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/utilisateurs/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?= old('nom') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
                <small style="color:var(--c-muted);">Minimum 8 caractères.</small>
            </div>

            <div class="form-actions" style="margin-top:24px; text-align:right;">
                <button type="reset" class="btn btn-ghost">Réinitialiser</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:6px"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Créer l'administrateur
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>