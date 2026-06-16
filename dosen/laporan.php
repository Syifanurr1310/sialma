<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('dosen');

$byStatus = $conn->query("SELECT status, COUNT(*) c FROM pengajuan GROUP BY status");
$byLayanan = $conn->query("SELECT l.nama_layanan, COUNT(p.id_pengajuan) c FROM jenis_layanan l LEFT JOIN pengajuan p ON p.id_layanan=l.id_layanan GROUP BY l.id_layanan ORDER BY c DESC");
$byBulan = $conn->query("SELECT DATE_FORMAT(tanggal_pengajuan,'%Y-%m') bln, COUNT(*) c FROM pengajuan GROUP BY bln ORDER BY bln DESC LIMIT 12");
$pageTitle='Laporan Pengajuan';
include __DIR__ . '/../includes/header.php';
?>
<div class="grid-2">
<div class="card"><h3>Per Status</h3>
<table class="tbl"><thead><tr><th>Status</th><th>Jumlah</th></tr></thead><tbody>
<?php while($r=$byStatus->fetch_assoc()): ?><tr><td><?= badge_status($r['status']) ?></td><td><b><?= $r['c'] ?></b></td></tr><?php endwhile; ?>
</tbody></table></div>
<div class="card"><h3>Per Jenis Layanan</h3>
<table class="tbl"><thead><tr><th>Layanan</th><th>Jumlah</th></tr></thead><tbody>
<?php while($r=$byLayanan->fetch_assoc()): ?><tr><td><?= htmlspecialchars($r['nama_layanan']) ?></td><td><b><?= $r['c'] ?></b></td></tr><?php endwhile; ?>
</tbody></table></div>
</div>
<div class="card"><h3>Per Bulan</h3>
<table class="tbl"><thead><tr><th>Bulan</th><th>Jumlah Pengajuan</th></tr></thead><tbody>
<?php while($r=$byBulan->fetch_assoc()): ?><tr><td><?= htmlspecialchars($r['bln']) ?></td><td><b><?= $r['c'] ?></b></td></tr><?php endwhile; ?>
</tbody></table></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
