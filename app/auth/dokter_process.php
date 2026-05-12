<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/admin/dokter.php'); exit; }
$conn = getDB();
$action = $_POST['action'] ?? '';

if ($action === 'tambah') {
    $nama = trim($_POST['nama'] ?? ''); $spesialis = trim($_POST['spesialisasi'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? ''); $is_aktif = intval($_POST['is_aktif'] ?? 1);
    $layanan = $_POST['layanan'] ?? [];
    if (!$nama || !$spesialis) { $_SESSION['flash']=['type'=>'error','msg'=>'Nama dan spesialisasi wajib diisi.']; header('Location: /glowclick-pwd/app/pages/admin/dokter.php'); exit; }
    $s = $conn->prepare("INSERT INTO dokter (nama,spesialisasi,no_hp,is_aktif) VALUES (?,?,?,?)");
    $s->bind_param('sssi',$nama,$spesialis,$no_hp,$is_aktif); $s->execute();
    $dokter_id = $conn->insert_id; $s->close();
    foreach ($layanan as $lid) { $lid=intval($lid); $s=$conn->prepare("INSERT IGNORE INTO dokter_layanan (dokter_id,layanan_id) VALUES (?,?)"); $s->bind_param('ii',$dokter_id,$lid); $s->execute(); $s->close(); }
    $_SESSION['flash']=['type'=>'success','msg'=>'Dokter berhasil ditambahkan.'];
} elseif ($action === 'edit') {
    $id=intval($_POST['id']??0); $nama=trim($_POST['nama']??''); $spesialis=trim($_POST['spesialisasi']??'');
    $no_hp=trim($_POST['no_hp']??''); $is_aktif=intval($_POST['is_aktif']??1); $layanan=$_POST['layanan']??[];
    $s=$conn->prepare("UPDATE dokter SET nama=?,spesialisasi=?,no_hp=?,is_aktif=? WHERE id=?");
    $s->bind_param('sssii',$nama,$spesialis,$no_hp,$is_aktif,$id); $s->execute(); $s->close();
    $conn->query("DELETE FROM dokter_layanan WHERE dokter_id=$id");
    foreach ($layanan as $lid) { $lid=intval($lid); $s=$conn->prepare("INSERT IGNORE INTO dokter_layanan (dokter_id,layanan_id) VALUES (?,?)"); $s->bind_param('ii',$id,$lid); $s->execute(); $s->close(); }
    $_SESSION['flash']=['type'=>'success','msg'=>'Data dokter berhasil diperbarui.'];
} elseif ($action === 'hapus') {
    $id=intval($_POST['id']??0);
    $s=$conn->prepare("DELETE FROM dokter WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'Dokter berhasil dihapus.'];
}
$conn->close();
header('Location: /glowclick-pwd/app/pages/admin/dokter.php'); exit;
