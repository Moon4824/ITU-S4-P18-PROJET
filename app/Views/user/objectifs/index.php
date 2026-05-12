<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php
    $objectifs = $objectifs ?? [];
    $selectedObjective = $selectedObjective ?? null;
    $suggestions = $suggestions ?? [];
    $sports = $sports ?? [];
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
    $selectedSportId = (int) ($old['sport_id'] ?? 0);
    $selectedRegimeId = (int) ($selectedRegimeId ?? ($old['regime_id'] ?? 0));
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
                        <input type="number" step="0.01" min="0" id="poids_objectif" name="poids_objectif" value="" class="form-control" placeholder="<?= esc((string) $poidsObjectif) ?>" required>
                        <small id="poids_objectif_help" style="display:block;margin-top:6px;color:var(--c-muted);">Rempli automatiquement si tu choisis l'IMC idéal.</small>
                        <small id="poids_objectif_error" style="display:none;margin-top:6px;color:var(--c-danger);"></small>
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
            <form id="objectif-detail-form" action="/objectifs/choose/save" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_type_objectif" value="<?= esc((string) ($selectedObjective['id'] ?? '')) ?>">
                <input type="hidden" name="poids_objectif" value="<?= esc((string) $poidsObjectif) ?>">
                <input type="hidden" name="date_debut" value="<?= esc((string) $dateDebut) ?>">
                <input type="hidden" name="regime_id" id="detail-regime-id" value="">

                <div class="dashboard-card" style="margin-bottom:16px;">
                    <h3 style="margin-top:0;">Récapitulatif</h3>
                    <p style="margin-bottom:6px;">Type d'objectif : <strong><?= esc((string) ($selectedObjective['libelle'] ?? '')) ?></strong></p>
                    <p style="margin-bottom:6px;">Poids actuel : <strong><?= esc(number_format((float) $poidsActuel, 2, ',', ' ')) ?> kg</strong></p>
                    <p style="margin-bottom:6px;">Poids objectif : <strong><?= esc(number_format((float) $poidsObjectif, 2, ',', ' ')) ?> kg</strong></p>
                    <p style="margin-bottom:0;">Différence : <strong><?= esc(number_format((float) ($poidsObjectif - $poidsActuel), 2, ',', ' ')) ?> kg</strong></p>

                    <fieldset class="form-field" style="border:1px solid var(--c-border);padding:12px;border-radius:8px;background:var(--c-input);margin-top:12px;">
                        <legend style="font-weight:600;padding:0 4px;">Sport choisi <span style="color:var(--c-danger);margin-left:6px;">*</span></legend>

                        <div id="sport-radio-group" role="radiogroup" aria-required="true" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                            <?php foreach ($sports as $i => $sport) : ?>
                                <label class="radio-item" style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;border:1px solid transparent;cursor:pointer;background:var(--c-surface);">
                                    <input type="radio" name="sport_id" form="objectif-detail-form" value="<?= esc((string) $sport['id']) ?>" <?= $selectedSportId === (int) $sport['id'] ? 'checked' : '' ?> <?= $i === 0 ? 'required' : '' ?> style="accent-color:var(--c-primary);">
                                    <span style="font-size:0.95rem;"><?= esc((string) $sport['nom']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div id="sport-error" style="color:var(--c-danger);margin-top:6px;display:none;">Veuillez choisir un sport pour voir le détail.</div>
                    </fieldset>
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
                            <?php if (empty($suggestions)) : ?>
                                <tr>
                                    <td colspan="4" style="padding:12px 10px;">Aucun régime compatible n'a été trouvé.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($suggestions as $suggestion) : ?>
                                    <tr>
                                        <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                            <strong><?= esc((string) $suggestion['nom']) ?></strong><br>
                                            <small style="color:var(--c-muted);">
                                                <?= esc((string) $suggestion['duree']) ?> j de base · <?= esc(number_format((float) $suggestion['prix'], 2, ',', ' ')) ?> Ar / j
                                            </small>
                                        </td>
                                        <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                            <?= esc((string) $suggestion['duree_totale_calculee']) ?> jours
                                        </td>
                                        <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                            <div style="display:flex;align-items:center;gap:6px;">
                                                <span style="<?= ((int) ($user['est_gold'] ?? 0) === 1) ? 'text-decoration:line-through;color:var(--c-muted);' : '' ?>">
                                                    <?= esc(number_format((float) $suggestion['prix_total_calcule'], 2, ',', ' ')) ?> Ar
                                                </span>
                                                <?php if ((int) ($user['est_gold'] ?? 0) === 1) : ?>
                                                    <div style="display:flex;align-items:center;gap:4px;background:#e8f5e9;padding:2px 6px;border-radius:4px;font-size:0.85rem;">
                                                        <svg style="width:14px;height:14px;color:#51cf66;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z"/></svg>
                                                        <strong style="color:#51cf66;">
                                                            <?php 
                                                                $origPrice = (float) ($suggestion['prix_total_original'] ?? $suggestion['prix_total_calcule']);
                                                                $displayPrice = (float) $suggestion['prix_total_calcule'];
                                                                echo esc(number_format($displayPrice, 2, ',', ' ')) . ' Ar';
                                                            ?>
                                                        </strong>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td style="padding:12px 10px;border-bottom:1px solid var(--c-border);vertical-align:middle;">
                                            <button
                                                type="button"
                                                class="btn btn-primary btn-sm"
                                                data-objective-detail-trigger
                                                data-regime-id="<?= esc((string) $suggestion['regime_id']) ?>"
                                                data-regime-name="<?= esc((string) $suggestion['nom']) ?>"
                                                data-regime-duree="<?= esc((string) $suggestion['duree']) ?>"
                                                data-regime-prix="<?= esc(number_format((float) $suggestion['prix'], 2, '.', '')) ?>"
                                                data-regime-total-duree="<?= esc((string) $suggestion['duree_totale_calculee']) ?>"
                                                data-regime-total-prix="<?= esc(number_format((float) $suggestion['prix_total_calcule'], 2, '.', '')) ?>"
                                                data-regime-pct-viande="<?= esc((string) ($suggestion['pct_viande'] ?? 0)) ?>"
                                                data-regime-pct-poisson="<?= esc((string) ($suggestion['pct_poisson'] ?? 0)) ?>"
                                                data-regime-pct-volaille="<?= esc((string) ($suggestion['pct_volaille'] ?? 0)) ?>"
                                                data-regime-variation="<?= esc(number_format((float) ($suggestion['variation_poids'] ?? 0), 2, '.', '')) ?>"
                                            >
                                                Voir détail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="modal-overlay" id="objective-detail-modal" aria-hidden="true">
                <div class="modal" style="max-width:760px;width:min(760px,calc(100vw - 32px));">
                    <div class="modal-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2l8 4.5v11L12 22l-8-4.5v-11L12 2Z"/><path d="M12 7v5"/><circle cx="12" cy="16" r="1"/></svg>
                    </div>
                    <h3>Détail du régime</h3>
                    <p style="margin-bottom:18px;">Vérifie le détail, puis valide ou exporte le PDF.</p>

                    <div class="dashboard-card" style="margin-bottom:16px;">
                        <h4 style="margin-top:0;">Récapitulatif objectif</h4>
                        <p style="margin-bottom:6px;">Objectif : <strong><?= esc((string) ($selectedObjective['libelle'] ?? '')) ?></strong></p>
                        <p style="margin-bottom:6px;">Poids initial : <strong><?= esc(number_format((float) $poidsActuel, 2, ',', ' ')) ?> kg</strong></p>
                        <p style="margin-bottom:6px;">Poids cible : <strong><?= esc(number_format((float) $poidsObjectif, 2, ',', ' ')) ?> kg</strong></p>
                        <p style="margin-bottom:0;">Date de début : <strong><?= esc((string) $dateDebut) ?></strong></p>
                    </div>

                    <div class="dashboard-card" style="margin-bottom:16px;">
                        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
                            <div>
                                <div style="color:var(--c-muted);font-size:.92rem;">Régime sélectionné</div>
                                <div id="detail-regime-name" style="font-weight:700;margin-top:4px;">-</div>
                            </div>
                            <div>
                                <div style="color:var(--c-muted);font-size:.92rem;">Variation de poids</div>
                                <div id="detail-regime-variation" style="font-weight:700;margin-top:4px;">-</div>
                            </div>
                            <div>
                                <div style="color:var(--c-muted);font-size:.92rem;">Durée de base</div>
                                <div id="detail-regime-duree" style="font-weight:700;margin-top:4px;">-</div>
                            </div>
                            <div>
                                <div style="color:var(--c-muted);font-size:.92rem;">Durée totale calculée</div>
                                <div id="detail-regime-total-duree" style="font-weight:700;margin-top:4px;">-</div>
                            </div>
                            <div>
                                <div style="color:var(--c-muted);font-size:.92rem;">Prix journalier</div>
                                <div id="detail-regime-prix" style="font-weight:700;margin-top:4px;">-</div>
                            </div>
                            <div>
                                <div style="color:var(--c-muted);font-size:.92rem;">Prix total</div>
                                <div id="detail-regime-total-prix" style="font-weight:700;margin-top:4px;">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-card" style="margin-bottom:16px;">
                        <h4 style="margin-top:0;">Répartition du régime</h4>
                        <div id="detail-regime-percentages" class="dashboard-badges" style="gap:8px;flex-wrap:wrap;"></div>
                    </div>

                    <?php if ((int) ($user['est_gold'] ?? 0) === 1) : ?>
                        <div class="dashboard-card" style="margin-bottom:16px;background:linear-gradient(135deg,rgba(81,207,102,.1),rgba(76,175,80,.05));border:1px solid #a5d6a7;border-left:4px solid #51cf66;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <svg style="width:24px;height:24px;color:#51cf66;flex-shrink:0;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z"/></svg>
                                <div>
                                    <strong style="color:#51cf66;display:block;margin-bottom:2px;">Remise GOLD appliquée</strong>
                                    <small style="color:var(--c-muted);">Vous bénéficiez d'une réduction exclusive sur ce régime.</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Sport selection moved to the recap area; modal no longer contains a selector -->

                    <div class="modal-actions" style="justify-content:space-between;flex-wrap:wrap;gap:10px;">
                        <button type="button" class="btn btn-ghost" id="objective-detail-close">Fermer</button>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;">
                            <button type="submit" class="btn btn-ghost" form="objectif-detail-form" formaction="/objectifs/choose/pdf" formmethod="post">Exporter PDF</button>
                            <button type="submit" class="btn btn-primary" form="objectif-detail-form" formmethod="post">Valider ce choix</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </article>
</div>
<script>
(function () {
    const stepOneForm = document.getElementById('objectif-step-one-form');
    const poidsInput = document.getElementById('poids_objectif');
    const helpText = document.getElementById('poids_objectif_help');
    const poidsErrorMsg = document.getElementById('poids_objectif_error');
    const poidsActuel = <?= json_encode((float) $poidsActuel, JSON_UNESCAPED_UNICODE) ?>;
    const idealWeight = <?= json_encode($poidsIdeal, JSON_UNESCAPED_UNICODE) ?>;
    const detailModal = document.getElementById('objective-detail-modal');
    const detailClose = document.getElementById('objective-detail-close');
    const detailRegimeId = document.getElementById('detail-regime-id');
    const detailRegimeName = document.getElementById('detail-regime-name');
    const detailRegimeVariation = document.getElementById('detail-regime-variation');
    const detailRegimeDuree = document.getElementById('detail-regime-duree');
    const detailRegimeTotalDuree = document.getElementById('detail-regime-total-duree');
    const detailRegimePrix = document.getElementById('detail-regime-prix');
    const detailRegimeTotalPrix = document.getElementById('detail-regime-total-prix');
    const detailRegimePercentages = document.getElementById('detail-regime-percentages');
    const detailTriggers = document.querySelectorAll('[data-objective-detail-trigger]');
    const sportRadios = document.querySelectorAll('input[name="sport_id"]');
    const sportError = document.getElementById('sport-error');

    const objectiveRadios = stepOneForm ? stepOneForm.querySelectorAll('input[name="id_type_objectif"]') : [];

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
            if (poidsErrorMsg) {
                poidsErrorMsg.style.display = 'none';
            }
            return;
        }

        poidsInput.readOnly = false;
        if (helpText) {
            helpText.textContent = 'Rempli automatiquement si tu choisis l\'IMC idéal.';
        }
        validatePoidsObjectif();
    };

    const validatePoidsObjectif = () => {
        if (!stepOneForm || !poidsInput || !poidsErrorMsg) return;
        
        const selected = stepOneForm.querySelector('input[name="id_type_objectif"]:checked');
        if (!selected) {
            poidsErrorMsg.style.display = 'none';
            return;
        }

        const label = selected.dataset.label || '';
        const normalizedLabel = label.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
        const isAugmenter = normalizedLabel.includes('augmenter');
        const isDiminuer = normalizedLabel.includes('reduire') || normalizedLabel.includes('diminuer');
        const isImc = normalizedLabel.includes('imc') && normalizedLabel.includes('ideal');

        if (isImc) {
            poidsErrorMsg.style.display = 'none';
            return;
        }

        const poidsValue = poidsInput.value ? parseFloat(poidsInput.value) : null;

        if (poidsValue === null || poidsValue === '') {
            poidsErrorMsg.style.display = 'none';
            return;
        }

        let hasError = false;
        let errorText = '';

        if (isAugmenter && poidsValue <= poidsActuel) {
            hasError = true;
            errorText = `Le poids doit être supérieur à ${poidsActuel} kg`;
        } else if (isDiminuer && poidsValue >= poidsActuel) {
            hasError = true;
            errorText = `Le poids doit être inférieur à ${poidsActuel} kg`;
        }

        if (hasError) {
            poidsErrorMsg.textContent = errorText;
            poidsErrorMsg.style.display = 'block';
            poidsInput.classList.add('is-invalid');
        } else {
            poidsErrorMsg.style.display = 'none';
            poidsInput.classList.remove('is-invalid');
        }
    };

    if (stepOneForm && poidsInput) {
        objectiveRadios.forEach((radio) => {
            radio.addEventListener('change', syncWeightField);
        });

        poidsInput.addEventListener('input', validatePoidsObjectif);
        poidsInput.addEventListener('change', validatePoidsObjectif);

        syncWeightField();
    }

    const currency = (value) => Number(value || 0).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Ar';
    const percentBadge = (label, value) => `<span class="badge badge-blue">${label} : ${value}%</span>`;

    const openDetailModal = (trigger) => {
        if (!detailModal || !detailRegimeId) {
            return;
        }

        const selectedSport = document.querySelector('input[name="sport_id"]:checked');
        if (!selectedSport) {
            if (sportError) {
                sportError.style.display = 'block';
            }
            const firstRadio = document.querySelector('input[name="sport_id"]');
            if (firstRadio) firstRadio.focus();
            return;
        }
        if (sportError) {
            sportError.style.display = 'none';
        }

        detailRegimeId.value = trigger.dataset.regimeId || '';
        if (detailRegimeName) {
            detailRegimeName.textContent = trigger.dataset.regimeName || '-';
        }
        if (detailRegimeVariation) {
            detailRegimeVariation.textContent = `${trigger.dataset.regimeVariation || '0'} kg`;
        }
        if (detailRegimeDuree) {
            detailRegimeDuree.textContent = `${trigger.dataset.regimeDuree || '0'} jours`;
        }
        if (detailRegimeTotalDuree) {
            detailRegimeTotalDuree.textContent = `${trigger.dataset.regimeTotalDuree || '0'} jours`;
        }
        if (detailRegimePrix) {
            detailRegimePrix.textContent = currency(trigger.dataset.regimePrix);
        }
        if (detailRegimeTotalPrix) {
            detailRegimeTotalPrix.textContent = currency(trigger.dataset.regimeTotalPrix);
        }
        if (detailRegimePercentages) {
            detailRegimePercentages.innerHTML = [
                percentBadge('Viande', trigger.dataset.regimePctViande || '0'),
                percentBadge('Poisson', trigger.dataset.regimePctPoisson || '0'),
                percentBadge('Volaille', trigger.dataset.regimePctVolaille || '0'),
            ].join('');
        }

        detailModal.classList.add('open');
        detailModal.setAttribute('aria-hidden', 'false');
    };

    const closeDetailModal = () => {
        if (!detailModal) {
            return;
        }

        detailModal.classList.remove('open');
        detailModal.setAttribute('aria-hidden', 'true');
    };

    detailTriggers.forEach((trigger) => {
        trigger.addEventListener('click', function () {
            openDetailModal(this);
        });
    });

    // hide error when user picks a sport and mark selected label
    sportRadios.forEach((r) => {
        r.addEventListener('change', function () {
            if (sportError) sportError.style.display = 'none';
            // remove selected class from all labels
            document.querySelectorAll('.radio-item').forEach((lab) => lab.style.borderColor = 'transparent');
            const lab = this.closest('.radio-item');
            if (lab) {
                lab.style.borderColor = 'var(--c-primary)';
            }
        });
        // initialize selected visuals
        if (r.checked) {
            const lab = r.closest('.radio-item');
            if (lab) lab.style.borderColor = 'var(--c-primary)';
        }
    });

    if (detailClose) {
        detailClose.addEventListener('click', closeDetailModal);
    }

    const autoOpenRegimeId = <?= json_encode((int) ($selectedRegimeId ?? 0)) ?>;
    if (autoOpenRegimeId > 0) {
        const autoTrigger = Array.from(detailTriggers).find((trigger) => Number(trigger.dataset.regimeId || 0) === autoOpenRegimeId);
        if (autoTrigger) {
            openDetailModal(autoTrigger);
        }
    }

    if (detailModal) {
        detailModal.addEventListener('click', function (event) {
            if (event.target === this) {
                closeDetailModal();
            }
        });
    }
})();
</script>
<?= $this->endSection() ?>