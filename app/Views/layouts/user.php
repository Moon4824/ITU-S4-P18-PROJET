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
    <?php $openWalletModal = ! empty($openWalletModal); ?>
    <?php $walletErrorMessage = isset($errorMessage) && is_string($errorMessage) ? trim((string) $errorMessage) : ''; ?>
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
                <a class="sidebar-link" href="/user/objectifs">New Objectifs</a>
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

            <?php if ($walletErrorMessage !== '') : ?>
                <div class="alert alert-danger" style="margin:0 0 16px;">
                    <?= esc($walletErrorMessage) ?>
                </div>
            <?php endif; ?>

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

    <div class="modal-overlay" id="gold-confirm-modal" aria-hidden="true" aria-modal="true" role="dialog" aria-labelledby="gold-confirm-title">
        <div class="modal" style="max-width:500px;">
            <div class="modal-icon" style="background:linear-gradient(135deg,#ffd54f,#ffb300);">
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z" fill="currentColor"/></svg>
            </div>
            <h3 id="gold-confirm-title">Activer l'option GOLD</h3>
            <p style="color:var(--c-muted);margin-bottom:18px;">Confirmez l'activation de l'option Gold pour bénéficier de réductions exclusives.</p>

            <div class="dashboard-card" style="margin-bottom:16px;background:linear-gradient(135deg,rgba(255,213,79,.1),rgba(255,179,0,.05));border:1px solid #ffe082;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <div style="font-size:0.85rem;color:var(--c-muted);margin-bottom:4px;">Coût de l'option</div>
                        <strong style="font-size:1.3rem;color:#ff6b6b;" id="gold-price-display">—</strong>
                    </div>
                    <div>
                        <div style="font-size:0.85rem;color:var(--c-muted);margin-bottom:4px;">Réduction sur les régimes</div>
                        <strong style="font-size:1.3rem;color:#51cf66;" id="gold-discount-display">—</strong>
                    </div>
                </div>
                <div style="font-size:0.9rem;color:var(--c-muted);">
                    <strong style="color:var(--c-text);">Avantages :</strong><br>
                    ✓ Réductions sur TOUS les régimes premium<br>
                    ✓ Accès illimité aux contenus exclusifs<br>
                    ✓ Priorité support client
                </div>
            </div>

            <div style="padding:12px;background:var(--c-surface);border-radius:6px;border-left:3px solid #ff6b6b;margin-bottom:16px;font-size:0.9rem;">
                <strong>Solde après activation :</strong><br>
                <span id="gold-new-balance">— Ar</span>
            </div>

            <div class="modal-actions" style="gap:10px;flex-wrap:wrap;">
                <button type="button" class="btn btn-ghost" id="gold-confirm-cancel">Annuler</button>
                <button type="button" class="btn btn-primary" id="gold-confirm-accept">Confirmer et activer</button>
            </div>
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
            const shouldOpenWalletModal = <?= json_encode($openWalletModal) ?>;
            const initialWalletError = <?= json_encode($walletErrorMessage) ?>;
            const goldBtn = document.getElementById('wallet-gold-btn');
            const modalGoldBtn = document.getElementById('wallet-modal-gold-btn');
            const walletBtn = document.getElementById('wallet-open-btn');
            const walletModal = document.getElementById('wallet-modal');
            const walletCloseBtn = document.getElementById('wallet-close-btn');

            // Modal management
            function openWalletModal(message = '', type = ''){
                if(walletModal) {
                    walletModal.classList.add('open');
                    walletModal.setAttribute('aria-hidden', 'false');
                    if (message) {
                        setWalletMessage(message, type || 'error');
                    } else {
                        setWalletMessage('');
                    }
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
                    // Store gold config for modal
                    window.__GOLD_CONFIG__ = {
                        price: j.gold_price ?? 0,
                        discount: j.gold_discount ?? 0,
                        balance: j.balance ?? 0,
                    };
                }catch(e){
                    // ignore
                }
            }

            function formatBalance(amount) {
                return Number(amount || 0).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Ar';
            }

            function showGoldConfirmModal(){
                const goldConfig = window.__GOLD_CONFIG__ || { price: 0, discount: 0, balance: 0 };
                const goldConfirmModal = document.getElementById('gold-confirm-modal');
                const priceDisplay = document.getElementById('gold-price-display');
                const discountDisplay = document.getElementById('gold-discount-display');
                const newBalanceDisplay = document.getElementById('gold-new-balance');

                if(priceDisplay) priceDisplay.textContent = formatBalance(goldConfig.price);
                if(discountDisplay) discountDisplay.textContent = goldConfig.discount + ' %';
                
                const newBalance = goldConfig.balance - goldConfig.price;
                if(newBalanceDisplay) newBalanceDisplay.textContent = formatBalance(newBalance);

                if(goldConfirmModal){
                    goldConfirmModal.classList.add('open');
                    goldConfirmModal.setAttribute('aria-hidden', 'false');
                }
            }

            function closeGoldConfirmModal(){
                const goldConfirmModal = document.getElementById('gold-confirm-modal');
                if(goldConfirmModal){
                    goldConfirmModal.classList.remove('open');
                    goldConfirmModal.setAttribute('aria-hidden', 'true');
                }
            }

            async function performActivateGold(){
                closeGoldConfirmModal();
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
                        alert(j.message || 'Gold activé avec succès !');
                        refreshStatus();
                        closeWalletModal();
                    } else {
                        alert(j.message || 'Erreur lors de l\'activation');
                    }
                }catch(e){
                    alert('Erreur réseau lors de l\'activation.');
                }
            }

            function activateGold(){
                const goldConfig = window.__GOLD_CONFIG__ || { price: 0, discount: 0, balance: 0 };
                
                // Check if already gold
                const goldBtn = document.getElementById('wallet-gold-btn');
                if(goldBtn && goldBtn.classList.contains('btn-gold-active')) {
                    alert('Vous avez déjà l\'option Gold.');
                    return;
                }

                // // Check balance
                // if(goldConfig.balance < goldConfig.price){
                //     alert('Solde insuffisant pour activer l\'option Gold.');
                //     return;
                // }

                showGoldConfirmModal();
            }

            if(goldBtn){ goldBtn.addEventListener('click', activateGold); }
            if(modalGoldBtn){ modalGoldBtn.addEventListener('click', activateGold); }

            // Gold confirm modal handlers
            const goldConfirmModal = document.getElementById('gold-confirm-modal');
            const goldConfirmCancel = document.getElementById('gold-confirm-cancel');
            const goldConfirmAccept = document.getElementById('gold-confirm-accept');

            if(goldConfirmCancel){ goldConfirmCancel.addEventListener('click', closeGoldConfirmModal); }
            if(goldConfirmAccept){ goldConfirmAccept.addEventListener('click', performActivateGold); }

            if(goldConfirmModal){
                goldConfirmModal.addEventListener('click', function(e){
                    if(e.target === goldConfirmModal) closeGoldConfirmModal();
                });
            }

            if (shouldOpenWalletModal) {
                openWalletModal(initialWalletError, initialWalletError ? 'error' : '');
            }

            // Initial fetch
            refreshStatus();
        })();
    </script>
</body>
</html>