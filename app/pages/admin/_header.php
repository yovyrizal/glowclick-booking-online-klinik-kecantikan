<?php
// app/pages/admin/_header.php
// Include di bagian atas setiap halaman admin
// Pastikan session_start() dan pengecekan role sudah dilakukan sebelum include ini
$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $page_title ?? 'Dashboard' ?> — GlowClick Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="../../../assets/style/dashboard.css"/>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <a href="dashboard.php">Glow<span>Click</span></a>
    <div class="role-badge">Admin Panel</div>
  </div>

  <nav class="sidebar-nav">
    <p class="nav-group-label">Utama</p>
    <ul>
      <li><a href="dashboard.php"  class="<?= $page==='dashboard.php'  ? 'active':'' ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
      <li><a href="booking.php"    class="<?= $page==='booking.php'    ? 'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Riwayat Booking</a></li>
    </ul>
    <p class="nav-group-label">Manajemen</p>
    <ul>
      <li><a href="dokter.php"     class="<?= $page==='dokter.php'     ? 'active':'' ?>"><i class="fa-solid fa-user-doctor"></i> Dokter</a></li>
      <li><a href="jadwal.php"     class="<?= $page==='jadwal.php'     ? 'active':'' ?>"><i class="fa-solid fa-calendar-days"></i> Jadwal Dokter</a></li>
      <li><a href="layanan.php"    class="<?= $page==='layanan.php'    ? 'active':'' ?>"><i class="fa-solid fa-spa"></i> Layanan</a></li>
      <li><a href="testimoni.php"  class="<?= $page==='testimoni.php'  ? 'active':'' ?>"><i class="fa-solid fa-star"></i> Testimoni</a></li>
      <li><a href="user.php"       class="<?= $page==='user.php'       ? 'active':'' ?>"><i class="fa-solid fa-users"></i> Data User</a></li>
    </ul>
    <p class="nav-group-label">Akun</p>
    <ul>
      <li><a href="profil.php"     class="<?= $page==='profil.php'     ? 'active':'' ?>"><i class="fa-solid fa-circle-user"></i> Profil Saya</a></li>
      <li><a href="../../../index.php"><i class="fa-solid fa-house"></i> Landing Page</a></li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar"><?= strtoupper(substr($_SESSION['nama_lengkap']??'A',0,1)) ?></div>
      <div class="sidebar-user-info">
        <div class="s-name"><?= htmlspecialchars($_SESSION['nama_lengkap']??'Admin') ?></div>
        <div class="s-uname">@<?= htmlspecialchars($_SESSION['username']??'') ?></div>
      </div>
    </div>
    <a href="../../../app/auth/logout.php" class="btn-logout">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>

<!-- MAIN CONTENT -->
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
