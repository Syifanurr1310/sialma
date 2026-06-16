<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('dosen');
$rows=$conn->query("SELECT p.*, u.nama, u.npm, l.nama_layanan FROM pengajuan p JOIN user u ON u.id_user=p.id_user JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.status IN ('Diverifikasi','Disetujui','Ditolak','Selesai') ORDER BY FIELD(p.status,'Diverifikasi','Disetujui','Selesai','Ditolak'), p.tanggal_update DESC");
$pageTitle='Persetujuan Pengajuan';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
<table class="tbl"><thead><tr><th>#</th><th>Mahasiswa</th><th>NPM</th><th>Layanan</th><th>Status</th><th></th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()): ?>
<tr><td>#<?= $r['id_pengajuan'] ?></td><td><?= htmlspecialchars($r['nama']) ?></td><td><?= htmlspecialchars($r['npm']) ?></td><td><?= htmlspecialchars($r['nama_layanan']) ?></td><td><?= badge_status($r['status']) ?></td><td><a class="btn btn-outline btn-sm" href="detail.php?id=<?= $r['id_pengajuan'] ?>">Tinjau</a></td></tr>
<?php endwhile; ?>
<?php if($rows->num_rows===0): ?><tr><td colspan="6" class="empty">Belum ada pengajuan untuk ditinjau.</td></tr><?php endif; ?>
</tbody></table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
