<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<?php
    $objectifs = $objectifs ?? [];
    $selectedObjective = $selectedObjective ?? null;
?>
<div class="dashboard-grid">
    <article class="dashboard-card dashboard-card-wide">
        <h2>Choisissez votre objectif</h2>
        <p>Sélectionnez un objectif enregistré en base pour finaliser votre parcours.</p>
        <?php if (!empty($selectedObjective)) : ?>
            <div class="dashboard-badges" style="margin-top:12px;">
                <span class="badge badge-green">Objectif actuel : <?= esc($selectedObjective) ?></span>
            </div>
        <?php endif; ?>
    </article>

    <article class="dashboard-card dashboard-card-wide">
        <form action="/objectifs/choose" method="post">
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
                                    <input type="radio" name="id_type_objectif" value="<?= esc((string) $objectif['id']) ?>" required>
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

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Choisir cet objectif</button>
            </div>
        </form>
    </article>
</div>
<?= $this->endSection() ?>