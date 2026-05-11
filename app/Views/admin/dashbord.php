<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$role = (string) ($user['role'] ?? '');
$stats = $stats ?? [];
$chartBars = $chartBars ?? [];
$recentActivity = $recentActivity ?? [];
$goldConfig = $stats['goldConfig'] ?? ['prix' => 0, 'remise_pct' => 0, 'actif' => 0];
$barMax = 1;

foreach ($chartBars as $bar) {
    $barMax = max($barMax, (int) ($bar['value'] ?? 0));
}
?>

<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <div class="breadcrumb">Votre espace de supervision</div>
    </div>
</div>

<div class="dashboard-grid">
    <article class="dashboard-card dashboard-card-wide">
        <h2>Bienvenue, <?= esc((string) ($user['name'] ?? 'Utilisateur')) ?></h2>
        <p>Vous êtes connecté en tant que <strong><?= esc($role) ?></strong>.</p>
        <div class="dashboard-badges">
            <span class="badge badge-blue">ID utilisateur: <?= esc((string) ($user['id'] ?? '-')) ?></span>
            <span class="badge badge-green">Rôle: <?= esc($role) ?></span>
        </div>
    </article>

    <article class="dashboard-card dashboard-card-wide">
        <div style="display:flex;justify-content:space-between;gap:16px;align-items:flex-start;flex-wrap:wrap;">
            <div>
                <h3>Histogramme des volumes</h3>
                <p>Comparaison rapide des indicateurs les plus importants du dashboard.</p>
            </div>
            <div style="display:flex;gap:12px;font-size:12px;color:var(--c-muted);flex-wrap:wrap;">
                <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;background:var(--c-primary);border-radius:2px;display:inline-block"></span>Volume</span>
                <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;background:rgba(148,163,184,.35);border-radius:2px;display:inline-block"></span>Référence</span>
            </div>
        </div>

        <div style="margin-top:18px;display:grid;grid-template-rows:1fr auto;gap:12px;">
            <div style="position:relative;height:280px;padding:18px 18px 10px;border-radius:20px;background:linear-gradient(180deg, rgba(15,23,42,.02), rgba(15,23,42,.06));border:1px solid rgba(148,163,184,.14);overflow:hidden;">
                <div style="position:absolute;left:18px;right:18px;bottom:46px;height:1px;background:rgba(148,163,184,.18);"></div>
                <div style="position:absolute;left:18px;right:18px;bottom:88px;height:1px;background:rgba(148,163,184,.10);"></div>
                <div style="position:absolute;left:18px;right:18px;bottom:130px;height:1px;background:rgba(148,163,184,.10);"></div>
                <div style="position:absolute;left:18px;right:18px;bottom:172px;height:1px;background:rgba(148,163,184,.10);"></div>
                <div style="position:absolute;left:18px;right:18px;bottom:214px;height:1px;background:rgba(148,163,184,.10);"></div>

                <div style="height:100%;display:flex;align-items:flex-end;justify-content:space-between;gap:14px;">
                    <?php foreach ($chartBars as $bar) : ?>
                        <?php
                            $barValue = (int) ($bar['value'] ?? 0);
                            $barHeight = $barMax > 0 ? max(14, (int) round(($barValue / $barMax) * 100)) : 14;
                            $barColor = (string) ($bar['color'] ?? 'var(--c-primary)');
                        ?>
                        <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;min-width:0;">
                            <div style="width:100%;max-width:72px;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;gap:8px;">
                                <span style="font-size:12px;font-weight:700;color:var(--c-muted);"> <?= esc((string) $barValue) ?> </span>
                                <div style="width:100%;height:<?= esc((string) $barHeight) ?>%;min-height:28px;border-radius:18px 18px 8px 8px;background:linear-gradient(180deg, <?= esc($barColor) ?>, rgba(255,255,255,.22));box-shadow:0 10px 20px rgba(15,23,42,.08);"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px;">
                <?php foreach ($chartBars as $bar) : ?>
                    <div style="text-align:center;font-size:12px;color:var(--c-muted);line-height:1.3;">
                        <strong style="display:block;color:var(--c-text);font-size:13px;"> <?= esc((string) ($bar['label'] ?? '')) ?> </strong>
                        <span><?= esc((string) ($bar['value'] ?? 0)) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>

    <article class="dashboard-card dashboard-card-wide">
        <h3>Activité récente</h3>
        <p>Les derniers événements utiles à montrer au professeur.</p>
        <div class="table-wrap" style="margin-top:12px;">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Utilisateur</th>
                        <th>Référence</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentActivity)) : ?>
                        <tr>
                            <td colspan="4" class="empty-row">Aucune activité récente.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($recentActivity as $activity) : ?>
                            <tr>
                                <td><?= esc((string) ($activity['type'] ?? '-')) ?></td>
                                <td><?= esc((string) ($activity['user'] ?? '-')) ?></td>
                                <td><?= esc((string) ($activity['reference'] ?? '-')) ?></td>
                                <td><?= esc((string) ($activity['date'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </article>

</div>
<?= $this->endSection() ?>