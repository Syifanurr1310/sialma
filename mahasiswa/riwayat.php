<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('mahasiswa');
$uid = $_SESSION['user']['id_user'];
$rows = $conn->query("SELECT p.*, l.nama_layanan, (SELECT COUNT(*) FROM dokumen d WHERE d.id_pengajuan=p.id_pengajuan) AS jml_dok FROM pengajuan p JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.id_user=$uid ORDER BY p.tanggal_pengajuan DESC");
$pageTitle = 'Riwayat Pengajuan';
$pageSubtitle = 'Daftar seluruh pengajuan yang pernah Anda buat.';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <div class="card-head"><h3>Semua Pengajuan</h3><a class="btn btn-primary btn-sm" href="ajukan.php">+ Ajukan Baru</a></div>
  <?php if ($rows->num_rows===0): ?>
    <div class="empty">Belum ada riwayat pengajuan.</div>
  <?php else: ?>
    <table class="tbl"><thead><tr><th>#</th><th>Layanan</th><th>Keperluan</th><th>Dok</th><th>Tanggal</th><th>Status</th><th></th></tr></thead><tbody>
    <?php while ($r = $rows->fetch_assoc()): ?>
      <tr>
        <td>#<?= $r['id_pengajuan'] ?></td>
        <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
        <td style="max-width:280px"><?= htmlspecialchars(mb_strimwidth($r['keperluan'],0,80,'…')) ?></td>
        <td><?= $r['jml_dok'] ?></td>
        <td><?= date('d M Y', strtotime($r['tanggal_pengajuan'])) ?></td>
        <td><?= badge_status($r['status']) ?></td>
        <td><a class="btn btn-outline btn-sm" href="detail.php?id=<?= $r['id_pengajuan'] ?>">Detail</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody></table>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
