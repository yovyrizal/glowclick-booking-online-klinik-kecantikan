<?php
session_start();
session_unset();
session_destroy();

// Redirect ke halaman login
header('Location: /glowclick-pwd/app/pages/login.php');
exit;
