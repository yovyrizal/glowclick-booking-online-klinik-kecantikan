<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/glowclick-pwd/app/config/db.php';
$page_title = 'Kelola Dokter';
$conn = getDB();

// Ambil semua dokter + jumlah layanan
$dokters = $conn ? $conn->query("
    SELECT d.*, COUNT(dl.layanan_id) as total_layanan
    FROM dokter d
    LEFT JOIN dokter_layanan dl ON d.id = dl.dokter_id
    GROUP BY d.id ORDER BY d.created_at DESC
") : null;

// Ambil semua layanan untuk form checkbox
$layanan_all = $conn ? $conn->query("SELECT id, nama FROM layanan WHERE is_aktif=1 ORDER BY nama") : null;

include '_header.php';
?>

<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
  <i class="fa-solid fa-<?= $_SESSION['flash']['type']==='success'?'circle-check':'circle-xmark' ?>"></i>
  <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text">
    <h1>Kelola <em>Dokter</em></h1>
    <p>Tambah, edit, dan atur layanan dokter GlowClick.</p>
  </div>
  <button class="btn btn-primary" onclick="openModal('modalTambah')">
    <i class="fa-solid fa-plus"></i> Tambah Dokter
  </button>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Dokter</th><th>Spesialisasi</th><th>No. HP</th><th>Layanan</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php if ($dokters && $dokters->num_rows > 0):
        $no = 1; while ($d = $dokters->fetch_assoc()):
        // Ambil layanan dokter ini
        $lay = $conn->query("SELECT l.nama FROM layanan l JOIN dokter_layanan dl ON l.id=dl.layanan_id WHERE dl.dokter_id={$d['id']}");
        $lay_names = [];
        if ($lay) while ($ln = $lay->fetch_assoc()) $lay_names[] = $ln['nama'];
      ?>
      <tr>
        <td style="color:var(--gray-soft);font-size:.75rem;"><?= $no++ ?></td>
        <td>
          <div class="dokter-info">
            <div class="dokter-avatar"><?= strtoupper(substr($d['nama'],4,1)) ?></div>
            <div><div class="d-name"><?= htmlspecialchars($d['nama']) ?></div></div>
          </div>
        </td>
        <td><?= htmlspecialchars($d['spesialisasi']) ?></td>
        <td><?= htmlspecialchars($d['no_hp'] ?? '-') ?></td>
        <td>
          <?php foreach ($lay_names as $ln): ?>
            <span class="badge badge-admin" style="margin:1px;"><?= htmlspecialchars($ln) ?></span>
          <?php endforeach; ?>
          <?php if (empty($lay_names)): ?><span style="color:var(--gray-soft);font-size:.75rem;">—</span><?php endif; ?>
        </td>
        <td><span class="badge badge-<?= $d['is_aktif'] ? 'aktif':'batal' ?>"><?= $d['is_aktif'] ? 'Aktif':'Nonaktif' ?></span></td>
        <td>
          <div style="display:flex;gap:.4rem;">
            <button class="btn btn-outline btn-sm" onclick="openEdit(<?= htmlspecialchars(json_encode($d)) ?>, <?= htmlspecialchars(json_encode($lay_names)) ?>)">
              <i class="fa-solid fa-pen"></i>
            </button>
            <button class="btn btn-danger btn-sm" onclick="openHapus(<?= $d['id'] ?>, '<?= htmlspecialchars(addslashes($d['nama'])) ?>')">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-user-doctor"></i><p>Belum ada dokter.</p></div></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal-overlay" id="modalTambah">
  <div class="modal">
    <div class="modal-header">
      <h3>Tambah <em>Dokter</em></h3>
      <button class="modal-close" onclick="closeModal('modalTambah')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/dokter_process.php" method="POST">
      <input type="hidden" name="action" value="tambah">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group full">
            <label>Nama Lengkap (dengan gelar) <span class="req">*</span></label>
            <input type="text" name="nama" placeholder="dr. Nama Dokter, Sp.XX" required maxlength="150">
          </div>
          <div class="form-group full">
            <label>Spesialisasi <span class="req">*</span></label>
            <input type="text" name="spesialisasi" placeholder="Dermatologi & Kulit" required maxlength="150">
          </div>
          <div class="form-group">
            <label>No. HP</label>
            <input type="tel" name="no_hp" placeholder="081234567890" maxlength="20">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_aktif">
              <option value="1">Aktif</option>
              <option value="0">Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Layanan yang Ditangani</label>
          <div class="layanan-check-grid">
            <?php if ($layanan_all) { $layanan_all->data_seek(0); while ($lv = $layanan_all->fetch_assoc()): ?>
            <div class="layanan-check-item" onclick="toggleCheck(this)">
              <input type="checkbox" name="layanan[]" value="<?= $lv['id'] ?>" id="lv_<?= $lv['id'] ?>">
              <label for="lv_<?= $lv['id'] ?>"><?= htmlspecialchars($lv['nama']) ?></label>
            </div>
            <?php endwhile; } ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalTambah')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal-overlay" id="modalEdit">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit <em>Dokter</em></h3>
      <button class="modal-close" onclick="closeModal('modalEdit')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/dokter_process.php" method="POST">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group full">
            <label>Nama Lengkap (dengan gelar) <span class="req">*</span></label>
            <input type="text" name="nama" id="edit_nama" required maxlength="150">
          </div>
          <div class="form-group full">
            <label>Spesialisasi <span class="req">*</span></label>
            <input type="text" name="spesialisasi" id="edit_spesialisasi" required maxlength="150">
          </div>
          <div class="form-group">
            <label>No. HP</label>
            <input type="tel" name="no_hp" id="edit_no_hp" maxlength="20">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_aktif" id="edit_is_aktif">
              <option value="1">Aktif</option>
              <option value="0">Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Layanan yang Ditangani</label>
          <div class="layanan-check-grid" id="edit_layanan_grid">
            <?php if ($layanan_all) { $layanan_all->data_seek(0); while ($lv = $layanan_all->fetch_assoc()): ?>
            <div class="layanan-check-item" onclick="toggleCheck(this)">
              <input type="checkbox" name="layanan[]" value="<?= $lv['id'] ?>" id="elv_<?= $lv['id'] ?>">
              <label for="elv_<?= $lv['id'] ?>"><?= htmlspecialchars($lv['nama']) ?></label>
            </div>
            <?php endwhile; } ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal-overlay" id="modalHapus">
  <div class="modal" style="max-width:420px;">
    <div class="modal-header">
      <h3>Hapus <em>Dokter</em></h3>
      <button class="modal-close" onclick="closeModal('modalHapus')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/dokter_process.php" method="POST">
      <input type="hidden" name="action" value="hapus">
      <input type="hidden" name="id" id="hapus_id">
      <div class="modal-body">
        <p style="font-size:.9rem;color:var(--gray-soft);line-height:1.65;">
          Apakah Anda yakin ingin menghapus dokter <strong id="hapus_nama"></strong>?<br>
          Seluruh jadwal dokter ini juga akan terhapus.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalHapus')">Batal</button>
        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
function toggleCheck(el){
  const cb = el.querySelector('input[type=checkbox]');
  cb.checked = !cb.checked;
  el.classList.toggle('checked', cb.checked);
}
// Init checked state on load
document.querySelectorAll('.layanan-check-item input[type=checkbox]').forEach(cb=>{
  if(cb.checked) cb.closest('.layanan-check-item').classList.add('checked');
});
function openEdit(dokter, layananAktif){
  document.getElementById('edit_id').value         = dokter.id;
  document.getElementById('edit_nama').value        = dokter.nama;
  document.getElementById('edit_spesialisasi').value= dokter.spesialisasi;
  document.getElementById('edit_no_hp').value       = dokter.no_hp ?? '';
  document.getElementById('edit_is_aktif').value    = dokter.is_aktif;
  // Set checkbox layanan
  document.querySelectorAll('#edit_layanan_grid input[type=checkbox]').forEach(cb=>{
    const item = cb.closest('.layanan-check-item');
    cb.checked = layananAktif.includes(cb.closest('.layanan-check-item').querySelector('label').textContent.trim());
    item.classList.toggle('checked', cb.checked);
  });
  openModal('modalEdit');
}
function openHapus(id, nama){
  document.getElementById('hapus_id').value = id;
  document.getElementById('hapus_nama').textContent = nama;
  openModal('modalHapus');
}
// Tutup modal klik overlay
document.querySelectorAll('.modal-overlay').forEach(el=>{
  el.addEventListener('click', function(e){ if(e.target===this) this.classList.remove('open'); });
});
</script>

<?php if ($conn) $conn->close(); ?>
<?php include '_footer.php'; ?>
