<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
$page_title = 'Buat Booking';
$conn = getDB();
$user = null;
if ($conn) {
    $s = $conn->prepare("SELECT nama_lengkap, no_hp FROM users WHERE id=?");
    $s->bind_param('i', $_SESSION['user_id']); $s->execute();
    $user = $s->get_result()->fetch_assoc(); $s->close();
}
include '_header.php';
?>

<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?></div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text">
    <h1>Buat <em>Booking</em></h1>
    <p>Isi langkah-langkah berikut untuk menjadwalkan kunjungan Anda.</p>
  </div>
</div>

<!-- STEPPER -->
<div style="margin-bottom:2rem;">
  <div style="display:flex;align-items:center;gap:0;max-width:600px;">
    <?php
    $steps = ['Data Diri','Layanan & Dokter','Jadwal','Konfirmasi'];
    foreach ($steps as $i => $s):
      $n = $i + 1;
    ?>
    <div style="flex:1;display:flex;align-items:center;">
      <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem;position:relative;z-index:1;">
        <div class="step-circle" id="step-circle-<?= $n ?>" style="width:32px;height:32px;border-radius:50%;background:<?=$n===1?'var(--black)':'var(--gray-light)'?>;color:<?=$n===1?'var(--gold)':'var(--gray-soft)'?>;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:600;transition:all .3s;">
          <?= $n ?>
        </div>
        <span style="font-size:.68rem;font-weight:500;text-transform:uppercase;letter-spacing:.06em;color:<?=$n===1?'var(--black)':'var(--gray-soft)'?>;white-space:nowrap;" id="step-label-<?=$n?>"><?= $s ?></span>
      </div>
      <?php if ($i < count($steps)-1): ?>
      <div style="flex:1;height:2px;background:var(--gray-light);margin:0 .3rem;margin-bottom:1.2rem;" id="step-line-<?=$n?>"></div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- FORM WRAPPER -->
<div style="max-width:640px;">

<!-- STEP 1: DATA DIRI -->
<div class="card step-panel" id="panel-1">
  <div class="card-header"><h3>Step 1 — <em>Data Diri</em></h3></div>
  <div class="card-body">
    <div class="form-group">
      <label>Nama Lengkap <span class="req">*</span></label>
      <input type="text" id="f_nama" value="<?= htmlspecialchars($user['nama_lengkap'] ?? '') ?>" maxlength="150">
      <small>Data diambil dari akun Anda, bisa diubah jika perlu.</small>
    </div>
    <div class="form-group">
      <label>No. HP <span class="req">*</span></label>
      <input type="tel" id="f_nohp" value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>" maxlength="20">
    </div>
    <div class="form-group">
      <label>Catatan (opsional)</label>
      <textarea id="f_catatan" placeholder="Contoh: alergi tertentu, keluhan khusus..."></textarea>
    </div>
    <div style="display:flex;justify-content:flex-end;">
      <button class="btn btn-primary" id="btn-step1-next" onclick="goStep(2)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</div>

<!-- STEP 2: LAYANAN & DOKTER -->
<div class="card step-panel" id="panel-2" style="display:none;">
  <div class="card-header"><h3>Step 2 — <em>Layanan &amp; Dokter</em></h3></div>
  <div class="card-body">
    <div class="form-group">
      <label>Pilih Layanan <span class="req">*</span></label>
      <select id="f_layanan" onchange="onLayananChange()">
        <option value="">— Pilih layanan —</option>
      </select>
      <small id="layanan_info" style="color:var(--gold-deep);"></small>
    </div>
    <div class="form-group" id="wrap_dokter" style="display:none;">
      <label>Pilih Dokter <span class="req">*</span></label>
      <select id="f_dokter" onchange="onDokterChange()">
        <option value="">— Pilih dokter —</option>
      </select>
      <small id="dokter_spesialis" style="color:var(--gold-deep);"></small>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:.5rem;">
      <button class="btn btn-outline" onclick="goStep(1)"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
      <button class="btn btn-primary" onclick="goStep(3)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</div>

