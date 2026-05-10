<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php
    $objectifs = $objectifs ?? [];
    $selectedObjective = $selectedObjective ?? null;
    $suggestions = $suggestions ?? [];
    $step = (int) ($step ?? 1);
    $old = $old ?? [];
    $validationErrors = $validationErrors ?? [];
    $errorMessage = $errorMessage ?? null;
    $poidsActuel = $poidsActuel ?? 0;
    $taille = $taille ?? 0;
    $imcActuel = $imcActuel ?? null;
    $poidsIdeal = $poidsIdeal ?? null;
    $poidsObjectif = $old['poids_objectif'] ?? ($poidsIdeal ?? '');
    $dateDebut = $old['date_debut'] ?? date('Y-m-d');
?>
<div class="dashboard-grid">
    <article class="dashboard-card dashboard-card-wide">
        <h2>Choisissez votre objectif</h2>
        <p>Remplis l'étape 1, puis la liste des régimes compatibles s'affiche avec le calcul de durée et de prix.</p>

        <div class="dashboard-badges" style="margin-top:12px;gap:8px;flex-wrap:wrap;">
            <span class="badge badge-green">IMC actuel : <?= $imcActuel !== null ? esc(number_format((float) $imcActuel, 2, ',', ' ')) : 'n/a' ?></span>
            <span class="badge badge-blue">Poids actuel : <?= esc(number_format((float) $poidsActuel, 2, ',', ' ')) ?> kg</span>
            <span class="badge badge-amber">Poids idéal : <?= $poidsIdeal !== null ? esc(number_format((float) $poidsIdeal, 2, ',', ' ')) . ' kg' : 'n/a' ?></span>
        </div>

        <?php if (! empty($errorMessage)) : ?>
            <div class="alert alert-danger" style="margin-top:16px;">
                <?= esc($errorMessage) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger" style="margin-top:16px;">
                <?= esc((string) session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($selectedObjective)) : ?>
            <div class="dashboard-badges" style="margin-top:12px;">
                <span class="badge badge-green">Objectif actuel : <?= esc((string) ($selectedObjective['libelle'] ?? $selectedObjective['type_objectif_label'] ?? '')) ?></span>
            </div>
        <?php endif; ?>
    </article>

    <article class="dashboard-card dashboard-card-wide">
        <?php if ($step === 1) : ?>
            <form action="/objectifs/choose" method="post" id="objectif-step-one-form">
                <?= csrf_field() ?>
                <div class="table-responsive">
                    <table class="table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:12px 10px;border-bottom:1px solid var(--c-border);width:72px;">Type</th>
                            <th style="text-align:left;padding:12px 10px;border-bottom:1px solid var(--c-border);">Libellé de l'objectif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($objectifs)) : ?>
                            <tr>
                                <td colspan="2" style="padding:12px 10px;">Aucun objectif trouvé en base.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($objectifs as $objectif) : ?>
                                <tr>
                                    <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                        <input type="radio" name="id_type_objectif" value="<?= esc((string) $objectif['id']) ?>" data-label="<?= esc((string) $objectif['libelle']) ?>" required>
                                    </td>
                                    <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                        <strong><?= esc((string) $objectif['libelle']) ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>

                <div class="form-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:16px;">
                    <div>
                        <label for="poids_objectif" style="display:block;margin-bottom:6px;">Poids objectif (kg)</label>
                        <input type="number" step="0.01" min="0" id="poids_objectif" name="poids_objectif" value="<?= esc((string) $poidsObjectif) ?>" class="form-control" required>
                        <small id="poids_objectif_help" style="display:block;margin-top:6px;color:var(--c-muted);">Rempli automatiquement si tu choisis l'IMC idéal.</small>
                    </div>
                    <div>
                        <label for="date_debut" style="display:block;margin-bottom:6px;">Date de début</label>
                        <input type="date" id="date_debut" name="date_debut" value="<?= esc((string) $dateDebut) ?>" class="form-control" required>
                    </div>
                </div>

                <?php if (! empty($validationErrors)) : ?>
                    <div class="alert alert-danger" style="margin-top:16px;">
                        <?= esc(implode(' ', array_values($validationErrors))) ?>
                    </div>
                <?php endif; ?>

                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                    <button type="submit" class="btn btn-primary">Voir les régimes compatibles</button>
                </div>
            </form>
        <?php else : ?>
            <div class="dashboard-card" style="margin-bottom:16px;">
                <h3 style="margin-top:0;">Récapitulatif</h3>
                <p style="margin-bottom:6px;">Type d'objectif : <strong><?= esc((string) ($selectedObjective['libelle'] ?? '')) ?></strong></p>
                <p style="margin-bottom:6px;">Poids actuel : <strong><?= esc(number_format((float) $poidsActuel, 2, ',', ' ')) ?> kg</strong></p>
                <p style="margin-bottom:6px;">Poids objectif : <strong><?= esc(number_format((float) $poidsObjectif, 2, ',', ' ')) ?> kg</strong></p>
                <p style="margin-bottom:0;">Différence : <strong><?= esc(number_format((float) ($poidsObjectif - $poidsActuel), 2, ',', ' ')) ?> kg</strong></p>
            </div>

            <div class="table-responsive">
                <table class="table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:12px 10px;border-bottom:1px solid var(--c-border);">Régime</th>
                            <th style="text-align:left;padding:12px 10px;border-bottom:1px solid var(--c-border);">Durée totale</th>
                            <th style="text-align:left;padding:12px 10px;border-bottom:1px solid var(--c-border);">Prix total</th>
                            <th style="text-align:left;padding:12px 10px;border-bottom:1px solid var(--c-border);">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suggestions as $suggestion) : ?>
                            <tr>
                                <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                    <strong><?= esc((string) $suggestion['nom']) ?></strong><br>
                                    <small style="color:var(--c-muted);">
                                        <?= esc((string) $suggestion['duree']) ?> j de base · <?= esc(number_format((float) $suggestion['prix'], 2, ',', ' ')) ?> Ar/j
                                    </small>
                                </td>
                                <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                    <?= esc((string) $suggestion['duree_totale_calculee']) ?> jours
                                </td>
                                <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                    <?= esc(number_format((float) $suggestion['prix_total_calcule'], 2, ',', ' ')) ?> Ar
                                </td>
                                <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                    <form action="/objectifs/choose/save" method="post" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id_type_objectif" value="<?= esc((string) ($selectedObjective['id'] ?? '')) ?>">
                                        <input type="hidden" name="poids_objectif" value="<?= esc((string) $poidsObjectif) ?>">
                                        <input type="hidden" name="date_debut" value="<?= esc((string) $dateDebut) ?>">
                                        <input type="hidden" name="regime_id" value="<?= esc((string) $suggestion['regime_id']) ?>">
                                        <button type="submit" class="btn btn-primary">Valider ce régime</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </article>
