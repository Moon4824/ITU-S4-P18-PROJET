<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php
  $name = (string) ($user['name'] ?? 'Utilisateur');
  $email = (string) ($user['email'] ?? '');
  $role = (string) ($user['role'] ?? '');
  $initials = (string) ($initials ?? 'U');
  $profile = (array) ($profile ?? []);
  $errors = session()->getFlashdata('errors') ?? [];
?>

<div class="page-header">
    <div>
        <h2>Modifier mon profil</h2>
        <div class="breadcrumb">Accueil / <span>Profil / Édition</span></div>
    </div>
    <div style="display:flex;gap:10px">
        <a href="<?= base_url('user/profile') ?>" class="btn btn-secondary btn-sm">Retour au profil</a>
        <a href="<?= base_url('user/') ?>" class="btn btn-primary btn-sm">Tableau de bord</a>
    </div>
</div>

<?php if (! empty($errors)) : ?>
    <div class="alert alert-error">
        <strong>Veuillez corriger les champs indiqués.</strong>
        <ul style="margin:8px 0 0 18px; padding:0">
            <?php foreach ($errors as $error) : ?>
                <li><?= esc((string) $error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="dash-grid">
    <div class="card" style="display:flex;gap:20px;align-items:center;flex-wrap:wrap">
        <div class="avatar" style="width:88px;height:88px;font-size:28px;background:var(--c-primary);color:#fff;display:flex;align-items:center;justify-content:center"><?= esc($initials) ?></div>
        <div style="flex:1;min-width:240px">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px">
                <h3 style="font-size:22px"><?= esc($name) ?></h3>
                <span class="badge badge-green">Compte actif</span>
            </div>
            <p style="color:var(--c-muted);line-height:1.6;max-width:720px">
                Mettez à jour vos informations personnelles et vos mesures de santé.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Aide rapide</div>
        <div style="margin-top:16px;color:var(--c-muted);line-height:1.6">
            Les champs marqués sont ceux utilisés pour afficher votre profil et vos informations santé.
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Formulaire du profil</div>
    </div>

    <form action="<?= base_url('user/profile/update') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div>
                <label class="field-label" for="nom">Nom complet <span class="required">*</span></label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    value="<?= esc((string) old('nom', $profile['nom'] ?? '')) ?>"
                    class="<?= isset($errors['nom']) ? 'input-error' : '' ?>"
                    required
                >
                <?php if (isset($errors['nom'])) : ?><span class="error-msg"><?= esc((string) $errors['nom']) ?></span><?php endif; ?>
            </div>

            <div>
                <label class="field-label" for="email">Adresse e-mail <span class="required">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= esc((string) old('email', $profile['email'] ?? '')) ?>"
                    class="<?= isset($errors['email']) ? 'input-error' : '' ?>"
                    required
                >
                <?php if (isset($errors['email'])) : ?><span class="error-msg"><?= esc((string) $errors['email']) ?></span><?php endif; ?>
            </div>

            <div>
                <label class="field-label" for="date_naissance">Date de naissance <span class="required">*</span></label>
                <input
                    type="date"
                    id="date_naissance"
                    name="date_naissance"
                    value="<?= esc((string) old('date_naissance', $profile['date_naissance'] ?? '')) ?>"
                    class="<?= isset($errors['date_naissance']) ? 'input-error' : '' ?>"
                    required
                >
                <?php if (isset($errors['date_naissance'])) : ?><span class="error-msg"><?= esc((string) $errors['date_naissance']) ?></span><?php endif; ?>
            </div>

            <div>
                <label class="field-label" for="genre">Genre <span class="required">*</span></label>
                <?php $currentGenre = (string) old('genre', $profile['genre'] ?? ''); ?>
                <select id="genre" name="genre" class="<?= isset($errors['genre']) ? 'input-error' : '' ?>" required>
                    <option value="">Sélectionner</option>
                    <option value="homme" <?= $currentGenre === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= $currentGenre === 'femme' ? 'selected' : '' ?>>Femme</option>
                </select>
                <?php if (isset($errors['genre'])) : ?><span class="error-msg"><?= esc((string) $errors['genre']) ?></span><?php endif; ?>
            </div>

            <div>
                <label class="field-label" for="poids_actuel">Poids actuel <span class="required">*</span></label>
                <div class="input-group">
                    <input
                        type="number"
                        id="poids_actuel"
                        name="poids_actuel"
                        value="<?= esc((string) old('poids_actuel', $profile['poids_actuel'] ?? '')) ?>"
                        min="0"
                        step="0.01"
                        class="<?= isset($errors['poids_actuel']) ? 'input-error' : '' ?>"
                        required
                    >
                    <span class="addon addon-right">kg</span>
                </div>
                <?php if (isset($errors['poids_actuel'])) : ?><span class="error-msg"><?= esc((string) $errors['poids_actuel']) ?></span><?php endif; ?>
            </div>

            <div>
                <label class="field-label" for="taille">Taille <span class="required">*</span></label>
                <div class="input-group">
                    <input
                        type="number"
                        id="taille"
                        name="taille"
                        value="<?= esc((string) old('taille', $profile['taille'] ?? '')) ?>"
                        min="0"
                        step="0.01"
                        class="<?= isset($errors['taille']) ? 'input-error' : '' ?>"
                        required
                    >
                    <span class="addon addon-right">m</span>
                </div>
                <?php if (isset($errors['taille'])) : ?><span class="error-msg"><?= esc((string) $errors['taille']) ?></span><?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= base_url('user/profile') ?>" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>