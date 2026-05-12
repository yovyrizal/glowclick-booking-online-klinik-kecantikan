<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/admin/user.php'); exit; }
$conn=getDB(); $action=$_POST['action']??'';
if ($action==='hapus') {
    $id=intval($_POST['id']??0);
    if ($id===$_SESSION['user_id']) { $_SESSION['flash']=['type'=>'error','msg'=>'Tidak bisa menghapus akun sendiri.']; header('Location: /glowclick-pwd/app/pages/admin/user.php'); exit; }
    $s=$conn->prepare("DELETE FROM users WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $s->close();
    $_SESSION['flash']=['type'=>'success','msg'=>'User berhasil dihapus.'];
}
$conn->close(); header('Location: /glowclick-pwd/app/pages/admin/user.php'); exit;
