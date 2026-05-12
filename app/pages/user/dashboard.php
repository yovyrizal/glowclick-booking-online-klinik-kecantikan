<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php'); exit;
}
// Halaman ini dikerjakan oleh tim user — akan di-merge via Git
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>User Dashboard</title></head>
<body><p>User dashboard belum tersedia. Akan di-merge dari branch user.</p></body>
</html>
