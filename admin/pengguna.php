<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$rows=$conn->query("SELECT id_user,nama,username,email,npm,prodi,role,created_at FROM user ORDER BY role,nama");
$pageTitle='Data Pengguna';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
<table class="tbl"><thead><tr><th>Nama</th><th>Username</th><th>NPM</th><th>Prodi</th><th>Role</th><th>Terdaftar</th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($r['nama']) ?><br><small style="color:var(--muted)"><?= htmlspecialchars($r['email']) ?></small></td>
  <td><?= htmlspecialchars($r['username']) ?></td>
  <td><?= htmlspecialchars($r['npm']) ?></td>
  <td><?= htmlspecialchars($r['prodi']) ?></td>
  <td><span class="badge badge-info"><?= htmlspecialchars($r['role']) ?></span></td>
  <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
</tr>
<?php endwhile; ?>
</tbody></table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