</div>
<script>
(function () {
    const stepOneForm = document.getElementById('objectif-step-one-form');
    const poidsInput = document.getElementById('poids_objectif');
    const helpText = document.getElementById('poids_objectif_help');
    const idealWeight = <?= json_encode($poidsIdeal, JSON_UNESCAPED_UNICODE) ?>;

    if (!stepOneForm || !poidsInput) {
        return;
    }

    const objectiveRadios = stepOneForm.querySelectorAll('input[name="id_type_objectif"]');

    const isImcIdeal = (label) => {
        if (!label) {
            return false;
        }

        const normalized = label.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
        return normalized.includes('imc') && normalized.includes('ideal');
    };

    const syncWeightField = () => {
        const selected = stepOneForm.querySelector('input[name="id_type_objectif"]:checked');
        const label = selected ? selected.dataset.label || '' : '';

        if (isImcIdeal(label) && idealWeight !== null) {
            poidsInput.value = Number(idealWeight).toFixed(2);
            poidsInput.readOnly = true;
            if (helpText) {
                helpText.textContent = 'Calculé automatiquement à partir de l\'IMC idéal.';
            }
            return;
        }

        poidsInput.readOnly = false;
        if (helpText) {
            helpText.textContent = 'Rempli automatiquement si tu choisis l\'IMC idéal.';
        }
    };

    objectiveRadios.forEach((radio) => {
        radio.addEventListener('change', syncWeightField);
    });

    syncWeightField();
})();
</script>
<?= $this->endSection() ?>