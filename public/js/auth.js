/* ============================================================
   BatikAI — Auth JavaScript
   ============================================================ */

// ── Toggle Password Visibility ────────────────────────────
function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  btn.textContent = isHidden ? '🙈' : '👁';
  btn.setAttribute('aria-label', isHidden ? 'Sembunyikan sandi' : 'Tampilkan sandi');
}

// ── Password Strength Checker ─────────────────────────────
function checkStrength(value) {
  const strengthWrap = document.getElementById('pass-strength');
  const label = document.getElementById('strength-label');
  const bars = [
    document.getElementById('bar1'),
    document.getElementById('bar2'),
    document.getElementById('bar3'),
    document.getElementById('bar4'),
  ];

  if (!strengthWrap) return;

  if (value.length === 0) {
    strengthWrap.style.display = 'none';
    return;
  }

  strengthWrap.style.display = 'block';

  // Score
  let score = 0;
  if (value.length >= 8)                    score++;
  if (/[A-Z]/.test(value))                  score++;
  if (/[0-9]/.test(value))                  score++;
  if (/[^A-Za-z0-9]/.test(value))           score++;

  const levels = [
    { cls: 'active-weak',   txt: 'Terlalu lemah' },
    { cls: 'active-fair',   txt: 'Cukup' },
    { cls: 'active-good',   txt: 'Kuat' },
    { cls: 'active-strong', txt: 'Sangat kuat' },
  ];

  // Reset bars
  bars.forEach(b => { b.className = 'pass-strength__bar'; });

  // Activate bars up to score
  for (let i = 0; i < score; i++) {
    bars[i].classList.add(levels[score - 1].cls);
  }

  label.textContent = levels[score - 1].txt;
  label.style.color = ['#E74C3C','#E67E22','#F1C40F','#27AE60'][score - 1];
}

// ── Input validation highlight ────────────────────────────
document.querySelectorAll('.form-input').forEach(input => {
  input.addEventListener('blur', () => {
    if (!input.checkValidity() && input.value.length > 0) {
      input.classList.add('is-error');
    } else {
      input.classList.remove('is-error');
    }
  });
  input.addEventListener('input', () => {
    if (input.classList.contains('is-error') && input.checkValidity()) {
      input.classList.remove('is-error');
    }
  });
});