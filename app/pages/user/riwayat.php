<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
$page_title = 'Riwayat Booking';
$conn = getDB();
$uid = $_SESSION['user_id'];
$filter = $_GET['status'] ?? '';
$where  = $filter ? "AND b.status='".mysqli_real_escape_string($conn,$filter)."'" : '';
$list = $conn ? $conn->query("
    SELECT b.id,l.nama as layanan,d.nama as dokter,d.spesialisasi,
           b.tanggal,b.jam,b.status,b.catatan,b.created_at
    FROM booking b
    JOIN layanan l ON b.layanan_id=l.id
    JOIN dokter d  ON b.dokter_id=d.id
    WHERE b.user_id=$uid $where
    ORDER BY b.created_at DESC
") : null;
include '_header.php';
?>
<?php if(isset($_SESSION['flash'])): ?>
<div class="alert alert-<?=$_SESSION['flash']['type']?>"><i class="fa-solid fa-circle-check"></i> <?=htmlspecialchars($_SESSION['flash']['msg'])?></div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text"><h1>Riwayat <em>Booking</em></h1><p>Semua jadwal kunjungan Anda di GlowClick.</p></div>
  <a href="booking.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Buat Booking Baru</a>
</div>

<div style="display:flex;gap:.6rem;margin-bottom:1.4rem;flex-wrap:wrap;">
  <?php foreach([''=>'Semua','pending'=>'Pending','konfirmasi'=>'Konfirmasi','selesai'=>'Selesai','batal'=>'Batal'] as $v=>$l): ?>
  <a href="?status=<?=$v?>" class="btn <?=$filter===$v?'btn-primary':'btn-outline'?> btn-sm"><?=$l?></a>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Layanan</th><th>Dokter</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if($list&&$list->num_rows>0): while($b=$list->fetch_assoc()): ?>
    <tr>
      <td style="color:var(--gray-soft);font-size:.75rem;">#<?=$b['id']?></td>
      <td><strong><?=htmlspecialchars($b['layanan'])?></strong></td>
      <td>
        <div><?=htmlspecialchars($b['dokter'])?></div>
        <div style="font-size:.72rem;color:var(--gray-soft);"><?=htmlspecialchars($b['spesialisasi'])?></div>
      </td>
      <td><?=date('d M Y',strtotime($b['tanggal']))?></td>
      <td><?=substr($b['jam'],0,5)?></td>
      <td><span class="badge badge-<?=$b['status']?>"><?=ucfirst($b['status'])?></span></td>
      <td>
        <?php if($b['status']==='pending'): ?>
        <button class="btn btn-danger btn-sm" onclick="openBatal(<?=$b['id']?>)"><i class="fa-solid fa-xmark"></i> Batal</button>
        <?php else: ?>
        <span style="font-size:.75rem;color:var(--gray-soft);">—</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="7"><div class="empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>Tidak ada booking<?=$filter?" dengan status '$filter'":''?>. <a href="booking.php" style="color:var(--gold-deep);">Buat sekarang!</a></p></div></td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div>

<div class="modal-overlay" id="modalBatal">
  <div class="modal" style="max-width:400px;"><div class="modal-header"><h3>Batalkan <em>Booking</em></h3><button class="modal-close" onclick="closeModal('modalBatal')"><i class="fa-solid fa-xmark"></i></button></div>
  <form action="../../auth/booking_process.php" method="POST">
    <input type="hidden" name="action" value="batal_booking">
    <input type="hidden" name="id" id="batal_id">
    <div class="modal-body"><p style="font-size:.9rem;color:var(--gray-soft);line-height:1.65;">Yakin ingin membatalkan booking ini? Tindakan ini tidak bisa diurungkan.</p></div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline" onclick="closeModal('modalBatal')">Tidak</button>
      <button type="submit" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Ya, Batalkan</button>
    </div>
  </form></div>
</div>
<script>
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(el=>{el.addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});});
function openBatal(id){document.getElementById('batal_id').value=id;openModal('modalBatal');}
</script>
<?php if($conn)$conn->close(); include '_footer.php'; ?>
