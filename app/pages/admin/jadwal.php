<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/glowclick-pwd/app/config/db.php';
$page_title = 'Jadwal Dokter';
$conn = getDB();

$dokters = $conn ? $conn->query("SELECT id, nama, spesialisasi FROM dokter WHERE is_aktif=1 ORDER BY nama") : null;
$selected_dokter_id = intval($_GET['dokter_id'] ?? 0);
$jadwal_list = null;
$selected_dokter = null;

if ($selected_dokter_id && $conn) {
    $s = $conn->prepare("SELECT id, nama, spesialisasi FROM dokter WHERE id=? AND is_aktif=1");
    $s->bind_param('i', $selected_dokter_id);
    $s->execute();
    $selected_dokter = $s->get_result()->fetch_assoc();
    $s->close();

    $jadwal_list = $conn->query("
        SELECT * FROM jadwal_dokter
        WHERE dokter_id = $selected_dokter_id
        ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')
    ");
}

$hari_list = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

include '_header.php';
?>

<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
  <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text">
    <h1>Jadwal <em>Dokter</em></h1>
    <p>Atur hari dan jam praktek dokter, termasuk status cuti.</p>
  </div>
</div>

<!-- Pilih Dokter -->
<div class="card" style="margin-bottom:1.4rem;">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
      <div class="form-group" style="flex:1;min-width:200px;margin-bottom:0;">
        <label>Pilih Dokter</label>
        <select name="dokter_id" onchange="this.form.submit()">
          <option value="">— Pilih dokter —</option>
          <?php if ($dokters) while ($d = $dokters->fetch_assoc()): ?>
          <option value="<?= $d['id'] ?>" <?= $selected_dokter_id==$d['id']?'selected':'' ?>>
            <?= htmlspecialchars($d['nama']) ?> — <?= htmlspecialchars($d['spesialisasi']) ?>
          </option>
          <?php endwhile; ?>
        </select>
      </div>
    </form>
  </div>
</div>

<?php if ($selected_dokter && $jadwal_list): ?>
<!-- Info Dokter -->
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.4rem;padding:1rem 1.4rem;background:var(--white);border:1px solid var(--gold-light);border-radius:var(--radius-lg);">
  <div class="dokter-avatar" style="width:44px;height:44px;font-size:1rem;"><?= strtoupper(substr($selected_dokter['nama'],4,1)) ?></div>
  <div>
    <div style="font-weight:600;font-size:.95rem;"><?= htmlspecialchars($selected_dokter['nama']) ?></div>
    <div style="font-size:.78rem;color:var(--gray-soft);"><?= htmlspecialchars($selected_dokter['spesialisasi']) ?></div>
  </div>
  <div style="margin-left:auto;">
    <button class="btn btn-primary" onclick="openModal('modalTambahJadwal')">
      <i class="fa-solid fa-plus"></i> Tambah Jadwal
    </button>
  </div>
</div>

<!-- Tabel Jadwal -->
<div class="card">
  <div class="card-header"><h3>Jadwal <em>Praktek</em></h3></div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Hari</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Status</th><th>Keterangan</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php if ($jadwal_list->num_rows > 0): while ($j = $jadwal_list->fetch_assoc()): ?>
      <tr>
        <td><strong><?= $j['hari'] ?></strong></td>
        <td><?= substr($j['jam_mulai'],0,5) ?></td>
        <td><?= substr($j['jam_selesai'],0,5) ?></td>
        <td><span class="badge badge-<?= $j['status'] ?>"><?= ucfirst($j['status']) ?></span></td>
        <td><?= htmlspecialchars($j['keterangan'] ?? '—') ?></td>
        <td>
          <div style="display:flex;gap:.4rem;">
            <button class="btn btn-outline btn-sm" onclick="openEditJadwal(<?= htmlspecialchars(json_encode($j)) ?>)">
              <i class="fa-solid fa-pen"></i>
            </button>
            <button class="btn btn-danger btn-sm" onclick="openHapusJadwal(<?= $j['id'] ?>, '<?= $j['hari'] ?>')">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endwhile; else: ?>
      <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i><p>Belum ada jadwal untuk dokter ini.</p></div></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH JADWAL -->
<div class="modal-overlay" id="modalTambahJadwal">
  <div class="modal">
    <div class="modal-header">
      <h3>Tambah <em>Jadwal</em></h3>
      <button class="modal-close" onclick="closeModal('modalTambahJadwal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/jadwal_process.php" method="POST">
      <input type="hidden" name="action" value="tambah">
      <input type="hidden" name="dokter_id" value="<?= $selected_dokter_id ?>">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Hari <span class="req">*</span></label>
            <select name="hari" required>
              <?php foreach ($hari_list as $h): ?>
              <option value="<?= $h ?>"><?= $h ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Status <span class="req">*</span></label>
            <select name="status" id="tj_status" onchange="toggleKet('tj')">
              <option value="aktif">Aktif</option>
              <option value="cuti">Cuti</option>
            </select>
          </div>
          <div class="form-group">
            <label>Jam Mulai <span class="req">*</span></label>
            <input type="time" name="jam_mulai" value="08:00" required>
          </div>
          <div class="form-group">
            <label>Jam Selesai <span class="req">*</span></label>
            <input type="time" name="jam_selesai" value="17:00" required>
          </div>
          <div class="form-group full" id="tj_ket_wrap" style="display:none;">
            <label>Keterangan Cuti</label>
            <input type="text" name="keterangan" placeholder="Contoh: Cuti sakit, Cuti tahunan">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalTambahJadwal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT JADWAL -->
<div class="modal-overlay" id="modalEditJadwal">
  <div class="modal">
    <div class="modal-header">
      <h3>Edit <em>Jadwal</em></h3>
      <button class="modal-close" onclick="closeModal('modalEditJadwal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/jadwal_process.php" method="POST">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="ej_id">
      <input type="hidden" name="dokter_id" value="<?= $selected_dokter_id ?>">
      <div class="modal-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Hari <span class="req">*</span></label>
            <select name="hari" id="ej_hari" required>
              <?php foreach ($hari_list as $h): ?>
              <option value="<?= $h ?>"><?= $h ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Status <span class="req">*</span></label>
            <select name="status" id="ej_status" onchange="toggleKet('ej')">
              <option value="aktif">Aktif</option>
              <option value="cuti">Cuti</option>
            </select>
          </div>
          <div class="form-group">
            <label>Jam Mulai <span class="req">*</span></label>
            <input type="time" name="jam_mulai" id="ej_jam_mulai" required>
          </div>
          <div class="form-group">
            <label>Jam Selesai <span class="req">*</span></label>
            <input type="time" name="jam_selesai" id="ej_jam_selesai" required>
          </div>
          <div class="form-group full" id="ej_ket_wrap">
            <label>Keterangan Cuti</label>
            <input type="text" name="keterangan" id="ej_keterangan" placeholder="Contoh: Cuti sakit">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalEditJadwal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL HAPUS JADWAL -->
<div class="modal-overlay" id="modalHapusJadwal">
  <div class="modal" style="max-width:400px;">
    <div class="modal-header">
      <h3>Hapus <em>Jadwal</em></h3>
      <button class="modal-close" onclick="closeModal('modalHapusJadwal')"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="../../auth/jadwal_process.php" method="POST">
      <input type="hidden" name="action" value="hapus">
      <input type="hidden" name="id" id="hj_id">
      <input type="hidden" name="dokter_id" value="<?= $selected_dokter_id ?>">
      <div class="modal-body">
        <p style="font-size:.9rem;color:var(--gray-soft);line-height:1.65;">
          Hapus jadwal hari <strong id="hj_hari"></strong> untuk dokter ini?
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalHapusJadwal')">Batal</button>
        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
      </div>
    </form>
  </div>
</div>

<?php endif; ?>

<script>
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(el=>{
  el.addEventListener('click',function(e){if(e.target===this)this.classList.remove('open');});
});
function toggleKet(prefix){
  const status = document.getElementById(prefix+'_status').value;
  const wrap   = document.getElementById(prefix+'_ket_wrap');
  if(wrap) wrap.style.display = status==='cuti' ? 'block' : 'none';
}
function openEditJadwal(j){
  document.getElementById('ej_id').value          = j.id;
  document.getElementById('ej_hari').value         = j.hari;
  document.getElementById('ej_status').value       = j.status;
  document.getElementById('ej_jam_mulai').value    = j.jam_mulai;
  document.getElementById('ej_jam_selesai').value  = j.jam_selesai;
  document.getElementById('ej_keterangan').value   = j.keterangan ?? '';
  toggleKet('ej');
  openModal('modalEditJadwal');
}
function openHapusJadwal(id, hari){
  document.getElementById('hj_id').value = id;
  document.getElementById('hj_hari').textContent = hari;
  openModal('modalHapusJadwal');
}
</script>

<?php if ($conn) $conn->close(); ?>
<?php include '_footer.php'; ?>
