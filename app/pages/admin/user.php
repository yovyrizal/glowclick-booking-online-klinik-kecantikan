<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../../config/db.php';
$page_title = 'Data User';
$conn = getDB();
$list = $conn ? $conn->query("SELECT id,nama_lengkap,username,no_hp,role,created_at FROM users ORDER BY created_at DESC") : null;
include '_header.php';
?>
<?php if (isset($_SESSION['flash'])): ?>
<div class="alert alert-<?= $_SESSION['flash']['type'] ?>"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['flash']['msg']) ?></div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="page-header">
  <div class="page-header-text"><h1>Data <em>User</em></h1><p>Daftar seluruh pengguna yang terdaftar di GlowClick.</p></div>
</div>

<div class="card">
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Nama</th><th>Username</th><th>No. HP</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if ($list && $list->num_rows > 0): $no=1; while ($u = $list->fetch_assoc()): ?>
    <tr>
      <td style="color:var(--gray-soft);font-size:.75rem;"><?= $no++ ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:.6rem;">
          <div class="sidebar-avatar" style="background:var(--gold-pale);color:var(--gold-deep);border:1px solid var(--gold-light);"><?= strtoupper(substr($u['nama_lengkap'],0,1)) ?></div>
          <?= htmlspecialchars($u['nama_lengkap']) ?>
        </div>
      </td>
      <td>@<?= htmlspecialchars($u['username']) ?></td>
      <td><?= htmlspecialchars($u['no_hp']) ?></td>
      <td><span class="badge badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
      <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
      <td>
        <?php if ($u['id'] != $_SESSION['user_id']): ?>
        <button class="btn btn-danger btn-sm" onclick="openHapus(<?= $u['id'] ?>,'<?= addslashes($u['nama_lengkap']) ?>')"><i class="fa-solid fa-trash"></i></button>
        <?php else: ?><span style="font-size:.75rem;color:var(--gray-soft);">Akun Anda</span><?php endif; ?>
      </td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-users"></i><p>Belum ada user.</p></div></td></tr>
    <?php endif; ?>
    </tbody>
  </table></div>
</div>

<div class="modal-overlay" id="modalHapus">
  <div class="modal" style="max-width:400px;"><div class="modal-header"><h3>Hapus <em>User</em></h3><button class="modal-close" onclick="closeModal('modalHapus')"><i class="fa-solid fa-xmark"></i></button></div>
  <form action="../../auth/user_process.php" method="POST">
    <input type="hidden" name="action" value="hapus"><input type="hidden" name="id" id="h_id">
    <div class="modal-body"><p style="font-size:.9rem;color:var(--gray-soft);">Hapus user <strong id="h_nama"></strong>? Semua data booking user ini juga akan terhapus.</p></div>
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
