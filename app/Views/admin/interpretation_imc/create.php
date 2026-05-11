<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">Ajouter une interprétation IMC</span>
    </div>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger">
            <?= esc(implode(' ', array_values((array) session()->getFlashdata('errors')))) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/imc/interpretations/store') ?>" style="max-width:640px;display:grid;gap:16px;">
        <?= csrf_field() ?>
        <div>
            <label for="libelle" style="display:block;margin-bottom:6px;font-weight:600;">Libellé</label>
            <input id="libelle" name="libelle" type="text" class="form-control" value="<?= esc((string) old('libelle')) ?>" required>
        </div>
        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;">
            <div>
                <label for="min" style="display:block;margin-bottom:6px;font-weight:600;">Min</label>
                <input id="min" name="min" type="number" step="0.01" class="form-control" value="<?= esc((string) old('min')) ?>">
            </div>
            <div>
                <label for="max" style="display:block;margin-bottom:6px;font-weight:600;">Max</label>
                <input id="max" name="max" type="number" step="0.01" class="form-control" value="<?= esc((string) old('max')) ?>">
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <a href="<?= base_url('admin/imc/interpretations') ?>" class="btn btn-ghost">Annuler</a>
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>