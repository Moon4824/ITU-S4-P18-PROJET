<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace utilisateur') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .wallet-gold-pill.btn-gold-inactive{ background:#ffffff; color:#333; border:1px solid #e0c97a; padding:6px 8px; border-radius:4px; }
        .wallet-gold-pill.btn-gold-active{ background:#ffd54f; color:#222; border:1px solid #ffb300; padding:6px 8px; border-radius:4px; }
        .btn.btn-gold{ background:linear-gradient(180deg,#ffd54f,#ffb300); color:#111; border:none; padding:8px 12px; border-radius:4px; cursor:pointer }
    </style>
</head>
<body>
    <?php $role = (string) ($user['role'] ?? ''); ?>
    <?php $soldeLabel = isset($user['solde_monnaie']) ? number_format((float) $user['solde_monnaie'], 2, ',', ' ') . ' Ar' : '0,00 Ar'; ?>
    <div class="app-shell app-shell-user">
        <aside class="sidebar sidebar-user">
            <div class="sidebar-brand">
                <img src="/assets/logo/diet.png" alt="NutriStep logo" style="width:32px;height:32px;object-fit:contain">
                <div>
                    <div class="brand-name">NutriStep</div>
                    <div class="brand-sub">Espace utilisateur</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a class="sidebar-link" href="/user">Dashboard</a>
                <a class="sidebar-link" href="/user/imc">IMC</a>
                <a class="sidebar-link" href="/user/objectifs">Objectifs</a>
            </nav>
        </aside>

        <main class="main-panel main-panel-user">
            <header class="topbar">
                <div class="topbar-search">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Rechercher…" />
                </div>
                
                <div class="topbar-actions">
                    <button type="button" class="wallet-btn" id="wallet-open-btn" title="Consulter mon solde">
                        <span class="wallet-btn-icon">
                            <svg viewBox="0 0 24 24"><path d="M21 7H5a3 3 0 0 1 0-6h16v6Z"/><path d="M3 7v12a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V7H5a3 3 0 0 1-2-1Z"/><circle cx="18" cy="13" r="1.5"/></svg>
                        </span>
                        <span>
                            <strong>Solde</strong>
                            <small id="wallet-topbar-balance"><?= esc($soldeLabel) ?></small>
                        </span>
                    </button>

                    <button type="button" id="wallet-gold-btn" class="wallet-gold-pill btn-gold-inactive" title="Activer GOLD">GOLD</button>

                    <a href="/user/profile" class="icon-btn" title="Mon profil">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>
                    </a>
                </div>
            </header>

            <section class="dashboard-content">
                <?= $this->renderSection('content') ?>
            </section>
        </main>
    </div>

    <div class="modal-overlay" id="wallet-modal" aria-hidden="true" aria-modal="true" role="dialog" aria-labelledby="wallet-modal-title">
        <div class="modal wallet-modal">
            <div class="modal-icon wallet-modal-icon">
                <svg viewBox="0 0 24 24"><path d="M3 7h18a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/><path d="M18 13h4"/><circle cx="18.5" cy="12" r="1"/></svg>
            </div>
            <h3 id="wallet-modal-title">Votre solde</h3>
            <p>Consultez votre solde exact et rechargez-le avec un code argent valide.</p>

            <div class="wallet-balance-box">
                <span class="wallet-balance-label">Solde exact</span>
                <strong id="wallet-balance-value"><?= esc($soldeLabel) ?></strong>
                <small id="wallet-balance-note">Chargement du solde en cours…</small>
            </div>

            <form id="wallet-code-form" class="wallet-form" method="post" action="<?= base_url('portefeuille/code') ?>">
                <?= csrf_field() ?>
                <div class="field-group">
                    <label for="wallet-code-input">Code de crédit</label>
                    <input type="text" id="wallet-code-input" name="code" maxlength="15" autocomplete="off" placeholder="Saisir le code argent">
                </div>
                <div class="wallet-form-note">Un code ne peut être utilisé qu’une seule fois.</div>

                <div class="wallet-message" id="wallet-message" aria-live="polite"></div>

                <div class="modal-actions wallet-modal-actions">
                    <button type="button" class="btn btn-ghost" id="wallet-close-btn">Fermer</button>
                    <button type="button" class="btn btn-gold" id="wallet-modal-gold-btn">GOLD</button>
                    <button type="submit" class="btn btn-primary" id="wallet-submit-btn">Créditer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.__WALLET_MODAL__ = {
            summaryUrl: <?= json_encode(base_url('portefeuille/summary')) ?>,
            redeemUrl: <?= json_encode(base_url('portefeuille/code')) ?>,
            balanceLabel: <?= json_encode($soldeLabel) ?>,
            activateGoldUrl: <?= json_encode(base_url('portefeuille/activate-gold')) ?>,
            csrfTokenName: <?= json_encode(csrf_token()) ?>,
            csrfTokenValue: <?= json_encode(csrf_hash()) ?>,
        };
    </script>
    <script src="<?= base_url('assets/js/portefeuille.js') ?>"></script>
    <script>
        (function(){
            const cfg = window.__WALLET_MODAL__ || {};
            const goldBtn = document.getElementById('wallet-gold-btn');
            const modalGoldBtn = document.getElementById('wallet-modal-gold-btn');
            const walletBtn = document.getElementById('wallet-open-btn');
            const walletModal = document.getElementById('wallet-modal');
            const walletCloseBtn = document.getElementById('wallet-close-btn');

            // Modal management
            function openWalletModal(){
                if(walletModal) {
                    walletModal.classList.add('open');
                    walletModal.setAttribute('aria-hidden', 'false');
                }
            }

            function closeWalletModal(){
                if(walletModal) {
                    walletModal.classList.remove('open');
                    walletModal.setAttribute('aria-hidden', 'true');
                }
            }

            // Close modal when clicking overlay
            if(walletModal){
                walletModal.addEventListener('click', function(e){
                    if(e.target === walletModal) closeWalletModal();
                });
            }

            if(walletBtn){ walletBtn.addEventListener('click', openWalletModal); }
            if(walletCloseBtn){ walletCloseBtn.addEventListener('click', closeWalletModal); }

            function setGoldActive(isActive){
                if(!goldBtn) return;
                if(isActive){
                    goldBtn.classList.remove('btn-gold-inactive');
                    goldBtn.classList.add('btn-gold-active');
                } else {
                    goldBtn.classList.remove('btn-gold-active');
                    goldBtn.classList.add('btn-gold-inactive');
                }
            }

            async function refreshStatus(){
                try{
                    const res = await fetch(cfg.summaryUrl, { credentials: 'same-origin' });
                    const j = await res.json();
                    setGoldActive((j.est_gold ?? 0) === 1);
                    const balanceVal = document.getElementById('wallet-topbar-balance');
                    const balanceModal = document.getElementById('wallet-balance-value');
                    if(balanceVal) balanceVal.textContent = j.balance_label ?? cfg.balanceLabel;
                    if(balanceModal) balanceModal.textContent = j.balance_label ?? cfg.balanceLabel;
                }catch(e){
                    // ignore
                }
            }

            async function activateGold(){
                if(!confirm('Confirmer l\'activation de l\'option GOLD ?')) return;
                try{
                    const body = {};
                    body[cfg.csrfTokenName] = cfg.csrfTokenValue;
                    const res = await fetch(cfg.activateGoldUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(body),
                    });
                    const j = await res.json();
                    if(j.success){
                        setGoldActive(true);
                        alert(j.message || 'Gold activé');
                        refreshStatus();
                    } else {
                        alert(j.message || 'Erreur lors de l\'activation');
                    }
                }catch(e){
                    alert('Erreur réseau lors de l\'activation.');
                }
            }

            if(goldBtn){ goldBtn.addEventListener('click', activateGold); }
            if(modalGoldBtn){ modalGoldBtn.addEventListener('click', activateGold); }

            // Initial fetch
            refreshStatus();
        })();
    </script>
</body>
</html>