<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/'.$_SESSION['role'].'/booking.php'); exit; }
$conn=getDB(); $action=$_POST['action']??'';

if ($action==='ubah_status' && $_SESSION['role']==='admin') {
    $id=intval($_POST['id']??0); $status=$_POST['status']??'';
    $allowed=['pending','konfirmasi','selesai','batal'];
    if (in_array($status,$allowed)) {
        $s=$conn->prepare("UPDATE booking SET status=? WHERE id=?"); $s->bind_param('si',$status,$id); $s->execute(); $s->close();
        $_SESSION['flash']=['type'=>'success','msg'=>'Status booking diperbarui.'];
    }
    $conn->close(); header('Location: /glowclick-pwd/app/pages/admin/booking.php'); exit;
} elseif ($action==='buat_booking' && $_SESSION['role']==='user') {
    $dokter_id=intval($_POST['dokter_id']??0); $layanan_id=intval($_POST['layanan_id']??0);
    $tanggal=$_POST['tanggal']??''; $jam=$_POST['jam']??''; $catatan=trim($_POST['catatan']??'');
    $user_id=$_SESSION['user_id'];
    if (!$dokter_id||!$layanan_id||!$tanggal||!$jam) {
        $_SESSION['flash']=['type'=>'error','msg'=>'Semua field wajib diisi.']; header('Location: /glowclick-pwd/app/pages/user/booking.php'); exit;
    }
    $s=$conn->prepare("INSERT INTO booking (user_id,dokter_id,layanan_id,tanggal,jam,catatan) VALUES (?,?,?,?,?,?)");
    $s->bind_param('iiisss',$user_id,$dokter_id,$layanan_id,$tanggal,$jam,$catatan); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Booking berhasil dibuat! Silakan tunggu konfirmasi.'];
    $conn->close(); header('Location: /glowclick-pwd/app/pages/user/riwayat.php'); exit;
} elseif ($action==='batal_booking' && $_SESSION['role']==='user') {
    $id=intval($_POST['id']??0); $user_id=$_SESSION['user_id'];
    $s=$conn->prepare("UPDATE booking SET status='batal' WHERE id=? AND user_id=? AND status='pending'");
    $s->bind_param('ii',$id,$user_id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Booking berhasil dibatalkan.'];
    $conn->close(); header('Location: /glowclick-pwd/app/pages/user/riwayat.php'); exit;
}
$conn->close(); header('Location: /glowclick-pwd/app/pages/'.$_SESSION['role'].'/booking.php'); exit;
