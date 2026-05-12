<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/glowclick-pwd/app/config/db.php';
$page_title = 'Kelola Layanan';
$conn = getDB();
$layanan_list = $conn ? $conn->query("SELECT * FROM layanan ORDER BY nama") : null;
include '_header.php';
?>
<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
  <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
</div><?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text">
    <h1>Kelola <em>Layanan</em></h1>
    <p>Tambah, edit, dan hapus layanan klinik GlowClick.</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('modalTambah')"><i class="fa-solid fa-plus"></i> Tambah Layanan</button>
</div>

<div class="card">
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Nama Layanan</th><th>Kategori</th><th>Harga</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if ($layanan_list && $layanan_list->num_rows > 0): $no=1; while ($l = $layanan_list->fetch_assoc()): ?>
    <tr>
      <td style="color:var(--gray-soft);font-size:.75rem;"><?= $no++ ?></td>
      <td><strong><?= htmlspecialchars($l['nama']) ?></strong></td>
      <td><?= htmlspecialchars($l['kategori']) ?></td>
      <td>Rp <?= number_format($l['harga'],0,',','.') ?></td>
      <td><?= $l['durasi_menit'] ?> menit</td>
      <td><span class="badge badge-<?= $l['is_aktif']?'aktif':'batal' ?>"><?= $l['is_aktif']?'Aktif':'Nonaktif' ?></span></td>
      <td>
        <div style="display:flex;gap:.4rem;">
          <button class="btn btn-outline btn-sm" onclick='openEdit(<?= json_encode($l) ?>)'><i class="fa-solid fa-pen"></i></button>
          <button class="btn btn-danger btn-sm" onclick="openHapus(<?= $l['id'] ?>,'<?= addslashes($l['nama']) ?>')"><i class="fa-solid fa-trash"></i></button>
        </div>
      </td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-spa"></i><p>Belum ada layanan.</p></div></td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal-overlay" id="modalTambah">
  <div class="modal"><div class="modal-header"><h3>Tambah <em>Layanan</em></h3><button class="modal-close" onclick="closeModal('modalTambah')"><i class="fa-solid fa-xmark"></i></button></div>
  <form action="../../auth/layanan_process.php" method="POST">
    <input type="hidden" name="action" value="tambah">
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group"><label>Nama Layanan <span class="req">*</span></label><input type="text" name="nama" required maxlength="100"></div>
        <div class="form-group"><label>Kategori <span class="req">*</span></label><input type="text" name="kategori" required maxlength="100"></div>
        <div class="form-group"><label>Harga (Rp) <span class="req">*</span></label><input type="number" name="harga" required min="0"></div>
        <div class="form-group"><label>Durasi (menit) <span class="req">*</span></label><input type="number" name="durasi_menit" value="60" required min="15"></div>
        <div class="form-group"><label>Status</label><select name="is_aktif"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
        <div class="form-group full"><label>Deskripsi</label><textarea name="deskripsi" rows="3"></textarea></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline" onclick="closeModal('modalTambah')">Batal</button><button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
  </form></div>
</div>

<!-- MODAL EDIT -->
<div class="modal-overlay" id="modalEdit">
  <div class="modal"><div class="modal-header"><h3>Edit <em>Layanan</em></h3><button class="modal-close" onclick="closeModal('modalEdit')"><i class="fa-solid fa-xmark"></i></button></div>
  <form action="../../auth/layanan_process.php" method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" id="e_id">
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group"><label>Nama Layanan <span class="req">*</span></label><input type="text" name="nama" id="e_nama" required maxlength="100"></div>
        <div class="form-group"><label>Kategori <span class="req">*</span></label><input type="text" name="kategori" id="e_kategori" required maxlength="100"></div>
        <div class="form-group"><label>Harga (Rp) <span class="req">*</span></label><input type="number" name="harga" id="e_harga" required min="0"></div>
        <div class="form-group"><label>Durasi (menit) <span class="req">*</span></label><input type="number" name="durasi_menit" id="e_durasi" required min="15"></div>
        <div class="form-group"><label>Status</label><select name="is_aktif" id="e_status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
        <div class="form-group full"><label>Deskripsi</label><textarea name="deskripsi" id="e_deskripsi" rows="3"></textarea></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button><button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
  </form></div>
</div>

<!-- MODAL HAPUS -->
<div class="modal-overlay" id="modalHapus">
  <div class="modal" style="max-width:400px;"><div class="modal-header"><h3>Hapus <em>Layanan</em></h3><button class="modal-close" onclick="closeModal('modalHapus')"><i class="fa-solid fa-xmark"></i></button></div>
  <form action="../../auth/layanan_process.php" method="POST">
    <input type="hidden" name="action" value="hapus"><input type="hidden" name="id" id="h_id">
    <div class="modal-body"><p style="font-size:.9rem;color:var(--gray-soft);">Hapus layanan <strong id="h_nama"></strong>? Data yang sudah terhubung ke booking tidak akan terhapus.</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-outline" onclick="closeModal('modalHapus')">Batal</button><button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button></div>
  </form></div>
</div>

<script>
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(el=>{el.addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});});
function openEdit(l){
  document.getElementById('e_id').value=l.id;document.getElementById('e_nama').value=l.nama;
  document.getElementById('e_kategori').value=l.kategori;document.getElementById('e_harga').value=l.harga;
  document.getElementById('e_durasi').value=l.durasi_menit;document.getElementById('e_status').value=l.is_aktif;
  document.getElementById('e_deskripsi').value=l.deskripsi??'';
  openModal('modalEdit');
}
function openHapus(id,nama){document.getElementById('h_id').value=id;document.getElementById('h_nama').textContent=nama;openModal('modalHapus');}
</script>
<?php if($conn)$conn->close(); include '_footer.php'; ?>
