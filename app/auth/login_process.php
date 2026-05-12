<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /glowclick-pwd/app/pages/login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// ── Validasi dasar ────────────────────────────
if (empty($username) || empty($password)) {
    $_SESSION['auth_error'] = 'Username dan password wajib diisi.';
    header('Location: /glowclick-pwd/app/pages/login.php');
    exit;
}

// ── Cek ke database ───────────────────────────
$conn = getDB();
$stmt = $conn->prepare("SELECT id, nama_lengkap, username, password, role FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();
$conn->close();

// ── Verifikasi user & password ────────────────
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['auth_error'] = 'Username atau password salah.';
    header('Location: /glowclick-pwd/app/pages/login.php');
    exit;
}

// ── Login berhasil — simpan session ──────────
session_regenerate_id(true); // cegah session fixation
$_SESSION['user_id']      = $user['id'];
$_SESSION['nama_lengkap'] = $user['nama_lengkap'];
$_SESSION['username']     = $user['username'];
$_SESSION['role']         = $user['role'];

// ── Redirect sesuai role ─────────────────────
if ($user['role'] === 'admin') {
    header('Location: /glowclick-pwd/app/pages/admin/dashboard.php');
} else {
    header('Location: /glowclick-pwd/app/pages/user/dashboard.php');
}
exit;
