<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/'.$_SESSION['role'].'/booking.php'); exit; }
$conn = getDB(); $action = $_POST['action'] ?? '';

// ── Admin: ubah status booking ──
if ($action === 'ubah_status' && $_SESSION['role'] === 'admin') {
    $id = intval($_POST['id'] ?? 0); $status = $_POST['status'] ?? '';
    $allowed = ['pending','konfirmasi','selesai','batal'];
    if (in_array($status, $allowed)) {
        $s = $conn->prepare("UPDATE booking SET status=? WHERE id=?");
        $s->bind_param('si', $status, $id); $s->execute(); $s->close();
        $_SESSION['flash'] = ['type'=>'success','msg'=>'Status booking diperbarui.'];
    }
    $conn->close(); header('Location: /glowclick-pwd/app/pages/admin/booking.php'); exit;
}

// ── User: buat booking ──
if ($action === 'buat_booking' && $_SESSION['role'] === 'user') {
    $dokter_id        = intval($_POST['dokter_id']  ?? 0);
    $layanan_id       = intval($_POST['layanan_id'] ?? 0);
    $tanggal          = $_POST['tanggal']  ?? '';
    $jam              = $_POST['jam']      ?? '';
    $catatan          = trim($_POST['catatan'] ?? '');
    $metode           = $_POST['metode_pembayaran'] ?? '';
    $user_id          = $_SESSION['user_id'];

    $allowed_metode = ['Transfer Bank', 'Cash', 'QRIS'];

    if (!$dokter_id || !$layanan_id || !$tanggal || !$jam || !in_array($metode, $allowed_metode)) {
        $_SESSION['flash'] = ['type'=>'error','msg'=>'Semua field wajib diisi.'];
        header('Location: /glowclick-pwd/app/pages/user/booking.php'); exit;
    }

    // Handle upload bukti pembayaran (opsional)
    $bukti_path = null;
    if (!empty($_FILES['bukti_pembayaran']['name'])) {
        $file     = $_FILES['bukti_pembayaran'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','pdf'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($ext, $allowed)) {
            $_SESSION['flash'] = ['type'=>'error','msg'=>'Format bukti pembayaran tidak didukung. Gunakan JPG, PNG, atau PDF.'];
            header('Location: /glowclick-pwd/app/pages/user/booking.php'); exit;
        }
        if ($file['size'] > $max_size) {
            $_SESSION['flash'] = ['type'=>'error','msg'=>'Ukuran file bukti pembayaran melebihi 2MB.'];
            header('Location: /glowclick-pwd/app/pages/user/booking.php'); exit;
        }

        $upload_dir = __DIR__ . '/../../uploads/bukti/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $filename   = 'bukti_' . $user_id . '_' . time() . '.' . $ext;
        $bukti_path = 'uploads/bukti/' . $filename;
        move_uploaded_file($file['tmp_name'], $upload_dir . $filename);
    }

    $s = $conn->prepare("INSERT INTO booking (user_id, dokter_id, layanan_id, tanggal, jam, catatan, metode_pembayaran, bukti_pembayaran) VALUES (?,?,?,?,?,?,?,?)");
    $s->bind_param('iiisssss', $user_id, $dokter_id, $layanan_id, $tanggal, $jam, $catatan, $metode, $bukti_path);
    $s->execute(); $s->close();

    $_SESSION['flash'] = ['type'=>'success','msg'=>'Booking berhasil dibuat! Silakan tunggu konfirmasi.'];
    $conn->close(); header('Location: /glowclick-pwd/app/pages/user/riwayat.php'); exit;
}

// ── User: batal booking ──
if ($action === 'batal_booking' && $_SESSION['role'] === 'user') {
    $id = intval($_POST['id'] ?? 0); $user_id = $_SESSION['user_id'];
    $s  = $conn->prepare("UPDATE booking SET status='batal' WHERE id=? AND user_id=? AND status='pending'");
    $s->bind_param('ii', $id, $user_id); $s->execute(); $s->close();
    $_SESSION['flash'] = ['type'=>'success','msg'=>'Booking berhasil dibatalkan.'];
    $conn->close(); header('Location: /glowclick-pwd/app/pages/user/riwayat.php'); exit;
}

$conn->close(); header('Location: /glowclick-pwd/app/pages/'.$_SESSION['role'].'/booking.php'); exit;
