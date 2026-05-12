<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
$page_title = 'Dashboard';
$conn = getDB();
$total_user=0;$total_booking=0;$total_dokter=0;$total_testimoni=0;$booking_pending=0;$booking_hari_ini=0;
if ($conn) {
    $r=$conn->query("SELECT COUNT(*) as n FROM users WHERE role='user'"); $total_user=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM booking"); $total_booking=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM dokter WHERE is_aktif=1"); $total_dokter=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM testimoni"); $total_testimoni=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM booking WHERE status='pending'"); $booking_pending=$r?$r->fetch_assoc()['n']:0;
    $r=$conn->query("SELECT COUNT(*) as n FROM booking WHERE tanggal=CURDATE()"); $booking_hari_ini=$r?$r->fetch_assoc()['n']:0;
    $booking_terbaru=$conn->query("SELECT b.id,u.nama_lengkap as nama_user,l.nama as layanan,d.nama as dokter,b.tanggal,b.jam,b.status FROM booking b JOIN users u ON b.user_id=u.id JOIN layanan l ON b.layanan_id=l.id JOIN dokter d ON b.dokter_id=d.id ORDER BY b.created_at DESC LIMIT 5");
}
include '_header.php';
?>
<?php if(isset($_SESSION['flash'])): ?>
<div class="alert alert-<?=$_SESSION['flash']['type']?>"><i class="fa-solid fa-circle-check"></i><?=htmlspecialchars($_SESSION['flash']['msg'])?></div>
<?php unset($_SESSION['flash']); endif; ?>
<div class="page-header"><div class="page-header-text">
  <h1>Selamat Datang, <em><?=htmlspecialchars($_SESSION['nama_lengkap'])?></em></h1>
  <p>Ringkasan data GlowClick — <?=date('d F Y')?></p>
</div></div>
<div class="stat-cards">
  <div class="stat-card"><div class="stat-icon"><i class="fa-solid fa-users"></i></div><div class="stat-info"><div class="num"><?=$total_user?></div><div class="lbl">Total User</div></div></div>
  <div class="stat-card"><div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div><div class="stat-info"><div class="num"><?=$total_booking?></div><div class="lbl">Total Booking</div></div></div>
  <div class="stat-card"><div class="stat-icon"><i class="fa-solid fa-user-doctor"></i></div><div class="stat-info"><div class="num"><?=$total_dokter?></div><div class="lbl">Dokter Aktif</div></div></div>
  <div class="stat-card"><div class="stat-icon"><i class="fa-solid fa-star"></i></div><div class="stat-info"><div class="num"><?=$total_testimoni?></div><div class="lbl">Testimoni</div></div></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.1rem;margin-bottom:1.6rem;">
  <div class="stat-card" style="border-color:#fde08d;"><div class="stat-icon" style="background:#fff8e1;color:#f39c12;border-color:#fde08d;"><i class="fa-solid fa-clock"></i></div><div class="stat-info"><div class="num" style="color:#f39c12;"><?=$booking_pending?></div><div class="lbl">Booking Pending</div></div></div>
  <div class="stat-card" style="border-color:#a9dfbf;"><div class="stat-icon" style="background:#eafaf1;color:#1e8449;border-color:#a9dfbf;"><i class="fa-solid fa-calendar-day"></i></div><div class="stat-info"><div class="num" style="color:#1e8449;"><?=$booking_hari_ini?></div><div class="lbl">Booking Hari Ini</div></div></div>
</div>
<div class="card">
  <div class="card-header"><h3>Booking <em>Terbaru</em></h3><a href="booking.php" class="btn btn-outline btn-sm">Lihat Semua <i class="fa-solid fa-arrow-right fa-xs"></i></a></div>
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Pasien</th><th>Layanan</th><th>Dokter</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if(!empty($booking_terbaru)&&$booking_terbaru->num_rows>0): while($b=$booking_terbaru->fetch_assoc()): ?>
    <tr>
      <td style="color:var(--gray-soft);font-size:.75rem;">#<?=$b['id']?></td>
      <td><?=htmlspecialchars($b['nama_user'])?></td>
      <td><?=htmlspecialchars($b['layanan'])?></td>
      <td><?=htmlspecialchars($b['dokter'])?></td>
      <td><?=date('d M Y',strtotime($b['tanggal']))?></td>
      <td><?=substr($b['jam'],0,5)?></td>
      <td><span class="badge badge-<?=$b['status']?>"><?=ucfirst($b['status'])?></span></td>
      <td><a href="booking.php" class="btn btn-outline btn-sm">Detail</a></td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="8"><div class="empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>Belum ada booking.</p></div></td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div>
<?php if($conn)$conn->close(); include '_footer.php'; ?>
