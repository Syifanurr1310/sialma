<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$f = $_GET['status'] ?? '';
$where = '';
if ($f) { $where = "WHERE p.status='" . $conn->real_escape_string($f) . "'"; }
$rows = $conn->query("SELECT p.*, u.nama AS nama_mhs, u.npm, l.nama_layanan FROM pengajuan p JOIN user u ON u.id_user=p.id_user JOIN jenis_layanan l ON l.id_layanan=p.id_layanan $where ORDER BY p.tanggal_pengajuan DESC");

$pageTitle='Kelola Pengajuan';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
<div class="card-head"><h3>Daftar Pengajuan</h3>
  <form method="get" style="display:flex;gap:8px">
    <select name="status" onchange="this.form.submit()">
      <option value="">Semua status</option>
      <?php foreach (['Menunggu Verifikasi','Diverifikasi','Disetujui','Ditolak','Selesai'] as $s): ?>
        <option <?= $f===$s?'selected':'' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </form>
</div>
<table class="tbl"><thead><tr><th>#</th><th>Mahasiswa</th><th>NPM</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th></th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()): ?>
  <tr>
    <td>#<?= $r['id_pengajuan'] ?></td>
    <td><?= htmlspecialchars($r['nama_mhs']) ?></td>
    <td><?= htmlspecialchars($r['npm']) ?></td>
    <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
    <td><?= date('d M Y', strtotime($r['tanggal_pengajuan'])) ?></td>
    <td><?= badge_status($r['status']) ?></td>
    <td><a class="btn btn-outline btn-sm" href="detail.php?id=<?= $r['id_pengajuan'] ?>">Detail</a></td>
  </tr>
<?php endwhile; ?>
<?php if ($rows->num_rows===0): ?><tr><td colspan="7" class="empty">Tidak ada data.</td></tr><?php endif; ?>
</tbody></table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
