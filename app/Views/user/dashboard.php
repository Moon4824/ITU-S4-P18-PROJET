<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php $role = (string) ($user['role'] ?? ''); ?>
<?php
$objectiveExists = ! empty($objective);
$objectiveStartWeight = $objectiveExists ? (float) ($objective['poids_initial'] ?? 0) : 0.0;
$objectiveTargetWeight = $objectiveExists && isset($objectiveWeight) ? (float) $objectiveWeight : 0.0;
$objectiveDurationDays = max(1, (int) ($objectiveDuration ?? 0));
$chartWeightMin = min($objectiveStartWeight, $objectiveTargetWeight);
$chartWeightMax = max($objectiveStartWeight, $objectiveTargetWeight);
$chartPadding = max(1.0, abs($objectiveTargetWeight - $objectiveStartWeight) * 0.25);
$chartMin = max(0.0, $chartWeightMin - $chartPadding);
$chartMax = $chartWeightMax + $chartPadding;

if ($chartMax <= $chartMin) {
    $chartMax = $chartMin + 1.0;
}

$yScale = static function (float $weight) use ($chartMin, $chartMax): float {
    $top = 40.0;
    $bottom = 210.0;
    $ratio = ($weight - $chartMin) / max(0.0001, ($chartMax - $chartMin));

    return $bottom - ($ratio * ($bottom - $top));
};

$startX = 80.0;
$endX = 820.0;
$midX = ($startX + $endX) / 2;
$startY = $yScale($objectiveStartWeight);
$targetY = $yScale($objectiveTargetWeight);
$midWeight = ($objectiveStartWeight + $objectiveTargetWeight) / 2;
$midY = $yScale($midWeight);
$chartTicks = [];

for ($index = 0; $index <= 4; $index++) {
    $ratio = $index / 4;
    $weight = $chartMax - ($ratio * ($chartMax - $chartMin));
    $chartTicks[] = [
        'weight' => round($weight, 0),
        'y' => 40 + ($ratio * 170),
    ];
}

$imcValue = isset($imc) && $imc > 0 ? number_format((float) $imc, 2, ',', ' ') : 'Non renseigné';
$imcLabel = (string) ($imcInterpretation['libelle'] ?? 'Non renseigné');
$objectiveWeightValue = isset($objectiveWeight) && $objectiveWeight !== null ? number_format((float) $objectiveWeight, 2, ',', ' ') . ' kg' : 'Non renseigné';
$objectiveTypeValue = (string) ($objectiveType ?? '');
$objectiveSportValue = (string) ($objectiveSport ?? '');
$objectiveDelta = $objectiveTypeValue !== '' ? 'Type : ' . $objectiveTypeValue : 'Aucun objectif enregistré';
$userName = (string) ($user['name'] ?? 'Utilisateur');
$initials = substr($userName, 0, 2);

$objectiveStartDateFormatted = '';
if (! empty($objectiveStartDate)) {
    try {
        $objectiveStartDateFormatted = (new DateTimeImmutable($objectiveStartDate))->format('d/m/Y');
    } catch (Throwable) {
        $objectiveStartDateFormatted = $objectiveStartDate;
    }
}

$objectiveTargetDateFormatted = '';
if (! empty($objectiveTargetDate)) {
    try {
        $objectiveTargetDateFormatted = (new DateTimeImmutable((string) $objectiveTargetDate))->format('d/m/Y');
    } catch (Throwable) {
        $objectiveTargetDateFormatted = (string) $objectiveTargetDate;
    }
}
?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2>Tableau de bord</h2>
        <div class="breadcrumb">Accueil / <span>Tableau de bord</span></div>
    </div>
    <div style="display:flex;gap:10px">
        <a href="<?= base_url('user/imc') ?>" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:currentColor;fill:none"><circle cx="12" cy="12" r="10"/><path d="M12 6v6m0 0l3-3m-3 3l-3-3"/></svg>
            Calculer IMC
        </a>
    </div>
</div>

