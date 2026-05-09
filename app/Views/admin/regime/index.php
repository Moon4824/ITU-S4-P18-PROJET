<?= $this->extend('layouts/model') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h2>Liste des régimes</h2>
        <div class="breadcrumb">Accueil / Admin / <span>Régimes</span></div>
    </div>
    <a href="/admin/regime/create" class="btn btn-primary btn-sm">Nouveau régime</a>
</div>

<div class="alert alert-info">
    <span>Liste complète des régimes disponibles.</span>
</div>

<div class="dash-grid" style="grid-template-columns:repeat(2,1fr);gap:14px">
    <?php if (!empty($regimes)) : ?>
        <?php foreach ($regimes as $r) :
            $pctViande = (int) ($r['pct_viande'] ?? 0);
            $pctPoisson = (int) ($r['pct_poisson'] ?? 0);
            $pctVolaille = (int) ($r['pct_volaille'] ?? 0);

            $variation = $r['variation_poids'] ?? null;
            if ($variation !== null && is_numeric($variation)) {
                $variation = (float) $variation;
            }

            if ($variation === null) {
                $badgeText = '—';
                $badgeClass = 'badge-gray';
            } elseif ($variation < 0) {
                $badgeText = 'Déficit';
                $badgeClass = 'badge-green';
            } elseif ($variation == 0) {
                $badgeText = 'Stable';
                $badgeClass = 'badge-blue';
            } else {
                $badgeText = 'Gain';
                $badgeClass = 'badge-amber';
            }
        ?>
        <div class="regime-card">
            <div class="card-header"><div class="card-title"><?= esc((string) ($r['nom'] ?? 'Régime')) ?></div><span class="badge <?= esc($badgeClass) ?>"><?= esc($badgeText) ?></span></div>
            <p style="color:var(--c-muted)">Durée: <?= esc((string) ($r['duree'] ?? '—')) ?> — Prix: <?= esc((string) ($r['prix'] ?? '—')) ?>€ — Variation: <?= esc((string) ($r['variation_poids'] ?? '—')) ?>kg</p>

            <div class="nutrition-bars" style="margin-top:8px">
                <div class="bar-item viande" style="width:<?= esc((string) $pctViande) ?>%">&nbsp;</div>
                <div class="bar-item poisson" style="width:<?= esc((string) $pctPoisson) ?>%">&nbsp;</div>
                <div class="bar-item volaille" style="width:<?= esc((string) $pctVolaille) ?>%">&nbsp;</div>
            </div>

            <div style="margin-top:10px;display:flex;gap:8px">
                <a href="/admin/regime/edit/<?= esc((string) $r['id']) ?>" class="btn btn-ghost btn-sm">Modifier</a>
                <form action="/admin/regime/delete/<?= esc((string) $r['id']) ?>" method="post" style="display:inline" onsubmit="return confirm('Supprimer ce régime ?');">
                    <button class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="card">
            <div class="card-header"><div class="card-title">Aucun régime</div></div>
            <div class="card-body">Aucun régime trouvé dans la base de données.</div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
