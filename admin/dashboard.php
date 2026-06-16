<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

$stats = ['total'=>0,'menunggu'=>0,'diverifikasi'=>0,'disetujui'=>0,'ditolak'=>0,'mhs'=>0];
$stats['total'] = $conn->query("SELECT COUNT(*) c FROM pengajuan")->fetch_assoc()['c'];
$stats['menunggu'] = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status='Menunggu Verifikasi'")->fetch_assoc()['c'];
$stats['diverifikasi'] = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status='Diverifikasi'")->fetch_assoc()['c'];
$stats['disetujui'] = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status IN ('Disetujui','Selesai')")->fetch_assoc()['c'];
$stats['ditolak']  = $conn->query("SELECT COUNT(*) c FROM pengajuan WHERE status='Ditolak'")->fetch_assoc()['c'];
$stats['mhs']      = $conn->query("SELECT COUNT(*) c FROM user WHERE role='mahasiswa'")->fetch_assoc()['c'];

$recent = $conn->query("SELECT p.*, u.nama AS nama_mhs, l.nama_layanan FROM pengajuan p JOIN user u ON u.id_user=p.id_user JOIN jenis_layanan l ON l.id_layanan=p.id_layanan ORDER BY p.tanggal_pengajuan DESC LIMIT 6");

$pageTitle='Dashboard Admin';
$pageSubtitle='Ringkasan layanan administrasi mahasiswa.';
include __DIR__ . '/../includes/header.php';
?>
<div class="stats">
  <div class="stat"><div class="label">Total Pengajuan</div><div class="value"><?= $stats['total'] ?></div></div>
  <div class="stat"><div class="label">Menunggu Verifikasi</div><div class="value"><?= $stats['menunggu'] ?></div></div>
  <div class="stat"><div class="label">Diverifikasi</div><div class="value"><?= $stats['diverifikasi'] ?></div></div>
  <div class="stat"><div class="label">Mahasiswa Terdaftar</div><div class="value"><?= $stats['mhs'] ?></div></div>
</div>
<div class="card">
  <div class="card-head"><h3>Pengajuan Terbaru</h3><a class="btn btn-outline btn-sm" href="pengajuan.php">Lihat semua →</a></div>
  <table class="tbl"><thead><tr><th>#</th><th>Mahasiswa</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th></th></tr></thead><tbody>
  <?php while($r=$recent->fetch_assoc()): ?>
    <tr>
      <td>#<?= $r['id_pengajuan'] ?></td>
      <td><?= htmlspecialchars($r['nama_mhs']) ?></td>
      <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
      <td><?= date('d M Y', strtotime($r['tanggal_pengajuan'])) ?></td>
      <td><?= badge_status($r['status']) ?></td>
      <td><a class="btn btn-outline btn-sm" href="detail.php?id=<?= $r['id_pengajuan'] ?>">Proses</a></td>
    </tr>
  <?php endwhile; ?>
  </tbody></table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
