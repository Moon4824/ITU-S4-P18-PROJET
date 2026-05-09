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

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= old('nom') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="id_role">Rôle</label>
                    <select name="id_role" id="id_role" class="form-control" required>
                        <option value="">-- Choisir un rôle --</option>
                        <?php foreach ($roles as $role) : ?>
                            <option value="<?= $role['id'] ?>" <?= old('id_role') == $role['id'] ? 'selected' : '' ?>>
                                <?= ucfirst(esc($role['role'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="<?= old('date_naissance') ?>" required>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <select name="genre" id="genre" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="homme" <?= old('genre') === 'homme' ? 'selected' : '' ?>>Homme</option>
                        <option value="femme" <?= old('genre') === 'femme' ? 'selected' : '' ?>>Femme</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="poids_actuel">Poids (kg)</label>
                    <input type="number" step="0.01" name="poids_actuel" id="poids_actuel" class="form-control" value="<?= old('poids_actuel') ?>" required>
                </div>
                <div class="form-group">
                    <label for="taille">Taille (m)</label>
                    <input type="number" step="0.01" name="taille" id="taille" class="form-control" value="<?= old('taille') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="solde_monnaie">Solde initial (Ar)</label>
                    <input type="number" step="0.01" name="solde_monnaie" id="solde_monnaie" class="form-control" value="<?= old('solde_monnaie', 0) ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; margin-top:24px;">
                    <input type="checkbox" name="est_gold" id="est_gold" value="1" <?= old('est_gold') ? 'checked' : '' ?>>
                    <label for="est_gold" style="margin:0; font-weight:500;">Compte Gold</label>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px; text-align:right;">
                <button type="reset" class="btn btn-ghost">Réinitialiser</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:6px"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>