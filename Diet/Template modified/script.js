// Star rating
function setStars(n) {
  document.querySelectorAll('#stars span').forEach((s, i) => {
    s.classList.toggle('on', i < n);
  });
}

// Range label sync (called inline via oninput, but also wired here for flexibility)
document.addEventListener('DOMContentLoaded', () => {
  const range = document.getElementById('priority-range');
  const label = document.getElementById('range-label');
  if (range && label) {
    range.addEventListener('input', () => { label.textContent = range.value; });
  }
});

// --- Simple registration flow used by inscription1.html and inscription2.html ---
function registerStep1(e) {
  if (e) e.preventDefault();
  const nom = document.getElementById('nom')?.value?.trim();
  const email = document.getElementById('email')?.value?.trim();
  const dob = document.getElementById('dob')?.value || '';
  const genre = document.getElementById('genre')?.value || '';
  const password = document.getElementById('password')?.value || '';
  if (!nom || !email || !password) {
    alert('Veuillez renseigner le nom, l\'email et le mot de passe.');
    return false;
  }
  const temp = { nom, email, dob, genre, password };
  localStorage.setItem('signup_temp', JSON.stringify(temp));
  window.location.href = 'inscription2.html';
  return false;
}

function registerStep2(e) {
  if (e) e.preventDefault();
  const poids = document.getElementById('poids')?.value || '';
  const taille = document.getElementById('taille')?.value || '';
  const tempRaw = localStorage.getItem('signup_temp');
  if (!tempRaw) {
    alert('Aucune donnée trouvée. Veuillez commencer l\'inscription depuis la première étape.');
    window.location.href = 'inscription1.html';
    return false;
  }
  const temp = JSON.parse(tempRaw);
  const user = Object.assign({}, temp, { poids, taille });
  // store user in localStorage (demo only)
  const users = JSON.parse(localStorage.getItem('users') || '[]');
  users.push(user);
  localStorage.setItem('users', JSON.stringify(users));
  localStorage.setItem('signup_profile', JSON.stringify(user));
  localStorage.removeItem('signup_temp');
  window.location.href = 'imc.html';
  return false;
}

function continueToRegimes() {
  const objective = document.querySelector('input[name="objectif"]:checked')?.value || '';
  if (!objective) {
    alert('Choisissez un objectif pour continuer.');
    return;
  }
  localStorage.setItem('chosen_objective', objective);
  window.location.href = 'regimes.html';
}

function getBMICategory(bmi) {
  if (bmi < 18.5) return { label: 'Maigreur', color: 'var(--c-warning)', width: '25%' };
  if (bmi < 25) return { label: 'Normal', color: 'var(--c-success)', width: '50%' };
  if (bmi < 30) return { label: 'Surpoids', color: 'var(--c-warning)', width: '75%' };
  return { label: 'Obésité', color: 'var(--c-danger)', width: '100%' };
}

function initBMIPage() {
  const raw = localStorage.getItem('signup_profile');
  const profile = raw ? JSON.parse(raw) : null;
  if (!profile) return;
  const weight = Number(profile.poids);
  const height = Number(profile.taille) / 100;
  if (!weight || !height) return;
  const bmi = weight / (height * height);
  const category = getBMICategory(bmi);
  const bmiValue = document.getElementById('bmi-value');
  const bmiLabel = document.getElementById('bmi-label');
  const bmiBar = document.getElementById('bmi-bar');
  const bmiHint = document.getElementById('bmi-hint');
  if (bmiValue) bmiValue.textContent = bmi.toFixed(1);
  if (bmiLabel) bmiLabel.textContent = category.label;
  if (bmiBar) {
    bmiBar.style.width = category.width;
    bmiBar.style.background = category.color;
  }
  if (bmiHint) bmiHint.textContent = `${profile.nom} · ${profile.genre || 'Genre non précisé'}`;
}

function initRegimePage() {
  const objective = localStorage.getItem('chosen_objective') || 'equilibre';
  const objectiveLabel = document.getElementById('objective-label');
  if (objectiveLabel) {
    const labels = {
      perte: 'Perte de poids',
      maintien: 'Maintien',
      prise: 'Prise de masse',
      equilibre: 'Équilibre alimentaire'
    };
    objectiveLabel.textContent = labels[objective] || 'Équilibre alimentaire';
  }
}

window.continueToRegimes = continueToRegimes;
window.initBMIPage = initBMIPage;
window.initRegimePage = initRegimePage;

// Expose functions globally for inline onsubmit usage (older browsers)
window.registerStep1 = registerStep1;
window.registerStep2 = registerStep2;
