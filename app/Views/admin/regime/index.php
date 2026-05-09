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

<div class="regime-grid">
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
                $badgeClass = 'badge-blue';
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
        <article class="regime-card">
            <!-- Left: Content -->
            <div class="card-content">
                <!-- Header: Title + Badge -->
                <div class="card-header">
                    <div class="card-title"><?= esc((string) ($r['nom'] ?? 'Régime')) ?></div>
                    <span class="badge <?= esc($badgeClass) ?>"><?= esc($badgeText) ?></span>
                </div>

                <!-- Body: Metadata -->
                <div class="card-body">
                    <div class="regime-meta">
                        <div class="meta-item"><strong>Durée:</strong> <?= esc((string) ($r['duree'] ?? '—')) ?> jours</div>
                        <div class="meta-item"><strong>Prix:</strong> <?= esc((string) ($r['prix'] ?? '—')) ?> Ar / jour</div>
                        <div class="meta-item"><strong>Variation:</strong> <?= esc((string) ($r['variation_poids'] ?? '—')) ?> kg</div>
                    </div>

                <!-- Nutrition Composition Bar -->
                <div class="nutrition-composition">
                    <div class="composition-bars">
                        <div class="bar-segment viande" style="flex: <?= esc((string) $pctViande) ?>"></div>
                        <div class="bar-segment poisson" style="flex: <?= esc((string) $pctPoisson) ?>"></div>
                        <div class="bar-segment volaille" style="flex: <?= esc((string) $pctVolaille) ?>"></div>
                    </div>
                    <div class="composition-labels">
                        <div class="label-item">
                            <span class="label-color viande"></span>
                            <span class="label-text">Viande <?= esc((string) $pctViande) ?>%</span>
                        </div>
                        <div class="label-item">
                            <span class="label-color poisson"></span>
                            <span class="label-text">Poisson <?= esc((string) $pctPoisson) ?>%</span>
                        </div>
                        <div class="label-item">
                            <span class="label-color volaille"></span>
                            <span class="label-text">Volaille <?= esc((string) $pctVolaille) ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Right: Actions -->
            <div class="card-actions">
                <a href="/admin/regime/edit/<?= esc((string) $r['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                <form action="/admin/regime/delete/<?= esc((string) $r['id']) ?>" method="post" style="display:inline" onsubmit="return confirm('Supprimer ce régime ?');">
                    <?= csrf_field() ?>
                    <button class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </div>
        </article>
        <?php endforeach; ?>
    <?php else : ?>
        <article class="regime-card regime-card-empty">
            <div class="card-header"><div class="card-title">Aucun régime</div></div>
            <div class="card-body">Aucun régime trouvé dans la base de données.</div>
        </article>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
