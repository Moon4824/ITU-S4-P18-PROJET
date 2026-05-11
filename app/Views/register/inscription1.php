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
                    <span>Inscription — étape 1/2</span>
                </div>
            </div>

            <h2>Créer un compte</h2>
            <p class="subtitle">Renseignez vos informations de connexion</p>

            <?php if (! empty($error)) : ?>
                <div class="alert alert-error"><?= esc($error) ?></div>
            <?php endif; ?>

            <?php if (isset($validation) && $validation !== null) : ?>
                <div class="alert alert-error" style="margin-top:0.75rem;">
                    <?php foreach ($validation->getErrors() as $message) : ?>
                        <?php $errorText = is_array($message) ? implode(' ', array_map('strval', $message)) : (string) $message; ?>
                        <div><?= esc($errorText) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/register/save-inscription1" method="post">
                <div class="field-group">
                    <label for="nom">Nom complet</label>
                    <div class="input-wrap">
                        <input type="text" id="nom" name="nom" placeholder="Ex : Andry Rakoto" value="<?= esc($nom ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label for="email">Adresse e-mail</label>
                    <div class="input-wrap">
                        <input type="email" id="email" name="email" placeholder="vous@exemple.com" value="<?= esc($email ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label for="date_naissance">Date de naissance</label>
                    <div class="input-wrap">
                        <input type="date" id="date_naissance" name="date_naissance" value="<?= esc($date_naissance ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label for="genre">Genre</label>
                    <select id="genre" name="genre" required>
                        <option value="">— Sélectionner —</option>
                        <option value="femme" <?= ($genre ?? '') === 'femme' ? 'selected' : '' ?>>Femme</option>
                        <option value="homme" <?= ($genre ?? '') === 'homme' ? 'selected' : '' ?>>Homme</option>
                    </select>
                </div>

                <div class="field-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <div class="input-wrap" style="position:relative;">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Min. 8 caractères" required style="padding-right:3rem;">
                        <button type="button" class="toggle-password" aria-label="Afficher le mot de passe" title="Afficher le mot de passe" style="position:absolute;right:0.6rem;top:50%;transform:translateY(-50%);background:none;border:none;padding:0;line-height:0;cursor:pointer;color:inherit;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:2rem">
                    <a href="/auth/login" class="btn btn-secondary btn-full">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-full">Suivant</button>
                </div>
            </form>

            <div class="login-footer">
                Déjà inscrit ? <a href="/auth/login">Se connecter</a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        var eyeOffSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a19.92 19.92 0 0 1 4.06-5.94"></path><path d="M1 1l22 22"></path></svg>';

        var toggle1 = document.querySelector('.toggle-password');
        var input1 = document.getElementById('mot_de_passe');
        if (toggle1 && input1) {
            toggle1.addEventListener('click', function () {
                if (input1.type === 'password') {
                    input1.type = 'text';
                    toggle1.innerHTML = eyeOffSvg;
                } else {
                    input1.type = 'password';
                    toggle1.innerHTML = eyeSvg;
                }
            });
        }
    });
    </script>
</body>
</html>
