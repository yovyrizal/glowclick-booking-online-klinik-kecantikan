<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/glowclick-pwd/app/config/db.php';
$page_title = 'Kelola Testimoni';
$conn = getDB();
$list = $conn ? $conn->query("SELECT * FROM testimoni ORDER BY created_at DESC") : null;
include '_header.php';
?>
<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?></div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text"><h1>Kelola <em>Testimoni</em></h1><p>Lihat dan hapus testimoni dari klien.</p></div>
</div>

<div class="card">
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Nama</th><th>Kota</th><th>Rating</th><th>Pesan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if ($list && $list->num_rows > 0): $no=1; while ($t = $list->fetch_assoc()): ?>
    <tr>
      <td style="color:var(--gray-soft);font-size:.75rem;"><?= $no++ ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:.6rem;">
          <img src="<?= htmlspecialchars($t['foto_url']??'') ?>" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;background:var(--gold-pale);" onerror="this.style.display='none'">
          <span><?= htmlspecialchars($t['nama']) ?></span>
        </div>
      </td>
      <td><?= htmlspecialchars($t['kota']) ?></td>
      <td><?php for($i=1;$i<=5;$i++) echo $i<=$t['rating']?'<i class="fa-solid fa-star" style="color:var(--gold-deep);font-size:.75rem;"></i>':'<i class="fa-regular fa-star" style="color:var(--gray-light);font-size:.75rem;"></i>'; ?></td>
      <td style="max-width:280px;"><span style="font-size:.82rem;color:var(--gray-soft);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($t['pesan']) ?></span></td>
      <td style="white-space:nowrap;"><?= date('d M Y', strtotime($t['created_at'])) ?></td>
      <td><button class="btn btn-danger btn-sm" onclick="openHapus(<?= $t['id'] ?>,'<?= addslashes($t['nama']) ?>')"><i class="fa-solid fa-trash"></i></button></td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-star"></i><p>Belum ada testimoni.</p></div></td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div>

<div class="modal-overlay" id="modalHapus">
  <div class="modal" style="max-width:400px;"><div class="modal-header"><h3>Hapus <em>Testimoni</em></h3><button class="modal-close" onclick="closeModal('modalHapus')"><i class="fa-solid fa-xmark"></i></button></div>
  <form action="../../auth/testimoni_process.php" method="POST">
    <input type="hidden" name="action" value="hapus"><input type="hidden" name="id" id="h_id">
    <div class="modal-body"><p style="font-size:.9rem;color:var(--gray-soft);">Hapus testimoni dari <strong id="h_nama"></strong>? Tindakan ini tidak bisa dibatalkan.</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-outline" onclick="closeModal('modalHapus')">Batal</button><button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button></div>
  </form></div>
</div>
<script>
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(el=>{el.addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});});
function openHapus(id,nama){document.getElementById('h_id').value=id;document.getElementById('h_nama').textContent=nama;openModal('modalHapus');}
</script>
<?php if($conn)$conn->close(); include '_footer.php'; ?>
