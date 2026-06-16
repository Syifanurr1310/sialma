<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('mahasiswa');
$uid = $_SESSION['user']['id_user'];
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT p.*, l.nama_layanan FROM pengajuan p JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.id_pengajuan=? AND p.id_user=?");
$stmt->bind_param('ii', $id, $uid);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { flash('error','Pengajuan tidak ditemukan.'); header('Location: riwayat.php'); exit; }
$dok = $conn->query("SELECT * FROM dokumen WHERE id_pengajuan=$id");

$pageTitle = 'Detail Pengajuan #' . $p['id_pengajuan'];
$pageSubtitle = $p['nama_layanan'];
include __DIR__ . '/../includes/header.php';
?>
<div class="grid-2">
  <div class="card">
    <h3>Informasi Pengajuan</h3>
    <p><b>Layanan:</b> <?= htmlspecialchars($p['nama_layanan']) ?></p>
    <p><b>Tanggal:</b> <?= date('d M Y H:i', strtotime($p['tanggal_pengajuan'])) ?></p>
    <p><b>Status:</b> <?= badge_status($p['status']) ?></p>
    <p><b>Keperluan:</b><br><?= nl2br(htmlspecialchars($p['keperluan'])) ?></p>
    <?php if ($p['catatan_admin']): ?><p><b>Catatan Admin:</b><br><?= nl2br(htmlspecialchars($p['catatan_admin'])) ?></p><?php endif; ?>
    <?php if ($p['catatan_dosen']): ?><p><b>Catatan Dosen/Fakultas:</b><br><?= nl2br(htmlspecialchars($p['catatan_dosen'])) ?></p><?php endif; ?>
  </div>
  <div class="card">
    <h3>Dokumen</h3>
    <?php if ($dok->num_rows===0): ?>
      <div class="empty">Tidak ada dokumen.</div>
    <?php else: ?>
      <ul style="padding-left:18px">
      <?php while ($d = $dok->fetch_assoc()): ?>
        <li><a href="<?= base_url($d['file_path']) ?>" target="_blank"><?= htmlspecialchars($d['nama_file']) ?></a> · <small><?= date('d M Y', strtotime($d['tanggal_upload'])) ?></small></li>
      <?php endwhile; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>
<a href="riwayat.php" class="btn btn-outline">← Kembali</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
