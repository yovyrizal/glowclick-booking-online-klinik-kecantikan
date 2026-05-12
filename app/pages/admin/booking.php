<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/glowclick-pwd/app/config/db.php';
$page_title = 'Riwayat Booking';
$conn = getDB();

$filter_status = $_GET['status'] ?? '';
$where = $filter_status ? "WHERE b.status='".mysqli_real_escape_string($conn, $filter_status)."'" : '';

$bookings = $conn ? $conn->query("
    SELECT b.id, u.nama_lengkap as nama_user, u.no_hp,
           l.nama as layanan, d.nama as dokter,
           b.tanggal, b.jam, b.status, b.catatan, b.created_at
    FROM booking b
    JOIN users u   ON b.user_id   = u.id
    JOIN layanan l ON b.layanan_id = l.id
    JOIN dokter d  ON b.dokter_id  = d.id
    $where
    ORDER BY b.created_at DESC
") : null;

include '_header.php';
?>

<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
  <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text">
    <h1>Riwayat <em>Booking</em></h1>
    <p>Kelola semua pesanan booking dari pasien.</p>
  </div>
</div>

<!-- Filter Status -->
<div style="display:flex;gap:.6rem;margin-bottom:1.4rem;flex-wrap:wrap;">
  <?php foreach ([''=>'Semua','pending'=>'Pending','konfirmasi'=>'Konfirmasi','selesai'=>'Selesai','batal'=>'Batal'] as $val=>$lbl): ?>
  <a href="?status=<?= $val ?>" class="btn <?= $filter_status===$val?'btn-primary':'btn-outline' ?> btn-sm">
    <?= $lbl ?>
  </a>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Pasien</th><th>No. HP</th><th>Layanan</th><th>Dokter</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php if ($bookings && $bookings->num_rows > 0): $no=1; while ($b = $bookings->fetch_assoc()): ?>
      <tr>
        <td style="color:var(--gray-soft);font-size:.75rem;">#<?= $b['id'] ?></td>
        <td><?= htmlspecialchars($b['nama_user']) ?></td>
        <td><?= htmlspecialchars($b['no_hp']) ?></td>
        <td><?= htmlspecialchars($b['layanan']) ?></td>
        <td><?= htmlspecialchars($b['dokter']) ?></td>
        <td><?= date('d M Y', strtotime($b['tanggal'])) ?></td>
        <td><?= substr($b['jam'],0,5) ?></td>
        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
        <td>
          <button class="btn btn-outline btn-sm" onclick="openUbahStatus(<?= htmlspecialchars(json_encode($b)) ?>)">
            <i class="fa-solid fa-pen"></i> Ubah Status
          </button>
        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="9"><div class="empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>Tidak ada booking<?= $filter_status?" dengan status '$filter_status'":'' ?>.</p></div></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL UBAH STATUS -->
<div class="modal-overlay" id="modalStatus">
  <div class="modal" style="max-width:440px;">
    <div class="modal-header">
      <h3>Ubah <em>Status Booking</em></h3>
      <button class="modal-close" onclick="closeModal('modalStatus')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/booking_process.php" method="POST">
      <input type="hidden" name="action" value="ubah_status">
      <input type="hidden" name="id" id="bs_id">
      <div class="modal-body">
        <div style="background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:var(--radius);padding:.9rem 1rem;margin-bottom:1rem;font-size:.83rem;line-height:1.65;">
          <div><strong id="bs_nama"></strong></div>
          <div style="color:var(--gray-soft);" id="bs_info"></div>
        </div>
        <div class="form-group">
          <label>Status Baru <span class="req">*</span></label>
          <select name="status" id="bs_status">
            <option value="pending">Pending</option>
            <option value="konfirmasi">Konfirmasi</option>
            <option value="selesai">Selesai</option>
            <option value="batal">Batal</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalStatus')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(el=>{
  el.addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});
});
function openUbahStatus(b){
  document.getElementById('bs_id').value     = b.id;
  document.getElementById('bs_nama').textContent = b.nama_user;
  document.getElementById('bs_info').textContent =
    b.layanan + ' · ' + b.dokter + ' · ' +
    new Date(b.tanggal).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}) +
    ' · ' + b.jam.substring(0,5);
  document.getElementById('bs_status').value = b.status;
  openModal('modalStatus');
}
</script>

<?php if ($conn) $conn->close(); ?>
<?php include '_footer.php'; ?>