<!-- STEP 3: JADWAL -->
<div class="card step-panel" id="panel-3" style="display:none;">
  <div class="card-header"><h3>Step 3 — <em>Jadwal</em></h3></div>
  <div class="card-body">
    <div class="form-group">
      <label>Tanggal Kunjungan <span class="req">*</span></label>
      <input type="date" id="f_tanggal" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" onchange="loadJam()">
    </div>
    <div class="form-group" id="wrap_jam" style="display:none;">
      <label>Pilih Jam <span class="req">*</span></label>
      <div id="jam_grid" style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.3rem;"></div>
      <small id="jam_info"></small>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:.5rem;">
      <button class="btn btn-outline" onclick="goStep(2)"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
      <button class="btn btn-primary" onclick="goStep(4)">Selanjutnya <i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</div>

<!-- STEP 4: KONFIRMASI -->
<div class="card step-panel" id="panel-4" style="display:none;">
  <div class="card-header"><h3>Step 4 — <em>Konfirmasi Booking</em></h3></div>
  <div class="card-body">

    <!-- Ringkasan -->
    <div style="background:var(--gold-pale);border:1px solid var(--gold-light);border-radius:var(--radius-lg);padding:1.4rem;margin-bottom:1.4rem;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem 1.5rem;font-size:.88rem;">
        <div><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">Nama</div><strong id="sum_nama">—</strong></div>
        <div><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">No. HP</div><strong id="sum_nohp">—</strong></div>
        <div><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">Layanan</div><strong id="sum_layanan">—</strong></div>
        <div><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">Dokter</div><strong id="sum_dokter">—</strong></div>
        <div><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">Tanggal</div><strong id="sum_tanggal">—</strong></div>
        <div><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">Jam</div><strong id="sum_jam">—</strong></div>
        <div style="grid-column:1/-1;"><div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-soft);margin-bottom:.2rem;">Catatan</div><span id="sum_catatan" style="color:var(--gray-soft);">—</span></div>
      </div>
      <!-- Total Harga -->
      <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--gold-light);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Total Harga</span>
        <span id="sum_total" style="font-size:1.1rem;font-weight:700;color:var(--gold-deep);">—</span>
      </div>
    </div>

    <!-- Form submit -->
    <form action="../../auth/booking_process.php" method="POST" enctype="multipart/form-data" id="formBooking">
      <input type="hidden" name="action"     value="buat_booking">
      <input type="hidden" name="dokter_id"  id="h_dokter_id">
      <input type="hidden" name="layanan_id" id="h_layanan_id">
      <input type="hidden" name="tanggal"    id="h_tanggal">
      <input type="hidden" name="jam"        id="h_jam">
      <input type="hidden" name="catatan"    id="h_catatan">

      <!-- Metode Pembayaran -->
      <div class="form-group">
        <label>Metode Pembayaran <span class="req">*</span></label>
        <div style="display:flex;flex-direction:column;gap:.6rem;margin-top:.5rem;">
          <label style="display:flex;align-items:center;gap:.7rem;font-weight:normal;cursor:pointer;line-height:1;">
            <input type="radio" name="metode_pembayaran" value="Transfer Bank" required style="width:16px;height:16px;flex-shrink:0;margin:0;cursor:pointer;">
            <span>Transfer Bank</span>
          </label>
          <label style="display:flex;align-items:center;gap:.7rem;font-weight:normal;cursor:pointer;line-height:1;">
            <input type="radio" name="metode_pembayaran" value="Cash" style="width:16px;height:16px;flex-shrink:0;margin:0;cursor:pointer;">
            <span>Cash</span>
          </label>
          <label style="display:flex;align-items:center;gap:.7rem;font-weight:normal;cursor:pointer;line-height:1;">
            <input type="radio" name="metode_pembayaran" value="QRIS" style="width:16px;height:16px;flex-shrink:0;margin:0;cursor:pointer;">
            <span>QRIS</span>
          </label>
        </div>
      </div>

      <!-- Bukti Pembayaran -->
      <div class="form-group">
        <label>Bukti Pembayaran <span style="color:var(--gray-soft);font-weight:normal;">(opsional)</span></label>
        <input type="file" name="bukti_pembayaran" accept="image/*,.pdf" style="margin-top:.3rem;">
        <small>Format: JPG, PNG, atau PDF. Maks 2MB.</small>
      </div>

      <p style="font-size:.8rem;color:var(--gray-soft);margin-bottom:1.2rem;">
        <i class="fa-solid fa-circle-info" style="color:var(--gold-deep);"></i>
        Booking akan berstatus <strong>Pending</strong> hingga dikonfirmasi oleh admin.
      </p>

      <div style="display:flex;justify-content:space-between;">
        <button type="button" class="btn btn-outline" onclick="goStep(3)"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Konfirmasi Booking</button>
      </div>
    </form>
  </div>
