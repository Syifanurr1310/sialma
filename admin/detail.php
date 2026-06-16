<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $aksi = $_POST['aksi'];
    $catatan = trim($_POST['catatan_admin'] ?? '');
    $newStatus = $aksi==='verifikasi' ? 'Diverifikasi' : ($aksi==='tolak' ? 'Ditolak' : ($aksi==='selesai' ? 'Selesai' : null));
    if ($newStatus) {
        $stmt = $conn->prepare("UPDATE pengajuan SET status=?, catatan_admin=? WHERE id_pengajuan=?");
        $stmt->bind_param('ssi', $newStatus, $catatan, $id);
        $stmt->execute();
        $uid = $conn->query("SELECT id_user FROM pengajuan WHERE id_pengajuan=$id")->fetch_assoc()['id_user'];
        notify($conn, $uid, "Pengajuan #$id diperbarui: $newStatus");
        if ($newStatus==='Diverifikasi') {
            // notify dosen
            $dosen = $conn->query("SELECT id_user FROM user WHERE role='dosen'");
            while ($d = $dosen->fetch_assoc()) notify($conn, $d['id_user'], "Pengajuan #$id telah diverifikasi admin dan menunggu persetujuan Anda.");
        }
        flash('success','Status pengajuan diperbarui.');
        header('Location: detail.php?id='.$id); exit;
    }
}

$p = $conn->query("SELECT p.*, u.nama AS nama_mhs, u.npm, u.email, u.prodi, l.nama_layanan FROM pengajuan p JOIN user u ON u.id_user=p.id_user JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.id_pengajuan=$id")->fetch_assoc();
if (!$p) { flash('error','Tidak ditemukan'); header('Location: pengajuan.php'); exit; }
$dok = $conn->query("SELECT * FROM dokumen WHERE id_pengajuan=$id");

$pageTitle='Detail Pengajuan #'.$p['id_pengajuan'];
$pageSubtitle=$p['nama_layanan'];
include __DIR__ . '/../includes/header.php';
?>
<div class="grid-2">
  <div class="card">
    <h3>Informasi Mahasiswa</h3>
    <p><b>Nama:</b> <?= htmlspecialchars($p['nama_mhs']) ?></p>
    <p><b>NPM:</b> <?= htmlspecialchars($p['npm']) ?></p>
    <p><b>Prodi:</b> <?= htmlspecialchars($p['prodi']) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($p['email']) ?></p>
    <hr style="border:0;border-top:1px solid var(--border);margin:14px 0">
    <h3>Pengajuan</h3>
    <p><b>Layanan:</b> <?= htmlspecialchars($p['nama_layanan']) ?></p>
    <p><b>Tanggal:</b> <?= date('d M Y H:i', strtotime($p['tanggal_pengajuan'])) ?></p>
    <p><b>Status:</b> <?= badge_status($p['status']) ?></p>
    <p><b>Keperluan:</b><br><?= nl2br(htmlspecialchars($p['keperluan'])) ?></p>
  </div>
  <div class="card">
    <h3>Dokumen Pendukung</h3>
    <?php if ($dok->num_rows===0): ?><div class="empty">Tidak ada dokumen.</div><?php else: ?>
      <ul style="padding-left:18px"><?php while($d=$dok->fetch_assoc()): ?>
        <li><a target="_blank" href="<?= base_url($d['file_path']) ?>"><?= htmlspecialchars($d['nama_file']) ?></a></li>
      <?php endwhile; ?></ul>
    <?php endif; ?>
    <hr style="border:0;border-top:1px solid var(--border);margin:14px 0">
    <h3>Tindakan</h3>
    <form method="post">
      <div class="form-group"><label>Catatan Admin</label><textarea name="catatan_admin"><?= htmlspecialchars($p['catatan_admin']) ?></textarea></div>
      <div class="actions">
        <button name="aksi" value="verifikasi" class="btn btn-primary">Verifikasi & Teruskan</button>
        <button name="aksi" value="tolak" class="btn btn-danger" data-confirm="Yakin menolak pengajuan ini?">Tolak</button>
        <button name="aksi" value="selesai" class="btn btn-success">Tandai Selesai</button>
      </div>
    </form>
  </div>
</div>
<a href="pengajuan.php" class="btn btn-outline">← Kembali</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
