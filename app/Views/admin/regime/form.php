<?php
$regime = $regime ?? null;
$detail = $detail ?? null;
$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');

$regimeId = is_array($regime) ? ($regime['id'] ?? null) : null;

$isEdit = !empty($regimeId);
$pageTitle = $isEdit ? 'Modifier un régime' : 'Créer un régime';
$pageSubtitle = $isEdit
    ? 'Mettre à jour le régime et ses détails nutritionnels.'
    : 'Créer un nouveau régime avec sa composition complète.';
$formAction = $isEdit
    ? '/admin/regime/update/' . $regimeId
    : '/admin/regime/store';
$submitLabel = $isEdit ? 'Mettre à jour le régime' : 'Créer le régime';

$nom = old('nom', $regime['nom'] ?? '');
$pctViande = old('pct_viande', $regime['pct_viande'] ?? '');
$pctPoisson = old('pct_poisson', $regime['pct_poisson'] ?? '');
$pctVolaille = old('pct_volaille', $regime['pct_volaille'] ?? '');
$duree = old('duree', $detail['duree'] ?? '');
$prix = old('prix', $detail['prix'] ?? '');
$variationPoids = old('variation_poids', $detail['variation_poids'] ?? '');
?>

<?= $this->extend('layouts/model') ?>

<?= $this->section('content') ?>
<div class="form-page">
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="/">Accueil</a> / <a href="/admin/regime">Régimes</a> / <span><?= esc($isEdit ? 'Modifier' : 'Nouveau') ?></span>
            </div>
            <h2><?= esc($pageTitle) ?></h2>
            <p><?= esc($pageSubtitle) ?></p>
        </div>

        <a href="/admin/regime" class="btn btn-secondary btn-sm">Retour à la liste</a>
    </div>

    <?php if (is_string($successMessage) && $successMessage !== '') : ?>
        <div class="alert alert-success"><?= esc($successMessage) ?></div>
    <?php endif; ?>

    <?php if (is_string($errorMessage) && $errorMessage !== '') : ?>
        <div class="alert alert-error"><?= esc($errorMessage) ?></div>
    <?php endif; ?>

    <div class="alert alert-info">
        <span>Les champs ci-dessous permettent de gérer le régime principal et ses détails nutritionnels dans un seul formulaire.</span>
    </div>

    <form action="<?= esc($formAction) ?>" method="post" class="regime-form">
        <?= csrf_field() ?>

        <div class="form-card">
            <div class="form-section-title">1. Informations du régime</div>
            <div class="form-grid">
                <div>
                    <label class="field-label" for="nom">Nom du régime <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom" value="<?= esc($nom) ?>" placeholder="Ex : Régime équilibré" required>
                </div>
                <div>
                    <label class="field-label" for="pct_viande">Pourcentage viande <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" id="pct_viande" name="pct_viande" value="<?= esc($pctViande) ?>" min="0" max="100" step="1" required>
                        <span class="addon addon-right">%</span>
                    </div>
                </div>
                <div>
                    <label class="field-label" for="pct_poisson">Pourcentage poisson <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" id="pct_poisson" name="pct_poisson" value="<?= esc($pctPoisson) ?>" min="0" max="100" step="1" required>
                        <span class="addon addon-right">%</span>
                    </div>
                </div>
                <div>
                    <label class="field-label" for="pct_volaille">Pourcentage volaille <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" id="pct_volaille" name="pct_volaille" value="<?= esc($pctVolaille) ?>" min="0" max="100" step="1" required>
                        <span class="addon addon-right">%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card section-gap">
            <div class="form-section-title">2. Détails du régime</div>
            <div class="form-grid cols-3">
                <div>
                    <label class="field-label" for="duree">Durée <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" id="duree" name="duree" value="<?= esc($duree) ?>" min="1" step="1" required>
                        <span class="addon addon-right">jours</span>
                    </div>
                </div>
                <div>
                    <label class="field-label" for="prix">Prix <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" id="prix" name="prix" value="<?= esc($prix) ?>" min="0" step="0.01" required>
                        <span class="addon addon-right">Ar</span>
                    </div>
                </div>
                <div>
                    <label class="field-label" for="variation_poids">Variation de poids <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="number" id="variation_poids" name="variation_poids" value="<?= esc($variationPoids) ?>" step="0.01" required>
                        <span class="addon addon-right">kg</span>
                    </div>
                </div>
            </div>
            <div class="field-hint">La variation peut être négative pour un déficit ou positive pour une prise de poids.</div>
        </div>

        <div class="form-footer">
            <a href="/admin/regime" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><?= esc($submitLabel) ?></button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>