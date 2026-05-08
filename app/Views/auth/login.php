<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Connexion') ?></title>
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
                    <span>Chaque pas compte pour votre santé</span>
                </div>
            </div>

            <h2>Connexion</h2>
            <p class="subtitle">Connectez-vous à votre espace de travail</p>

            <?php if (! empty($adminName)) : ?>
                <div class="alert alert-info">Compte de démonstration chargé depuis la base: <?= esc((string) $adminName) ?></div>
            <?php endif; ?>

            <?php if (! empty($error)) : ?>
                <div class="alert alert-error"><?= esc($error) ?></div>
            <?php endif; ?>

            <form action="/auth/login" method="post">
                <div class="field-group">
                    <label for="email">Adresse e-mail</label>
                    <div class="input-wrap">
                        <input type="email" id="email" name="email" placeholder="vous@exemple.com" value="<?= esc($email ?? '') ?>" required>
                    </div>
                    <?php if (isset($validation) && $validation->getError('email')) : ?>
                        <small class="field-error"><?= esc((string) $validation->getError('email')) ?></small>
                    <?php endif; ?>
                </div>

                <div class="field-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <div class="input-wrap">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" value="<?= esc($password ?? '') ?>" required>
                    </div>
                    <?php if (isset($validation) && $validation->getError('mot_de_passe')) : ?>
                        <small class="field-error"><?= esc((string) $validation->getError('mot_de_passe')) ?></small>
                    <?php endif; ?>
                </div>

                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember" value="1">
                        Se souvenir de moi
                    </label>
                    <a href="#">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-full">Se connecter</button>
            </form>

            <div class="login-footer">
                Pas encore de compte ? <a href="/inscription1.html">S'inscrire</a>
            </div>
        </div>
    </div>
</body>
</html>
