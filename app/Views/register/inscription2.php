<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Inscription') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon">
                    <img src="/assets/logo/diet.png" alt="NutriStep logo">
                </div>
                <div>
                    <h1>NutriStep</h1>
                    <span>Inscription — étape 2/2</span>
                </div>
            </div>

            <h2>Informations supplémentaires</h2>
            <p class="subtitle">Renseignez vos données personnelles</p>

            <?php if (! empty($error)) : ?>
                <div class="alert alert-error"><?= esc($error) ?></div>
            <?php endif; ?>

            <form action="/register/save-inscription2" method="post">
                <div class="field-group">
                    <label>Prénom et nom</label>
                    <div class="input-wrap" style="background-color:#f0f0f0;pointer-events:none;">
                        <input type="text" value="<?= esc($nom ?? '') ?>" disabled style="background-color:#f0f0f0;">
                    </div>
                </div>

                <div class="field-group">
                    <label for="poids">Poids (kg)</label>
                    <div class="input-wrap">
                        <input type="number" id="poids" name="poids" step="0.1" placeholder="Ex : 72.5" value="<?= esc($poids ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label for="taille">Taille (cm)</label>
                    <div class="input-wrap">
                        <input type="number" id="taille" name="taille" placeholder="Ex : 175" value="<?= esc($taille ?? '') ?>" required>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:2rem">
                    <a href="/register/inscription1" class="btn btn-secondary btn-full">Précédent</a>
                    <button type="submit" class="btn btn-primary btn-full">Terminer</button>
                </div>
            </form>

            <div class="login-footer">
                Après validation, vous verrez votre IMC.
            </div>
        </div>
    </div>
</body>
</html>
