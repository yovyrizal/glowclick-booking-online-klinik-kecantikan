<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}

$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$no_hp        = trim($_POST['no_hp']        ?? '');
$username     = trim($_POST['username']     ?? '');
$password     = $_POST['password']          ?? '';
$confirm      = $_POST['confirm_password']  ?? '';

// ── Validasi server-side ──────────────────────
if (empty($nama_lengkap) || empty($no_hp) || empty($username) || empty($password) || empty($confirm)) {
    $_SESSION['auth_error'] = 'Semua field wajib diisi.';
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}
if (strlen($nama_lengkap) > 150) {
    $_SESSION['auth_error'] = 'Nama lengkap terlalu panjang.';
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}
if (!preg_match('/^[a-zA-Z0-9_]{4,50}$/', $username)) {
    $_SESSION['auth_error'] = 'Username hanya boleh huruf, angka, underscore, minimal 4 karakter.';
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}
if (strlen($password) < 6) {
    $_SESSION['auth_error'] = 'Password minimal 6 karakter.';
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}
if ($password !== $confirm) {
    $_SESSION['auth_error'] = 'Konfirmasi password tidak cocok.';
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}

// ── Cek username sudah dipakai ────────────────
$conn = getDB();
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    $conn->close();
    $_SESSION['auth_error'] = 'Username sudah digunakan. Pilih username lain.';
    header('Location: /glowclick-pwd/app/pages/register.php');
    exit;
}
$stmt->close();

// ── Hash password & simpan ke DB ──────────────
$hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $conn->prepare(
    "INSERT INTO users (nama_lengkap, no_hp, username, password, role) VALUES (?, ?, ?, ?, 'user')"
);
$stmt->bind_param('ssss', $nama_lengkap, $no_hp, $username, $hashed);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    $_SESSION['auth_success'] = 'Akun berhasil dibuat! Silakan masuk dengan username Anda.';
    header('Location: /glowclick-pwd/app/pages/login.php');
} else {
    $stmt->close();
    $conn->close();
    $_SESSION['auth_error'] = 'Gagal membuat akun. Coba lagi.';
    header('Location: /glowclick-pwd/app/pages/register.php');
}
exit;
