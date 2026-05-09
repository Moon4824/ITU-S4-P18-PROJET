function normalizeBMIInterpretationLabel(label) {
  return String(label || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function displayBMIInterpretationLabel(label) {
  const normalized = normalizeBMIInterpretationLabel(label);
  if (normalized === 'sous-poids' || normalized === 'sous poids') {
    return 'Maigreur';
  }
  return label || '—';
}

function getBMIStyle(label) {
  const key = normalizeBMIInterpretationLabel(label);
  if (key.includes('maigreur') || key.includes('sous poids')) {
    return { badgeClass: 'imc-badge-maigreur', fillClass: 'imc-fill-maigreur', width: '25%' };
  }
  if (key.includes('normal')) {
    return { badgeClass: 'imc-badge-normal', fillClass: 'imc-fill-normal', width: '50%' };
  }
  if (key.includes('surpoids')) {
    return { badgeClass: 'imc-badge-surpoids', fillClass: 'imc-fill-surpoids', width: '75%' };
  }
  return { badgeClass: 'imc-badge-obesite', fillClass: 'imc-fill-obesite', width: '100%' };
}

function findInterpretation(imc, interpretations) {
  const value = Number(imc);
  if (!Array.isArray(interpretations)) return null;

  return interpretations.find((interpretation) => {
    const min = interpretation.min === null || interpretation.min === '' ? null : Number(interpretation.min);
    const max = interpretation.max === null || interpretation.max === '' ? null : Number(interpretation.max);
    const aboveMin = min === null || value >= min;
    const belowMax = max === null || value <= max;
    return aboveMin && belowMax;
  }) || null;
}

function setCheckedObjectiveByInterpretation(label) {
  const normalized = normalizeBMIInterpretationLabel(label);
  let objective = 'equilibre';

  if (normalized.includes('maigreur') || normalized.includes('sous poids')) {
    objective = 'prise';
  } else if (normalized.includes('surpoids') || normalized.includes('obesite')) {
    objective = 'perte';
  } else if (normalized.includes('normal')) {
    objective = 'maintien';
  }

  const input = document.querySelector(`input[name="objectif"][value="${objective}"]`);
  if (input) input.checked = true;
}

function initBMIPage() {
  const data = window.__IMC_PAGE__ || {};
  const bmi = Number(data.imc);
  if (!bmi) return;

  const applyWithInterpretations = function (interpretations) {
    const interpretation = findInterpretation(bmi, interpretations) || { libelle: 'Normal', min: 18.5, max: 24.99 };
    const style = getBMIStyle(interpretation.libelle);

    const bmiValue = document.getElementById('bmi-value');
    const bmiLabel = document.getElementById('bmi-label');
    const bmiBar = document.getElementById('bmi-bar');
    const bmiHint = document.getElementById('bmi-hint');

    if (bmiValue) bmiValue.textContent = bmi.toFixed(1);
    if (bmiLabel) {
      bmiLabel.textContent = displayBMIInterpretationLabel(interpretation.libelle);
      bmiLabel.classList.remove('imc-badge-maigreur', 'imc-badge-normal', 'imc-badge-surpoids', 'imc-badge-obesite');
      bmiLabel.classList.add(style.badgeClass);
    }
    if (bmiBar) {
      bmiBar.style.width = style.width;
      bmiBar.classList.remove('imc-fill-maigreur', 'imc-fill-normal', 'imc-fill-surpoids', 'imc-fill-obesite');
      bmiBar.classList.add(style.fillClass);
    }
    if (bmiHint) {
      bmiHint.textContent = data.nom
        ? `${data.nom} · ${data.genre || 'Genre non précisé'}`
        : 'Analyse de votre profil';
    }
    setCheckedObjectiveByInterpretation(interpretation.libelle);

    // populate the scale labels container
    const labelsContainer = document.getElementById('imc-scale-labels');
    if (labelsContainer) {
      labelsContainer.innerHTML = '';
      interpretations.forEach((it) => {
        const label = displayBMIInterpretationLabel(it.libelle || '');
        const span = document.createElement('span');
        span.textContent = label;
        labelsContainer.appendChild(span);
      });
    }
  };

  // If server already provided interpretations, use them. Otherwise fetch from API.
  if (Array.isArray(data.interpretations) && data.interpretations.length > 0) {
    applyWithInterpretations(data.interpretations);
    return;
  }

  fetch('/api/imc/interpretations')
    .then((res) => {
      if (!res.ok) throw new Error('Failed to load interpretations');
      return res.json();
    })
    .then((interpretations) => applyWithInterpretations(interpretations))
    .catch(() => {
      // Fallback to a simple built-in list when API fails
      const fallback = [
        { libelle: 'Maigreur', min: null, max: 18.49 },
        { libelle: 'Normal', min: 18.5, max: 24.99 },
        { libelle: 'Surpoids', min: 25, max: 29.99 },
        { libelle: 'Obésité', min: 30, max: null },
      ];
      applyWithInterpretations(fallback);
    });

  const bmiValue = document.getElementById('bmi-value');
  const bmiLabel = document.getElementById('bmi-label');
  const bmiBar = document.getElementById('bmi-bar');
  const bmiHint = document.getElementById('bmi-hint');

  
}

function continueToRegimes() {
  window.location.href = '/objectifs';
}

window.initBMIPage = initBMIPage;
window.continueToRegimes = continueToRegimes;

document.addEventListener('DOMContentLoaded', initBMIPage);