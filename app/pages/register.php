<?php
session_start();

// Kalau sudah login, redirect
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: /glowclick-pwd/app/pages/admin/dashboard.php');
    } else {
        header('Location: /glowclick-pwd/app/pages/user/dashboard.php');
    }
    exit;
}

$error = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar — GlowClick</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="../../assets/style/auth.css"/>
</head>
<body>

<div class="auth-wrapper">

  <!-- ── KIRI: Visual ─────────────────────── -->
  <div class="auth-visual">
    <img
      src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?w=900&q=80&fit=crop&crop=face"
      alt="Beauty Treatment"
    />
    <div class="auth-visual-overlay"></div>
    <div class="auth-visual-content">
      <a href="../../index.php" class="auth-visual-logo">Glow<span>Click</span></a>
      <h2 class="auth-visual-quote">
        Begin Your<br><em>Glow Journey</em>
      </h2>
      <p class="auth-visual-sub">
        Daftarkan diri Anda dan mulai nikmati kemudahan booking treatment kecantikan terbaik.
      </p>
      <div class="auth-visual-badges">
        <span class="auth-badge"><i class="fa-solid fa-gift"></i> Konsultasi Gratis</span>
        <span class="auth-badge"><i class="fa-solid fa-tag"></i> Diskon Member Baru</span>
      </div>
    </div>
  </div>

  <!-- ── KANAN: Form ──────────────────────── -->
  <div class="auth-form-area">
    <a href="../../index.php" class="auth-back">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="auth-header">
      <p class="eyebrow">Bergabung Sekarang</p>
      <h1>Buat <em>Akun Baru</em></h1>
      <p>Sudah punya akun? <a href="login.php" style="color:var(--gold-deep);font-weight:600;border-bottom:1px solid var(--gold-mid);">Masuk di sini</a></p>
    </div>
    <div class="auth-divider"></div>

    <!-- Alert error dari server -->
    <?php if ($error): ?>
    <div class="auth-alert error">
      <i class="fa-solid fa-circle-xmark"></i>
      <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <!-- Alert dari JS -->
    <div class="auth-alert" id="jsAlert">
      <i class="fa-solid fa-circle-xmark"></i>
      <span id="jsAlertMsg"></span>
    </div>

    <form id="registerForm" action="../../app/auth/register_process.php" method="POST" novalidate>

      <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-id-card input-icon"></i>
          <input
            type="text"
            id="nama_lengkap"
            name="nama_lengkap"
            placeholder="Nama lengkap Anda"
            maxlength="150"
            required
          />
        </div>
      </div>

      <div class="form-group">
        <label for="no_hp">Nomor HP <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-phone input-icon"></i>
          <input
            type="tel"
            id="no_hp"
            name="no_hp"
            placeholder="Contoh: 08123456789"
            maxlength="20"
            required
          />
        </div>
      </div>

      <div class="form-group">
        <label for="username">Username <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-user input-icon"></i>
          <input
            type="text"
            id="username"
            name="username"
            placeholder="Buat username unik Anda"
            maxlength="50"
            autocomplete="username"
            required
          />
        </div>
        <small style="font-size:0.7rem;color:var(--gray-soft);margin-top:0.2rem;">
          Hanya huruf, angka, dan underscore (_). Min. 4 karakter.
        </small>
      </div>

      <div class="form-group">
        <label for="password">Password <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock input-icon"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Buat password yang kuat"
            autocomplete="new-password"
            required
          />
          <button type="button" class="toggle-pass" onclick="togglePass('password', this)" tabindex="-1">
            <i class="fa-regular fa-eye"></i>
          </button>
        </div>
        <div class="pass-strength">
          <div class="pass-strength-bar" id="strengthBar"></div>
        </div>
        <div class="pass-strength-label" id="strengthLabel"></div>
      </div>

      <div class="form-group">
        <label for="confirm_password">Konfirmasi Password <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock input-icon"></i>
          <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            placeholder="Ulangi password Anda"
            autocomplete="new-password"
            required
          />
          <button type="button" class="toggle-pass" onclick="togglePass('confirm_password', this)" tabindex="-1">
            <i class="fa-regular fa-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-auth" id="btnRegister">
        <i class="fa-solid fa-user-plus"></i>
        Daftar Sekarang
      </button>

    </form>

    <p class="auth-switch">
      Sudah punya akun? <a href="login.php">Masuk di sini</a>
    </p>
  </div>

</div>

<script>
  // Toggle show/hide password
  function togglePass(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'fa-regular fa-eye-slash';
    } else {
      input.type = 'password';
      icon.className = 'fa-regular fa-eye';
    }
  }

  // Password strength checker
  document.getElementById('password').addEventListener('input', function () {
    const val   = this.value;
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    let score   = 0;

    if (val.length >= 8)                    score++;
    if (/[A-Z]/.test(val))                  score++;
    if (/[0-9]/.test(val))                  score++;
    if (/[^A-Za-z0-9]/.test(val))           score++;

    const levels = [
      { w: '0%',   bg: 'transparent', txt: '' },
      { w: '25%',  bg: '#e74c3c',     txt: 'Lemah' },
      { w: '50%',  bg: '#e67e22',     txt: 'Cukup' },
      { w: '75%',  bg: '#f1c40f',     txt: 'Baik' },
      { w: '100%', bg: '#27ae60',     txt: 'Sangat Kuat' },
    ];

    bar.style.width      = levels[score].w;
    bar.style.background = levels[score].bg;
    label.textContent    = val.length ? levels[score].txt : '';
    label.style.color    = levels[score].bg;
  });

  // Validasi client-side
  document.getElementById('registerForm').addEventListener('submit', function (e) {
    const nama     = document.getElementById('nama_lengkap').value.trim();
    const no_hp    = document.getElementById('no_hp').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirm  = document.getElementById('confirm_password').value;
    const alert    = document.getElementById('jsAlert');
    const alertMsg = document.getElementById('jsAlertMsg');

    alert.className = 'auth-alert';

    if (!nama || !no_hp || !username || !password || !confirm) {
      e.preventDefault();
      alertMsg.textContent = 'Semua field wajib diisi.';
      alert.className = 'auth-alert error';
      return;
    }
    if (!/^[a-zA-Z0-9_]{4,50}$/.test(username)) {
      e.preventDefault();
      alertMsg.textContent = 'Username hanya boleh huruf, angka, underscore, minimal 4 karakter.';
      alert.className = 'auth-alert error';
      return;
    }
    if (password.length < 6) {
      e.preventDefault();
      alertMsg.textContent = 'Password minimal 6 karakter.';
      alert.className = 'auth-alert error';
      return;
    }
    if (password !== confirm) {
      e.preventDefault();
      alertMsg.textContent = 'Konfirmasi password tidak cocok.';
      alert.className = 'auth-alert error';
      return;
    }

    // Loading state
    const btn = document.getElementById('btnRegister');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mendaftarkan...';
  });
</script>

</body>
</html>