</div>

</div><!-- end max-width wrapper -->

<script>
let currentStep = 1;
let selectedJam  = '';
let selectedHarga = 0;

// ── Stepper ──────────────────────────────────
function goStep(n) {
  if (n > currentStep) {
    if (currentStep === 1) {
      if (!document.getElementById('f_nama').value.trim() || !document.getElementById('f_nohp').value.trim()) {
        alert('Nama dan No. HP wajib diisi.'); return;
      }
    }
    if (currentStep === 2) {
      if (!document.getElementById('f_layanan').value) { alert('Pilih layanan terlebih dahulu.'); return; }
      if (!document.getElementById('f_dokter').value)  { alert('Pilih dokter terlebih dahulu.'); return; }
      document.getElementById('f_tanggal').value = '';
      document.getElementById('wrap_jam').style.display = 'none';
      document.getElementById('jam_grid').innerHTML = '';
      document.getElementById('jam_info').textContent = '';
      selectedJam = '';
    }
    if (currentStep === 3) {
      if (!document.getElementById('f_tanggal').value) { alert('Pilih tanggal.'); return; }
      if (!selectedJam) { alert('Pilih jam.'); return; }
    }
  }

  document.getElementById('panel-' + currentStep).style.display = 'none';
  document.getElementById('panel-' + n).style.display = 'block';

  for (let i = 1; i <= 4; i++) {
    const circle = document.getElementById('step-circle-' + i);
    const label  = document.getElementById('step-label-' + i);
    const line   = document.getElementById('step-line-' + i);
    if (i < n) {
      circle.style.background = 'var(--gold-deep)'; circle.style.color = 'var(--black)';
      circle.innerHTML = '<i class="fa-solid fa-check" style="font-size:.65rem;"></i>';
      label.style.color = 'var(--gold-deep)';
      if (line) line.style.background = 'var(--gold-deep)';
    } else if (i === n) {
      circle.style.background = 'var(--black)'; circle.style.color = 'var(--gold)';
      circle.textContent = i; label.style.color = 'var(--black)';
    } else {
      circle.style.background = 'var(--gray-light)'; circle.style.color = 'var(--gray-soft)';
      circle.textContent = i; label.style.color = 'var(--gray-soft)';
      if (line) line.style.background = 'var(--gray-light)';
    }
  }

  if (n === 4) fillSummary();
  currentStep = n;
}

// ── Load layanan saat pertama kali masuk step 2 ──
let layananLoaded = false;
document.getElementById('btn-step1-next').addEventListener('click', function () {
  if (layananLoaded) return;
  layananLoaded = true;
  fetch('../../../app/auth/api_booking.php?type=layanan')
    .then(r => r.json()).then(res => {
      const sel = document.getElementById('f_layanan');
      sel.innerHTML = '<option value="">— Pilih layanan —</option>';
      (res.data || []).forEach(l => {
        sel.innerHTML += `<option value="${l.id}" data-harga="${l.harga}" data-durasi="${l.durasi_menit}">${l.nama}</option>`;
      });
    });
});

// ── Saat layanan dipilih → tampilkan info + load dokter ──
function onLayananChange() {
  const sel       = document.getElementById('f_layanan');
  const opt       = sel.options[sel.selectedIndex];
  const wrapDok   = document.getElementById('wrap_dokter');
  const dokterSel = document.getElementById('f_dokter');

  dokterSel.innerHTML = '<option value="">— Pilih dokter —</option>';
  document.getElementById('dokter_spesialis').textContent = '';
  wrapDok.style.display = 'none';
  selectedHarga = 0;

  if (!opt.value) {
    document.getElementById('layanan_info').textContent = '';
    return;
  }

  selectedHarga = parseInt(opt.dataset.harga) || 0;
  const durasi  = opt.dataset.durasi;
  document.getElementById('layanan_info').textContent =
    `Rp ${selectedHarga.toLocaleString('id-ID')} · ${durasi} menit`;

  fetch(`../../../app/auth/api_booking.php?type=dokter_by_layanan&layanan_id=${opt.value}`)
    .then(r => r.json()).then(res => {
      dokterSel.innerHTML = '<option value="">— Pilih dokter —</option>';
      const list = res.data || [];
      if (list.length === 0) {
        dokterSel.innerHTML += '<option value="" disabled>Tidak ada dokter tersedia untuk layanan ini</option>';
      } else {
        list.forEach(d => {
          dokterSel.innerHTML += `<option value="${d.id}" data-spesialis="${d.spesialisasi}">${d.nama}</option>`;
        });
      }
      wrapDok.style.display = 'block';
    });
}