<!-- Dashboard Grid (2 colonnes) -->
<div class="dash-grid">
    <div class="card">
        <div style="display:flex;gap:20px;align-items:center;flex-wrap:wrap">
            <div class="avatar" style="width:88px;height:88px;font-size:28px;background:var(--c-primary);color:#fff;display:flex;align-items:center;justify-content:center"><?= esc($initials) ?></div>
            <div style="flex:1;min-width:240px">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px">
                    <h3 style="font-size:22px">Bienvenue, <?= esc($userName) ?></h3>
                    <span class="badge badge-blue">Utilisateur</span>
                    <span class="badge badge-green">Actif</span>
                </div>
                <p style="color:var(--c-muted);line-height:1.6;max-width:720px">
                    Vous êtes connecté en tant que <strong><?= esc($role) ?></strong>. 
                    Consultez votre profil, suivez vos objectifs et gérez votre programme nutritionnel.
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <?php if ($objectiveExists) : ?>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
                <div>
                    <div class="card-title">Objectif en cours</div>
                    <div style="color:var(--c-muted);font-size:13px;margin-top:6px">Progression estimée du programme</div>
                </div>
                <span class="badge badge-green"><?= esc($objectiveTypeValue ?: 'Objectif actif') ?></span>
            </div>

            <div style="margin-top:18px;padding:16px;border:1px solid var(--c-border);border-radius:16px;background:linear-gradient(180deg, rgba(255,255,255,0.85), rgba(248,250,252,0.95));">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;font-size:13px;color:var(--c-muted);margin-bottom:14px">
                    <div><strong style="display:block;color:var(--c-text);margin-bottom:4px">Sport</strong><?= esc($objectiveSportValue ?: 'Non renseigné') ?></div>
                    <div><strong style="display:block;color:var(--c-text);margin-bottom:4px">Début</strong><?= esc($objectiveStartDateFormatted ?: 'Non renseigné') ?></div>
                    <div><strong style="display:block;color:var(--c-text);margin-bottom:4px">Cible</strong><?= esc($objectiveTargetDateFormatted ?: 'Non renseigné') ?></div>
                </div>

                <div style="position:relative;height:280px;padding:6px 4px 0;">
                    <svg viewBox="0 0 900 280" preserveAspectRatio="none" style="width:100%;height:100%;overflow:visible">
                        <defs>
                            <linearGradient id="goalLine" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#ff6b4a" />
                                <stop offset="100%" stop-color="#84cc16" />
                            </linearGradient>
                        </defs>

                        <line x1="80" y1="40" x2="80" y2="240" stroke="var(--c-border)" stroke-width="2" />
                        <line x1="80" y1="240" x2="840" y2="240" stroke="var(--c-border)" stroke-width="2" />

                        <?php foreach ($chartTicks as $tick) : ?>
                            <line x1="74" y1="<?= esc(number_format((float) $tick['y'], 1, '.', '')) ?>" x2="80" y2="<?= esc(number_format((float) $tick['y'], 1, '.', '')) ?>" stroke="var(--c-border)" stroke-width="2" />
                            <text x="66" y="<?= esc(number_format((float) ($tick['y'] + 4), 1, '.', '')) ?>" text-anchor="end" font-size="12" fill="var(--c-muted)"><?= esc((string) $tick['weight']) ?> kg</text>
                            <line x1="80" y1="<?= esc(number_format((float) $tick['y'], 1, '.', '')) ?>" x2="840" y2="<?= esc(number_format((float) $tick['y'], 1, '.', '')) ?>" stroke="rgba(148,163,184,0.12)" stroke-width="1" stroke-dasharray="4 6" />
                        <?php endforeach; ?>

                        <path d="M 80 <?= esc(number_format($startY, 1, '.', '')) ?> C 260 <?= esc(number_format(($startY + $midY) / 2, 1, '.', '')) ?>, 420 <?= esc(number_format(($startY + $midY) / 2, 1, '.', '')) ?>, 500 <?= esc(number_format($midY, 1, '.', '')) ?> S 700 <?= esc(number_format(($midY + $targetY) / 2, 1, '.', '')) ?>, 840 <?= esc(number_format($targetY, 1, '.', '')) ?>" stroke="url(#goalLine)" stroke-width="10" stroke-linecap="round" fill="none" />

                        <circle cx="80" cy="<?= esc(number_format($startY, 1, '.', '')) ?>" r="12" fill="#fff" stroke="#ff6b4a" stroke-width="7" />
                        <circle cx="840" cy="<?= esc(number_format($targetY, 1, '.', '')) ?>" r="12" fill="#fff" stroke="#84cc16" stroke-width="7" />

                        <text x="80" y="262" text-anchor="middle" font-size="12" fill="var(--c-muted)">J0</text>
                        <text x="500" y="262" text-anchor="middle" font-size="12" fill="var(--c-muted)">J+<?= esc((string) max(1, (int) floor($objectiveDurationDays / 2))) ?></text>
                        <text x="840" y="262" text-anchor="middle" font-size="12" fill="var(--c-muted)">J+<?= esc((string) $objectiveDurationDays) ?></text>

                        <text x="80" y="<?= esc(number_format(max(24, $startY - 16), 1, '.', '')) ?>" text-anchor="middle" font-size="12" fill="#ff6b4a" font-weight="700"><?= esc(number_format($objectiveStartWeight, 2, ',', ' ')) ?> kg</text>
                        <text x="840" y="<?= esc(number_format(max(24, $targetY - 16), 1, '.', '')) ?>" text-anchor="middle" font-size="12" fill="#84cc16" font-weight="700"><?= esc(number_format($objectiveTargetWeight, 2, ',', ' ')) ?> kg</text>
                    </svg>
                </div>
            </div>

            <div style="margin-top:16px;display:grid;grid-template-columns:repeat(2,1fr);gap:12px;font-size:13px">
                <div style="padding:12px 14px;border:1px solid var(--c-border);border-radius:12px;background:#fff">
                    <div style="color:var(--c-muted);margin-bottom:4px">Début</div>
                    <strong><?= esc($objectiveStartDateFormatted) ?></strong>
                </div>
                <div style="padding:12px 14px;border:1px solid var(--c-border);border-radius:12px;background:#fff">
                    <div style="color:var(--c-muted);margin-bottom:4px">Poids cible</div>
                    <strong><?= esc(number_format((float) $objectiveTargetWeight, 2, ',', ' ')) ?> kg</strong>
                </div>
                <div style="padding:12px 14px;border:1px solid var(--c-border);border-radius:12px;background:#fff">
                    <div style="color:var(--c-muted);margin-bottom:4px">Sport</div>
                    <strong><?= esc($objectiveSportValue) ?></strong>
                </div>
                <div style="padding:12px 14px;border:1px solid var(--c-border);border-radius:12px;background:#fff">
                    <div style="color:var(--c-muted);margin-bottom:4px">Durée</div>
                    <strong><?= esc((string) $objectiveDurationDays) ?> jours</strong>
                </div>
            </div>
        <?php else : ?>
            <div class="card-title">Actions rapides</div>
            <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px">
                <a href="<?= base_url('user/profile') ?>" class="btn btn-primary btn-full">Voir mon profil</a>
                <a href="<?= base_url('objectifs/choose') ?>" class="btn btn-ghost btn-full">Choisir mon objectif</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modules utilisateur -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Modules disponibles</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:16px">
        <a href="<?= base_url('user/imc') ?>" style="padding:16px;border:1.5px solid var(--c-border);border-radius:8px;text-decoration:none;color:var(--c-text);transition:all 0.2s;text-align:center">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:currentColor;fill:none;margin:0 auto 8px"><path d="M12 2l3 7h7l-5.5 4.2L18.5 21 12 16.8 5.5 21l2-7.8L2 9h7z"/></svg>
            <div style="font-weight:600;font-size:14px">Calcul IMC</div>
            <div style="font-size:12px;color:var(--c-muted)">Suivi santé</div>
        </a>
        <a href="<?= base_url('user/objectifs') ?>" style="padding:16px;border:1.5px solid var(--c-border);border-radius:8px;text-decoration:none;color:var(--c-text);transition:all 0.2s;text-align:center">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:currentColor;fill:none;margin:0 auto 8px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div style="font-weight:600;font-size:14px">Objectifs</div>
            <div style="font-size:12px;color:var(--c-muted)">Vos cibles</div>
        </a>
        <a href="<?= base_url('user/profile') ?>" style="padding:16px;border:1.5px solid var(--c-border);border-radius:8px;text-decoration:none;color:var(--c-text);transition:all 0.2s;text-align:center">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:currentColor;fill:none;margin:0 auto 8px"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>
            <div style="font-weight:600;font-size:14px">Profil</div>
            <div style="font-size:12px;color:var(--c-muted)">Mes infos</div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>