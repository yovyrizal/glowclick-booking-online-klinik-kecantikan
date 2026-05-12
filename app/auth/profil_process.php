<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: /glowclick-pwd/app/pages/login.php'); exit; }
require_once __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /glowclick-pwd/app/pages/'.$_SESSION['role'].'/profil.php'); exit; }
$conn=getDB(); $action=$_POST['action']??'';
$redirect = '../pages/'.$_SESSION['role'].'/profil.php';

if ($action==='edit_profil') {
    $nama=trim($_POST['nama_lengkap']??''); $no_hp=trim($_POST['no_hp']??'');
    $username=trim($_POST['username']??''); $pw_baru=$_POST['password_baru']??'';
    $pw_konfirm=$_POST['konfirmasi_password']??''; $pw_skrg=$_POST['password_sekarang']??'';
    // Cek password sekarang
    $s=$conn->prepare("SELECT password FROM users WHERE id=?"); $s->bind_param('i',$_SESSION['user_id']); $s->execute();
    $row=$s->get_result()->fetch_assoc(); $s->close();
    if (!$row || !password_verify($pw_skrg, $row['password'])) {
        $_SESSION['flash']=['type'=>'error','msg'=>'Password saat ini salah.']; header("Location: $redirect"); exit;
    }
    // Cek username unik
    $s=$conn->prepare("SELECT id FROM users WHERE username=? AND id!=?"); $s->bind_param('si',$username,$_SESSION['user_id']); $s->execute(); $s->store_result();
    if ($s->num_rows>0) { $s->close(); $_SESSION['flash']=['type'=>'error','msg'=>'Username sudah digunakan.']; header("Location: $redirect"); exit; }
    $s->close();
    // Update
    if ($pw_baru) {
        if (strlen($pw_baru)<6) { $_SESSION['flash']=['type'=>'error','msg'=>'Password baru minimal 6 karakter.']; header("Location: $redirect"); exit; }
        if ($pw_baru!==$pw_konfirm) { $_SESSION['flash']=['type'=>'error','msg'=>'Konfirmasi password tidak cocok.']; header("Location: $redirect"); exit; }
        $hash=password_hash($pw_baru,PASSWORD_BCRYPT,['cost'=>12]);
        $s=$conn->prepare("UPDATE users SET nama_lengkap=?,no_hp=?,username=?,password=? WHERE id=?");
        $s->bind_param('ssssi',$nama,$no_hp,$username,$hash,$_SESSION['user_id']); $s->execute(); $s->close();
    } else {
        $s=$conn->prepare("UPDATE users SET nama_lengkap=?,no_hp=?,username=? WHERE id=?");
        $s->bind_param('sssi',$nama,$no_hp,$username,$_SESSION['user_id']); $s->execute(); $s->close();
    }
    $_SESSION['nama_lengkap']=$nama; $_SESSION['username']=$username;
    $_SESSION['flash']=['type'=>'success','msg'=>'Profil berhasil diperbarui.'];
}
$conn->close(); header("Location: $redirect"); exit;
