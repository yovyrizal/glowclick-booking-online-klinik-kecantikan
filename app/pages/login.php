<?php
session_start();

// Kalau sudah login, langsung redirect sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: /glowclick-pwd/app/pages/admin/dashboard.php');
    } else {
        header('Location: /glowclick-pwd/app/pages/user/dashboard.php');
    }
    exit;
}

// Ambil pesan dari proses login (jika ada error/sukses dari register)
$error   = $_SESSION['auth_error']   ?? '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — GlowClick</title>
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
      src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=900&q=80&fit=crop"
      alt="Beauty Treatment"
    />
    <div class="auth-visual-overlay"></div>
    <div class="auth-visual-content">
      <a href="../../index.php" class="auth-visual-logo">Glow<span>Click</span></a>
      <h2 class="auth-visual-quote">
        Your Beauty,<br><em>Your Schedule</em>
      </h2>
      <p class="auth-visual-sub">
        Masuk dan nikmati kemudahan booking treatment kecantikan kapan saja, di mana saja.
      </p>
      <div class="auth-visual-badges">
        <span class="auth-badge"><i class="fa-solid fa-shield-halved"></i> Aman & Terpercaya</span>
        <span class="auth-badge"><i class="fa-solid fa-bolt"></i> Booking Instan</span>
        <span class="auth-badge"><i class="fa-solid fa-star"></i> 4.9 Rating</span>
      </div>
    </div>
  </div>

  <!-- ── KANAN: Form ──────────────────────── -->
  <div class="auth-form-area">
    <a href="../../index.php" class="auth-back">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="auth-header">
      <p class="eyebrow">Selamat Datang</p>
      <h1>Masuk ke <em>GlowClick</em></h1>
      <p>Belum punya akun? <a href="register.php" style="color:var(--gold-deep);font-weight:600;border-bottom:1px solid var(--gold-mid);">Daftar sekarang</a></p>
    </div>
    <div class="auth-divider"></div>

    <!-- Alert error / success -->
    <?php if ($error): ?>
    <div class="auth-alert error">
      <i class="fa-solid fa-circle-xmark"></i>
      <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="auth-alert success">
      <i class="fa-solid fa-circle-check"></i>
      <span><?= htmlspecialchars($success) ?></span>
    </div>
    <?php endif; ?>

    <!-- JS alert (untuk validasi client-side) -->
    <div class="auth-alert" id="jsAlert">
      <i class="fa-solid fa-circle-xmark"></i>
      <span id="jsAlertMsg"></span>
    </div>

    <form id="loginForm" action="../../app/auth/login_process.php" method="POST" novalidate>

      <div class="form-group">
        <label for="username">Username <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-user input-icon"></i>
          <input
            type="text"
            id="username"
            name="username"
            placeholder="Masukkan username"
            autocomplete="username"
            maxlength="50"
            required
          />
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password <span class="req">*</span></label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock input-icon"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan password"
            autocomplete="current-password"
            required
          />
          <button type="button" class="toggle-pass" onclick="togglePass('password', this)" tabindex="-1">
            <i class="fa-regular fa-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-auth" id="btnLogin">
        <i class="fa-solid fa-right-to-bracket"></i>
        Masuk
      </button>
    </form>

    <p class="auth-switch">
      Belum punya akun? <a href="register.php">Daftar di sini</a>
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

  // Validasi client-side sebelum submit
  document.getElementById('loginForm').addEventListener('submit', function (e) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const alert    = document.getElementById('jsAlert');
    const alertMsg = document.getElementById('jsAlertMsg');

    alert.className = 'auth-alert';

    if (!username || !password) {
      e.preventDefault();
      alertMsg.textContent = 'Username dan password wajib diisi.';
      alert.className = 'auth-alert error';
      return;
    }

    // Loading state
    const btn = document.getElementById('btnLogin');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
  });
</script>

</body>
</html>
