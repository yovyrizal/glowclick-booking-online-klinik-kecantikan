<?php
// ===== KONFIGURASI DATABASE =====
// Sesuaikan dengan konfigurasi server Anda
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // ganti dengan username MySQL Anda
define('DB_PASS', '');            // ganti dengan password MySQL Anda
define('DB_NAME', 'glowclick');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
