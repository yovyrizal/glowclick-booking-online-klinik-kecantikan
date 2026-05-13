<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
$page_title = 'Profil Saya';
$conn = getDB();
$user = null;
if ($conn) {
    $s = $conn->prepare("SELECT id,nama_lengkap,username,no_hp FROM users WHERE id=?");
    $s->bind_param('i',$_SESSION['user_id']); $s->execute();
    $user = $s->get_result()->fetch_assoc(); $s->close();
}
include '_header.php';
?>
<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?></div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header"><div class="page-header-text"><h1>Profil <em>Saya</em></h1><p>Kelola informasi akun admin Anda.</p></div></div>

<div style="max-width:600px;margin:0 auto;">
  <!-- Avatar -->
  <div class="card" style="margin-bottom:1.4rem;">
    <div class="card-body" style="text-align:center;padding:2rem;">
      <div style="width:72px;height:72px;border-radius:50%;background:var(--gold-pale);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:2rem;color:var(--gold-deep);margin:0 auto .75rem;border:2px solid var(--gold-mid);">
        <?= strtoupper(substr($user['nama_lengkap']??'A',0,1)) ?>
      </div>
      <div style="font-family:var(--font-display);font-size:1.25rem;"><?= htmlspecialchars($user['nama_lengkap']??'') ?></div>
      <div style="font-size:.75rem;color:var(--gray-soft);margin-top:.2rem;text-transform:uppercase;letter-spacing:.06em;">Administrator</div>
    </div>
  </div>

  <!-- Form Edit -->
  <div class="card">
    <div class="card-header"><h3>Edit <em>Informasi</em></h3></div>
    <div class="card-body">
      <form action="../../auth/profil_process.php" method="POST">
        <input type="hidden" name="action" value="edit_profil">
        <div class="form-group">
          <label>Nama Lengkap <span class="req">*</span></label>
          <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']??'') ?>" required maxlength="150">
        </div>
        <div class="form-group">
          <label>No. HP <span class="req">*</span></label>
          <input type="tel" name="no_hp" value="<?= htmlspecialchars($user['no_hp']??'') ?>" required maxlength="20">
        </div>
        <div class="form-group">
          <label>Username <span class="req">*</span></label>
          <input type="text" name="username" value="<?= htmlspecialchars($user['username']??'') ?>" required maxlength="50" pattern="[a-zA-Z0-9_]{4,50}">
          <small>Hanya huruf, angka, underscore. Min. 4 karakter.</small>
        </div>
        <div style="border-top:1px solid var(--gold-light);margin:1.2rem 0;padding-top:1.2rem;">
          <p style="font-size:.78rem;color:var(--gray-soft);margin-bottom:1rem;">Kosongkan jika tidak ingin mengganti password.</p>
          <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password_baru" id="pw_baru" minlength="6" autocomplete="new-password">
          </div>
          <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi_password" id="pw_konfirm" autocomplete="new-password">
          </div>
        </div>
        <div class="form-group">
          <label>Password Saat Ini <span class="req">*</span></label>
          <input type="password" name="password_sekarang" required autocomplete="current-password">
          <small>Wajib diisi untuk menyimpan perubahan.</small>
        </div>
        <div style="display:flex;justify-content:flex-end;margin-top:.5rem;">
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if($conn)$conn->close(); include '_footer.php'; ?>
