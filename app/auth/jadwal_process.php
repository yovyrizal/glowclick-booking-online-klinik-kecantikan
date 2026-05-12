<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/admin/jadwal.php'); exit; }
$conn = getDB();
$action=trim($_POST['action']??''); $dokter_id=intval($_POST['dokter_id']??0);
$redirect = "../pages/admin/jadwal.php?dokter_id=$dokter_id";

if ($action==='tambah') {
    $hari=$_POST['hari']??''; $jam_mulai=$_POST['jam_mulai']??''; $jam_selesai=$_POST['jam_selesai']??'';
    $status=$_POST['status']??'aktif'; $ket=trim($_POST['keterangan']??'');
    $s=$conn->prepare("INSERT INTO jadwal_dokter (dokter_id,hari,jam_mulai,jam_selesai,status,keterangan) VALUES (?,?,?,?,?,?)");
    $s->bind_param('isssss',$dokter_id,$hari,$jam_mulai,$jam_selesai,$status,$ket); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Jadwal berhasil ditambahkan.'];
} elseif ($action==='edit') {
    $id=intval($_POST['id']??0); $hari=$_POST['hari']??''; $jam_mulai=$_POST['jam_mulai']??'';
    $jam_selesai=$_POST['jam_selesai']??''; $status=$_POST['status']??'aktif'; $ket=trim($_POST['keterangan']??'');
    $s=$conn->prepare("UPDATE jadwal_dokter SET hari=?,jam_mulai=?,jam_selesai=?,status=?,keterangan=? WHERE id=?");
    $s->bind_param('sssssi',$hari,$jam_mulai,$jam_selesai,$status,$ket,$id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Jadwal berhasil diperbarui.'];
} elseif ($action==='hapus') {
    $id=intval($_POST['id']??0);
    $s=$conn->prepare("DELETE FROM jadwal_dokter WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Jadwal berhasil dihapus.'];
}
$conn->close(); header("Location: $redirect"); exit;
