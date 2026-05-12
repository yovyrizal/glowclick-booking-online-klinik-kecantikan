<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
$page_title = 'Dashboard';
$conn = getDB();
$uid = $_SESSION['user_id'];
$total_booking=0; $pending=0; $selesai=0; $booking_terbaru=null;
if ($conn) {
    $r=$conn->query("SELECT COUNT(*) as n FROM booking WHERE user_id=$uid"); $total_booking=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM booking WHERE user_id=$uid AND status='pending'"); $pending=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM booking WHERE user_id=$uid AND status='selesai'"); $selesai=$r?$r->fetch_assoc()['n']:0;
    $booking_terbaru=$conn->query("SELECT b.id,l.nama as layanan,d.nama as dokter,b.tanggal,b.jam,b.status FROM booking b JOIN layanan l ON b.layanan_id=l.id JOIN dokter d ON b.dokter_id=d.id WHERE b.user_id=$uid ORDER BY b.created_at DESC LIMIT 5");
}
include '_header.php';
?>
<?php if(isset($_SESSION['flash'])): ?>
<div class="alert alert-<?=$_SESSION['flash']['type']?>"><i class="fa-solid fa-circle-check"></i> <?=htmlspecialchars($_SESSION['flash']['msg'])?></div>
<?php unset($_SESSION['flash']); endif; ?>
<div class="page-header">
  <div class="page-header-text">
    <h1>Halo, <em><?=htmlspecialchars($_SESSION['nama_lengkap'])?></em> &#128075;</h1>
    <p>Selamat datang di GlowClick. Yuk booking treatment favoritmu!</p>
  </div>
  <a href="booking.php" class="btn btn-primary"><i class="fa-solid fa-calendar-plus"></i> Buat Booking</a>
</div>
<div class="stat-cards" style="grid-template-columns:repeat(3,1fr);">
  <div class="stat-card"><div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div><div class="stat-info"><div class="num"><?=$total_booking?></div><div class="lbl">Total Booking</div></div></div>
  <div class="stat-card" style="border-color:#fde08d;"><div class="stat-icon" style="background:#fff8e1;color:#f39c12;border-color:#fde08d;"><i class="fa-solid fa-clock"></i></div><div class="stat-info"><div class="num" style="color:#f39c12;"><?=$pending?></div><div class="lbl">Menunggu Konfirmasi</div></div></div>
  <div class="stat-card" style="border-color:#a9dfbf;"><div class="stat-icon" style="background:#eafaf1;color:#1e8449;border-color:#a9dfbf;"><i class="fa-solid fa-circle-check"></i></div><div class="stat-info"><div class="num" style="color:#1e8449;"><?=$selesai?></div><div class="lbl">Treatment Selesai</div></div></div>
</div>
<div class="card">
  <div class="card-header"><h3>Booking <em>Terbaru</em></h3><a href="riwayat.php" class="btn btn-outline btn-sm">Lihat Semua</a></div>
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Layanan</th><th>Dokter</th><th>Tanggal</th><th>Jam</th><th>Status</th></tr></thead>
    <tbody>
    <?php if($booking_terbaru&&$booking_terbaru->num_rows>0): while($b=$booking_terbaru->fetch_assoc()): ?>
    <tr>
      <td style="color:var(--gray-soft);font-size:.75rem;">#<?=$b['id']?></td>
      <td><?=htmlspecialchars($b['layanan'])?></td>
      <td><?=htmlspecialchars($b['dokter'])?></td>
      <td><?=date('d M Y',strtotime($b['tanggal']))?></td>
      <td><?=substr($b['jam'],0,5)?></td>
      <td><span class="badge badge-<?=$b['status']?>"><?=ucfirst($b['status'])?></span></td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="6"><div class="empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>Belum ada booking. <a href="booking.php" style="color:var(--gold-deep);">Buat sekarang!</a></p></div></td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div>
<?php if($conn)$conn->close(); include '_footer.php'; ?>
