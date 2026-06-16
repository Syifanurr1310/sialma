<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('mahasiswa');
$uid = $_SESSION['user']['id_user'];

$stats = ['total'=>0,'menunggu'=>0,'disetujui'=>0,'ditolak'=>0];
$q = $conn->query("SELECT status, COUNT(*) c FROM pengajuan WHERE id_user=$uid GROUP BY status");
while ($r = $q->fetch_assoc()) {
    $stats['total'] += $r['c'];
    if ($r['status']==='Menunggu Verifikasi' || $r['status']==='Diverifikasi') $stats['menunggu'] += $r['c'];
    if ($r['status']==='Disetujui' || $r['status']==='Selesai') $stats['disetujui'] += $r['c'];
    if ($r['status']==='Ditolak') $stats['ditolak'] += $r['c'];
}

$recent = $conn->query("SELECT p.*, l.nama_layanan FROM pengajuan p JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.id_user=$uid ORDER BY p.tanggal_pengajuan DESC LIMIT 5");
$notifs = $conn->query("SELECT * FROM notifikasi WHERE id_user=$uid ORDER BY created_at DESC LIMIT 5");

$pageTitle = 'Dashboard Mahasiswa';
$pageSubtitle = 'Ringkasan pengajuan layanan administrasi Anda.';
include __DIR__ . '/../includes/header.php';
?>
<div class="stats">
  <div class="stat"><div class="label">Total Pengajuan</div><div class="value"><?= $stats['total'] ?></div><div class="delta">Seluruh riwayat</div></div>
  <div class="stat"><div class="label">Sedang Diproses</div><div class="value"><?= $stats['menunggu'] ?></div><div class="delta">Menunggu / Diverifikasi</div></div>
  <div class="stat"><div class="label">Disetujui</div><div class="value"><?= $stats['disetujui'] ?></div><div class="delta">Sudah selesai</div></div>
  <div class="stat"><div class="label">Ditolak</div><div class="value"><?= $stats['ditolak'] ?></div><div class="delta">Perlu diajukan ulang</div></div>
</div>
<div class="grid-2">
  <div class="card">
    <div class="card-head"><h3>Pengajuan Terbaru</h3><a class="btn btn-outline btn-sm" href="ajukan.php">+ Ajukan Baru</a></div>
    <?php if ($recent->num_rows === 0): ?>
      <div class="empty">Belum ada pengajuan. Mulai dengan menekan tombol "Ajukan Baru".</div>
    <?php else: ?>
      <table class="tbl"><thead><tr><th>Layanan</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
      <?php while ($r = $recent->fetch_assoc()): ?>
        <tr><td><?= htmlspecialchars($r['nama_layanan']) ?></td>
            <td><?= date('d M Y', strtotime($r['tanggal_pengajuan'])) ?></td>
            <td><?= badge_status($r['status']) ?></td></tr>
      <?php endwhile; ?>
      </tbody></table>
    <?php endif; ?>
  </div>
  <div class="card">
    <div class="card-head"><h3>Notifikasi Terbaru</h3></div>
    <?php if ($notifs->num_rows === 0): ?>
      <div class="empty">Tidak ada notifikasi.</div>
    <?php else: ?>
      <div class="timeline">
        <?php while ($n = $notifs->fetch_assoc()): ?>
          <div class="tl-item"><div class="tl-dot"></div>
            <div class="tl-body"><?= htmlspecialchars($n['pesan']) ?><small><?= date('d M Y H:i', strtotime($n['created_at'])) ?></small></div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
