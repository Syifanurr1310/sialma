<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('dosen');
$menunggu = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status='Diverifikasi'")->fetch_assoc()['c'];
$disetujui = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status IN ('Disetujui','Selesai')")->fetch_assoc()['c'];
$ditolak = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status='Ditolak'")->fetch_assoc()['c'];
$total = $conn->query("SELECT COUNT(*) c FROM pengajuan")->fetch_assoc()['c'];
$pageTitle='Dashboard Pihak Berwenang';
$pageSubtitle='Pengajuan yang telah lolos verifikasi admin menunggu persetujuan Anda.';
include __DIR__ . '/../includes/header.php';
?>
<div class="stats">
  <div class="stat"><div class="label">Menunggu Persetujuan</div><div class="value"><?= $menunggu ?></div></div>
  <div class="stat"><div class="label">Telah Disetujui</div><div class="value"><?= $disetujui ?></div></div>
  <div class="stat"><div class="label">Telah Ditolak</div><div class="value"><?= $ditolak ?></div></div>
  <div class="stat"><div class="label">Total Pengajuan</div><div class="value"><?= $total ?></div></div>
</div>
<div class="card">
<div class="card-head"><h3>Antrian Persetujuan</h3><a class="btn btn-outline btn-sm" href="persetujuan.php">Buka semua →</a></div>
<?php $q=$conn->query("SELECT p.*, u.nama, l.nama_layanan FROM pengajuan p JOIN user u ON u.id_user=p.id_user JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.status='Diverifikasi' ORDER BY p.tanggal_update DESC LIMIT 6"); ?>
<table class="tbl"><thead><tr><th>#</th><th>Mahasiswa</th><th>Layanan</th><th>Tanggal</th><th></th></tr></thead><tbody>
<?php while($r=$q->fetch_assoc()): ?>
<tr><td>#<?= $r['id_pengajuan'] ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['nama_layanan']) ?></td><td><?= date('d M Y', strtotime($r['tanggal_update'])) ?></td><td><a class="btn btn-primary btn-sm" href="detail.php?id=<?= $r['id_pengajuan'] ?>">Tinjau</a></td></tr>
<?php endwhile; ?>
<?php if($q->num_rows===0): ?><tr><td colspan="5" class="empty">Tidak ada antrian.</td></tr><?php endif; ?>
</tbody></table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
