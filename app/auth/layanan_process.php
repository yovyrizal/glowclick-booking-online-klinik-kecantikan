<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/admin/layanan.php'); exit; }
$conn=getDB(); $action=$_POST['action']??'';
if ($action==='tambah') {
    $nama=trim($_POST['nama']??''); $kat=trim($_POST['kategori']??''); $desk=trim($_POST['deskripsi']??'');
    $harga=intval($_POST['harga']??0); $dur=intval($_POST['durasi_menit']??60); $aktif=intval($_POST['is_aktif']??1);
    $s=$conn->prepare("INSERT INTO layanan (nama,kategori,deskripsi,harga,durasi_menit,is_aktif) VALUES (?,?,?,?,?,?)");
    $s->bind_param('sssiii',$nama,$kat,$desk,$harga,$dur,$aktif); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Layanan berhasil ditambahkan.'];
} elseif ($action==='edit') {
    $id=intval($_POST['id']??0); $nama=trim($_POST['nama']??''); $kat=trim($_POST['kategori']??'');
    $desk=trim($_POST['deskripsi']??''); $harga=intval($_POST['harga']??0); $dur=intval($_POST['durasi_menit']??60); $aktif=intval($_POST['is_aktif']??1);
    $s=$conn->prepare("UPDATE layanan SET nama=?,kategori=?,deskripsi=?,harga=?,durasi_menit=?,is_aktif=? WHERE id=?");
    $s->bind_param('sssiii i',$nama,$kat,$desk,$harga,$dur,$aktif,$id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Layanan berhasil diperbarui.'];
} elseif ($action==='hapus') {
    $id=intval($_POST['id']??0);
    $s=$conn->prepare("DELETE FROM layanan WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Layanan berhasil dihapus.'];
}
$conn->close(); header('Location: /glowclick-pwd/app/pages/admin/layanan.php'); exit;