// ── Saat dokter dipilih → tampilkan spesialisasi ──
function onDokterChange() {
  const sel = document.getElementById('f_dokter');
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('dokter_spesialis').textContent = opt.dataset.spesialis || '';
}

// ── Load jam tersedia ──
function loadJam() {
  const dokter_id = document.getElementById('f_dokter').value;
  const tanggal   = document.getElementById('f_tanggal').value;
  const wrap      = document.getElementById('wrap_jam');
  const grid      = document.getElementById('jam_grid');
  const info      = document.getElementById('jam_info');
  if (!dokter_id || !tanggal) return;
  selectedJam = '';
  grid.innerHTML = '<span style="color:var(--gray-soft);font-size:.82rem;">Memuat jadwal...</span>';
  wrap.style.display = 'block';
  fetch(`../../../app/auth/api_booking.php?type=jam_tersedia&dokter_id=${dokter_id}&tanggal=${tanggal}`)
    .then(r => r.json()).then(res => {
      grid.innerHTML = '';
      if (res.cuti) {
        info.textContent = 'Dokter cuti pada hari ini. Pilih tanggal lain.';
        info.style.color = 'var(--danger)';
        return;
      }
      if (!res.data || res.data.length === 0) {
        info.textContent = 'Tidak ada jam tersedia. Semua slot sudah terpesan.';
        info.style.color = 'var(--danger)';
        return;
      }
      info.textContent = '';
      res.data.forEach(jam => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = jam;
        btn.style.cssText = 'padding:.4rem .9rem;border:1.5px solid var(--gold-light);border-radius:var(--radius);font-family:var(--font-body);font-size:.82rem;cursor:pointer;transition:all .25s;background:var(--white);';
        btn.onmouseover = () => { if (selectedJam !== jam) btn.style.borderColor = 'var(--gold-mid)'; };
        btn.onmouseout  = () => { if (selectedJam !== jam) btn.style.borderColor = 'var(--gold-light)'; };
        btn.onclick = () => {
          document.querySelectorAll('#jam_grid button').forEach(b => {
            b.style.background = 'var(--white)'; b.style.borderColor = 'var(--gold-light)'; b.style.color = 'var(--black)';
          });
          btn.style.background = 'var(--black)'; btn.style.borderColor = 'var(--black)'; btn.style.color = 'var(--gold)';
          selectedJam = jam;
        };
        grid.appendChild(btn);
      });
    });
}

// ── Isi ringkasan konfirmasi ──
function fillSummary() {
  const layananSel = document.getElementById('f_layanan');
  const dokterSel  = document.getElementById('f_dokter');
  const tanggal    = document.getElementById('f_tanggal').value;
  const tgl        = tanggal
    ? new Date(tanggal).toLocaleDateString('id-ID', {weekday:'long', day:'2-digit', month:'long', year:'numeric'})
    : '—';

  document.getElementById('sum_nama').textContent    = document.getElementById('f_nama').value || '—';
  document.getElementById('sum_nohp').textContent    = document.getElementById('f_nohp').value || '—';
  document.getElementById('sum_layanan').textContent = layananSel.options[layananSel.selectedIndex]?.text || '—';
  document.getElementById('sum_dokter').textContent  = dokterSel.options[dokterSel.selectedIndex]?.text  || '—';
  document.getElementById('sum_tanggal').textContent = tgl;
  document.getElementById('sum_jam').textContent     = selectedJam || '—';
  document.getElementById('sum_catatan').textContent = document.getElementById('f_catatan').value || '—';
  document.getElementById('sum_total').textContent   = selectedHarga
    ? 'Rp ' + selectedHarga.toLocaleString('id-ID')
    : '—';

  document.getElementById('h_layanan_id').value = layananSel.value;
  document.getElementById('h_dokter_id').value  = dokterSel.value;
  document.getElementById('h_tanggal').value    = tanggal;
  document.getElementById('h_jam').value        = selectedJam;
  document.getElementById('h_catatan').value    = document.getElementById('f_catatan').value;
}
</script>

<?php if($conn) $conn->close(); include '_footer.php'; ?>
