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
                    <div class="input-wrap" style="position:relative;">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" value="<?= esc($password ?? '') ?>" required style="padding-right:3rem;">
                        <button type="button" class="toggle-password" aria-label="Afficher le mot de passe" title="Afficher le mot de passe" style="position:absolute;right:0.6rem;top:50%;transform:translateY(-50%);background:none;border:none;padding:0;line-height:0;cursor:pointer;color:inherit;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
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
                Pas encore de compte ? <a href="/register/inscription1">S'inscrire</a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.querySelector('.toggle-password');
        var input = document.getElementById('mot_de_passe');
        if (! toggle || ! input) return;

        var eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        var eyeOffSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a19.92 19.92 0 0 1 4.06-5.94"></path><path d="M1 1l22 22"></path></svg>';

        toggle.addEventListener('click', function () {
            if (input.type === 'password') {
                input.type = 'text';
                toggle.innerHTML = eyeOffSvg;
                toggle.setAttribute('aria-label', 'Masquer le mot de passe');
            } else {
                input.type = 'password';
                toggle.innerHTML = eyeSvg;
                toggle.setAttribute('aria-label', 'Afficher le mot de passe');
            }
        });
    });
    </script>
</body>
</html>
