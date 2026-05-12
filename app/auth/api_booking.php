<?php
// app/auth/api_booking.php
// Endpoint JSON untuk kebutuhan form booking user
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}
require_once __DIR__ . '/../config/db.php';
$conn = getDB();
if (!$conn) { echo json_encode(['success'=>false,'message'=>'DB error']); exit; }

$type = $_GET['type'] ?? '';

// Ambil semua layanan aktif
if ($type === 'layanan') {
    $r = $conn->query("SELECT id, nama, kategori, harga, durasi_menit FROM layanan WHERE is_aktif=1 ORDER BY nama");
    $data = [];
    while ($row = $r->fetch_assoc()) $data[] = $row;
    echo json_encode(['success'=>true,'data'=>$data]); exit;
}

// Ambil dokter berdasarkan layanan
if ($type === 'dokter_by_layanan') {
    $layanan_id = intval($_GET['layanan_id'] ?? 0);
    $s = $conn->prepare("
        SELECT d.id, d.nama, d.spesialisasi
        FROM dokter d
        JOIN dokter_layanan dl ON d.id = dl.dokter_id
        WHERE dl.layanan_id = ? AND d.is_aktif = 1
        ORDER BY d.nama
    ");
    $s->bind_param('i', $layanan_id); $s->execute();
    $r = $s->get_result(); $data = [];
    while ($row = $r->fetch_assoc()) $data[] = $row;
    $s->close();
    echo json_encode(['success'=>true,'data'=>$data]); exit;
}

// Ambil jam tersedia berdasarkan dokter + tanggal
if ($type === 'jam_tersedia') {
    $dokter_id = intval($_GET['dokter_id'] ?? 0);
    $tanggal   = $_GET['tanggal'] ?? '';
    if (!$dokter_id || !$tanggal) { echo json_encode(['success'=>true,'data',[]]); exit; }

    // Cek hari dari tanggal
    $hari_en = date('l', strtotime($tanggal));
    $hari_map = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
                 'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
    $hari = $hari_map[$hari_en] ?? '';

    // Cek jadwal dokter di hari itu (harus aktif, bukan cuti)
    $s = $conn->prepare("SELECT jam_mulai, jam_selesai, status FROM jadwal_dokter WHERE dokter_id=? AND hari=?");
    $s->bind_param('is', $dokter_id, $hari); $s->execute();
    $jadwal = $s->get_result()->fetch_assoc(); $s->close();

    if (!$jadwal || $jadwal['status'] === 'cuti') {
        echo json_encode(['success'=>true,'data'=>[],'cuti'=>true]); exit;
    }

    // Generate slot per jam
    $mulai    = strtotime($tanggal . ' ' . $jadwal['jam_mulai']);
    $selesai  = strtotime($tanggal . ' ' . $jadwal['jam_selesai']);
    $slots    = [];
    for ($t = $mulai; $t < $selesai; $t += 3600) {
        $slots[] = date('H:i', $t);
    }

    // Ambil jam yang sudah dipesan (status bukan batal)
    $s = $conn->prepare("SELECT jam FROM booking WHERE dokter_id=? AND tanggal=? AND status != 'batal'");
    $s->bind_param('is', $dokter_id, $tanggal); $s->execute();
    $r = $s->get_result(); $booked = [];
    while ($row = $r->fetch_assoc()) $booked[] = substr($row['jam'], 0, 5);
    $s->close();

    // Filter jam yang sudah terpakai
    $available = array_filter($slots, fn($s) => !in_array($s, $booked));
    echo json_encode(['success'=>true,'data'=>array_values($available),'cuti'=>false]); exit;
}


// Ambil layanan berdasarkan dokter
if ($type === 'layanan_by_dokter') {
    $dokter_id = intval($_GET['dokter_id'] ?? 0);
    $s = $conn->prepare("
        SELECT l.id, l.nama, l.kategori, l.harga, l.durasi_menit
        FROM layanan l
        JOIN dokter_layanan dl ON l.id = dl.layanan_id
        WHERE dl.dokter_id = ? AND l.is_aktif = 1
        ORDER BY l.nama
    ");
    $s->bind_param('i', $dokter_id); $s->execute();
    $r = $s->get_result(); $data = [];
    while ($row = $r->fetch_assoc()) $data[] = $row;
    $s->close();
    echo json_encode(['success'=>true,'data'=>$data]); exit;
}

echo json_encode(['success'=>false,'message'=>'Invalid type']);
$conn->close();
