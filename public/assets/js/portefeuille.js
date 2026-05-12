function formatMoneyLabel(value) {
  const numeric = Number(value);

  if (Number.isNaN(numeric)) {
    return '0,00 Ar';
  }

  return new Intl.NumberFormat('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(numeric) + ' Ar';
}

function setWalletMessage(message, type) {
  const messageEl = document.getElementById('wallet-message');
  if (!messageEl) return;

  messageEl.textContent = message || '';
  messageEl.classList.remove('is-success', 'is-error');

  if (type === 'success') {
    messageEl.classList.add('is-success');
  } else if (type === 'error') {
    messageEl.classList.add('is-error');
  }
}

function setWalletBalance(label) {
  const balanceValue = document.getElementById('wallet-balance-value');
  const topbarBalance = document.getElementById('wallet-topbar-balance');

  if (balanceValue) balanceValue.textContent = label;
  if (topbarBalance) topbarBalance.textContent = label;
}

async function loadWalletSummary() {
  const config = window.__WALLET_MODAL__ || {};
  const noteEl = document.getElementById('wallet-balance-note');

  try {
    const response = await fetch(config.summaryUrl || '/portefeuille/summary', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    });

    if (!response.ok) {
      throw new Error('summary_request_failed');
    }

    const payload = await response.json();

    if (payload && payload.success && payload.balance_label) {
      setWalletBalance(payload.balance_label);
      if (noteEl) noteEl.textContent = payload.note || 'Solde actualisé.';
      return;
    }

    throw new Error('summary_payload_invalid');
  } catch (error) {
    if (noteEl) noteEl.textContent = 'Impossible de charger le solde exact pour le moment.';
  }
}

function openWalletModal() {
  const modal = document.getElementById('wallet-modal');
  const codeInput = document.getElementById('wallet-code-input');

  if (!modal) return;

  modal.classList.add('open');
  modal.setAttribute('aria-hidden', 'false');
  setWalletMessage('');

  if (codeInput) {
    codeInput.value = '';
    setTimeout(() => codeInput.focus(), 0);
  }

  loadWalletSummary();
}

function closeWalletModal() {
  const modal = document.getElementById('wallet-modal');

  if (!modal) return;

  modal.classList.remove('open');
  modal.setAttribute('aria-hidden', 'true');
  setWalletMessage('');
}

async function submitWalletCode(event) {
  event.preventDefault();

  const config = window.__WALLET_MODAL__ || {};
  const form = event.currentTarget;
  const submitBtn = document.getElementById('wallet-submit-btn');
  const codeInput = document.getElementById('wallet-code-input');

  if (!form || !submitBtn) return;

  const formData = new FormData(form);
  const code = String(formData.get('code') || '').trim();

  if (!code) {
    setWalletMessage('Veuillez saisir un code de crédit.', 'error');
    if (codeInput) codeInput.focus();
    return;
  }

  submitBtn.disabled = true;
  setWalletMessage('Crédit en cours…');

  try {
    const response = await fetch(config.redeemUrl || '/portefeuille/code', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: formData,
      credentials: 'same-origin',
    });

    const payload = await response.json();

    if (!response.ok || !payload.success) {
      throw new Error(payload.message || 'credit_failed');
    }

    const balanceLabel = payload.balance_label || formatMoneyLabel(payload.balance ?? 0);
    setWalletBalance(balanceLabel);
    setWalletMessage(payload.message || 'Code crédité avec succès.', 'success');
    form.reset();

    const noteEl = document.getElementById('wallet-balance-note');
    if (noteEl && payload.note) {
      noteEl.textContent = payload.note;
    }
  } catch (error) {
    setWalletMessage(error.message || 'Une erreur est survenue pendant le crédit.', 'error');
  } finally {
    submitBtn.disabled = false;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const openBtn = document.getElementById('wallet-open-btn');
  const closeBtn = document.getElementById('wallet-close-btn');
  const modal = document.getElementById('wallet-modal');
  const form = document.getElementById('wallet-code-form');

  if (openBtn) {
    openBtn.addEventListener('click', openWalletModal);
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeWalletModal);
  }

  if (modal) {
    modal.addEventListener('click', function (event) {
      if (event.target === modal) {
        closeWalletModal();
      }
    });
  }

  if (form) {
    form.addEventListener('submit', submitWalletCode);
  }

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeWalletModal();
    }
  });
});