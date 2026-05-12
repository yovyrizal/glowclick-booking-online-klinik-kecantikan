<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/db.php';

$conn = getDB();

$sql  = "SELECT id, nama, kota, pesan, rating, foto_url, created_at
         FROM testimoni
         ORDER BY created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query gagal']);
    $conn->close();
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    // Fallback avatar jika foto_url kosong
    if (empty($row['foto_url'])) {
        $row['foto_url'] = 'https://ui-avatars.com/api/?name=' . urlencode($row['nama']) . '&background=E8DBB3&color=000&size=100';
    }
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);
$conn->close();
?>
