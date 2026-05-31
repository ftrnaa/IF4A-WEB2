/* ============================================================
   BatikAI — Auth JavaScript (FULL)
   ============================================================ */

// ─────────────────────────────────────────────
// Toggle Password Visibility
// ─────────────────────────────────────────────
function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === 'password';

  input.type = isHidden ? 'text' : 'password';
  btn.textContent = isHidden ? '🙈' : '👁';

  btn.setAttribute(
    'aria-label',
    isHidden ? 'Sembunyikan sandi' : 'Tampilkan sandi'
  );
}

// ─────────────────────────────────────────────
// Password Strength Checker (Register)
// ─────────────────────────────────────────────
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

  let score = 0;
  if (value.length >= 8) score++;
  if (/[A-Z]/.test(value)) score++;
  if (/[0-9]/.test(value)) score++;
  if (/[^A-Za-z0-9]/.test(value)) score++;

  const levels = [
    { txt: 'Terlalu lemah', color: '#E74C3C' },
    { txt: 'Cukup', color: '#E67E22' },
    { txt: 'Kuat', color: '#F1C40F' },
    { txt: 'Sangat kuat', color: '#27AE60' },
  ];

  // reset bars
  bars.forEach(b => {
    if (b) b.className = 'pass-strength__bar';
  });

  const index = score - 1;

  if (index >= 0) {
    for (let i = 0; i < score; i++) {
      bars[i].classList.add(`active-${index === 0 ? 'weak' : index === 1 ? 'fair' : index === 2 ? 'good' : 'strong'}`);
    }

    label.textContent = levels[index].txt;
    label.style.color = levels[index].color;
  }
}

// ─────────────────────────────────────────────
// INPUT VALIDATION UI
// ─────────────────────────────────────────────
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


// Navbar Auth State
document.addEventListener('DOMContentLoaded', () => {
  const authWrap = document.getElementById('navbar-auth');
  if (!authWrap) return;

  const user = JSON.parse(localStorage.getItem('user'));
  const role = localStorage.getItem('role');

  if (user) {
    authWrap.innerHTML = `
      <div class="user-dropdown" id="user-dropdown">
        <button class="user-dropdown__btn" onclick="toggleDropdown()" aria-expanded="false" aria-haspopup="true">
          <div class="ud-avatar">${user.first_name[0]}${user.last_name?.[0] ?? ''}</div>
          <span class="ud-name">${user.first_name}</span>
          <span class="ud-chevron">⌄</span>
        </button>
        <div class="user-dropdown__menu" id="dropdown-menu" role="menu">
          <div class="ud-header">
            <div class="ud-avatar ud-avatar--lg">${user.first_name[0]}${user.last_name?.[0] ?? ''}</div>
            <div>
              <p class="ud-fullname">${user.first_name} ${user.last_name ?? ''}</p>
              <p class="ud-email">${user.email ?? ''}</p>
            </div>
          </div>
          <a class="ud-item" href="${role === 'admin' ? '/admin' : '/dashboard'}" role="menuitem">Dashboard</a>
          <div class="ud-divider"></div>
          <button class="ud-item ud-item--danger" onclick="logout()" role="menuitem">Keluar</button>
        </div>
      </div>
    `;
  }
});

// Toggle Dropdown
function toggleDropdown() {
  const dropdown = document.getElementById('user-dropdown');
  const menu = document.getElementById('dropdown-menu');
  const btn = dropdown?.querySelector('.user-dropdown__btn');
  if (!menu) return;

  const isOpen = menu.classList.toggle('show');
  dropdown?.classList.toggle('open', isOpen);
  btn?.setAttribute('aria-expanded', isOpen);
}

// Close on outside click
window.addEventListener('click', function (e) {
  const dropdown = document.getElementById('user-dropdown');
  const menu = document.getElementById('dropdown-menu');
  const btn = dropdown?.querySelector('.user-dropdown__btn');
  if (!dropdown || !menu) return;

  if (!dropdown.contains(e.target)) {
    menu.classList.remove('show');
    dropdown.classList.remove('open');
    btn?.setAttribute('aria-expanded', 'false');
  }
});

function logout() {
  fetch('/logout', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content'),
      'Accept': 'application/json',
    }
  })
  .then(() => {
    window.location.href = '/';
  })
  .catch(err => {
    console.error(err);
  });
}
