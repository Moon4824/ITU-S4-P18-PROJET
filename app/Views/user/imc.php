<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
    <?php
        $imc = $imc ?? 0.0;
        $nom = $nom ?? '';
        $email = $email ?? '';
        $genre = $genre ?? '';
        $poids = $poids ?? 0;
        $taille = $taille ?? 0;
        // Server provides only the raw IMC and user data; interpretation and colors
        // are loaded client-side from the API so the view stays decoupled.
        $initialObjective = 'equilibre';
    ?>
    <article class="dashboard-card dashboard-card-wide imc-card-shell">
        <div class="login-logo" style="margin-bottom:18px;">
            <div class="logo-icon">
                <img src="/assets/logo/diet.png" alt="NutriStep logo">
            </div>
            <div>
                <h1>NutriStep</h1>
                <span>IMC & objectifs</span>
            </div>
        </div>

        <h2>Votre IMC</h2>
        <p class="subtitle" id="bmi-hint">Analyse de votre profil</p>

        <div class="imc-summary">
            <div class="imc-value" id="bmi-value"><?= esc(number_format((float) $imc, 1, '.', '')) ?></div>
            <div class="imc-category imc-badge-normal" id="bmi-label">—</div>
        </div>

        <div class="imc-scale">
            <div class="imc-scale-track">
                <div class="imc-scale-fill imc-fill-normal" id="bmi-bar"></div>
            </div>
            <div class="imc-scale-labels" id="imc-scale-labels">
                <!-- populated client-side from API -->
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <a href="/register/inscription2" class="btn btn-secondary btn-full">Précédent</a>
            <button type="button" class="btn btn-primary btn-full" onclick="continueToRegimes()">Choisir mes objectifs</button>
        </div>

        <div style="margin-top:1rem;padding:1rem;border-radius:12px;background:var(--c-input);border:1px solid var(--c-border);">
            <div style="margin-bottom:.75rem;">
                <strong>Nom :</strong> <?= esc($nom ?? '') ?><br>
                <strong>Email :</strong> <?= esc($email ?? '') ?><br>
                <strong>Genre :</strong> <?= esc($genre ?? '') ?>
            </div>
            <div>
                <strong>Poids :</strong> <?= esc((string) $poids) ?> kg<br>
                <strong>Taille :</strong> <?= esc((string) $taille) ?> cm
            </div>
        </div>

        
    </article>

    <script>
        // Minimal payload for the page: IMC and user info. Interpretations loaded via API.
        window.__IMC_PAGE__ = <?= json_encode([
            'imc' => (float) $imc,
            'nom' => (string) $nom,
            'genre' => (string) $genre,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="/assets/js/imc.js" defer></script>
<?= $this->endSection() ?>