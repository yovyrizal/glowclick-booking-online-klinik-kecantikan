<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

require_once __DIR__ . '/../config/db.php';

// ===== AMBIL & VALIDASI INPUT =====
$nama   = trim($_POST['nama']   ?? '');
$kota   = trim($_POST['kota']   ?? '');
$pesan  = trim($_POST['pesan']  ?? '');
$rating = intval($_POST['rating'] ?? 5);

// Validasi tidak boleh kosong
if (empty($nama) || empty($kota) || empty($pesan)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit;
}

// Validasi panjang
if (strlen($nama) > 100) {
    echo json_encode(['success' => false, 'message' => 'Nama terlalu panjang (maks 100 karakter)']);
    exit;
}
if (strlen($kota) > 100) {
    echo json_encode(['success' => false, 'message' => 'Kota terlalu panjang (maks 100 karakter)']);
    exit;
}
if (strlen($pesan) > 1000) {
    echo json_encode(['success' => false, 'message' => 'Pesan terlalu panjang (maks 1000 karakter)']);
    exit;
}

// Validasi rating
if ($rating < 1 || $rating > 5) {
    $rating = 5;
}

// Generate avatar otomatis via UI Avatars
$foto_url = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=E8DBB3&color=000&size=100';

// ===== SIMPAN KE DATABASE =====
$conn = getDB();

$stmt = $conn->prepare(
    "INSERT INTO testimoni (nama, kota, pesan, rating, foto_url) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param('sssis', $nama, $kota, $pesan, $rating, $foto_url);

if ($stmt->execute()) {
    $new_id = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'message' => 'Testimoni berhasil dikirim!',
        'data' => [
            'id'         => $new_id,
            'nama'       => htmlspecialchars($nama),
            'kota'       => htmlspecialchars($kota),
            'pesan'      => htmlspecialchars($pesan),
            'rating'     => $rating,
            'foto_url'   => $foto_url,
            'created_at' => date('Y-m-d H:i:s'),
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan testimoni: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
