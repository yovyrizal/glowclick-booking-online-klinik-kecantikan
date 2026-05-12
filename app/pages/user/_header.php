<?php
$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $page_title ?? 'Dashboard' ?> — GlowClick</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="/glowclick-pwd/assets/style/dashboard.css"/>
</head>
<body>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <a href="/glowclick-pwd/app/pages/user/dashboard.php">Glow<span>Click</span></a>
    <div class="role-badge" style="background:var(--gold-light);color:var(--gray-soft);">Member</div>
  </div>
  <nav class="sidebar-nav">
    <p class="nav-group-label">Menu</p>
    <ul>
      <li><a href="/glowclick-pwd/app/pages/user/dashboard.php" class="<?= $page==='dashboard.php'?'active':'' ?>"><i class="fa-solid fa-house"></i> Beranda</a></li>
      <li><a href="/glowclick-pwd/app/pages/user/booking.php"   class="<?= $page==='booking.php'  ?'active':'' ?>"><i class="fa-solid fa-calendar-plus"></i> Buat Booking</a></li>
      <li><a href="/glowclick-pwd/app/pages/user/riwayat.php"   class="<?= $page==='riwayat.php'  ?'active':'' ?>"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Booking</a></li>
    </ul>
    <p class="nav-group-label">Akun</p>
    <ul>
      <li><a href="/glowclick-pwd/app/pages/user/profil.php"    class="<?= $page==='profil.php'   ?'active':'' ?>"><i class="fa-solid fa-circle-user"></i> Profil Saya</a></li>
      <li><a href="/glowclick-pwd/index.php"><i class="fa-solid fa-arrow-left"></i> Landing Page</a></li>
    </ul>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar"><?= strtoupper(substr($_SESSION['nama_lengkap']??'U',0,1)) ?></div>
      <div class="sidebar-user-info">
        <div class="s-name"><?= htmlspecialchars($_SESSION['nama_lengkap']??'User') ?></div>
        <div class="s-uname">@<?= htmlspecialchars($_SESSION['username']??'') ?></div>
      </div>
    </div>
    <a href="/glowclick-pwd/app/auth/logout.php" class="btn-logout">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>

<div class="main-content">
  <div class="topbar">
    <div class="topbar-left">
      <button class="topbar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="topbar-title"><?= $page_title ?? 'Dashboard' ?></div>
    </div>
    <div class="topbar-right">
      <span class="topbar-greeting">Halo, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']??'') ?></strong></span>
    </div>
  </div>
  <div class="page-body">