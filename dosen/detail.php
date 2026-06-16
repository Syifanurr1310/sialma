<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('dosen');
$id=(int)($_GET['id']??0);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $aksi=$_POST['aksi']; $catatan=trim($_POST['catatan_dosen']??'');
    $newStatus = $aksi==='setujui' ? 'Disetujui' : ($aksi==='tolak' ? 'Ditolak' : null);
    if ($newStatus) {
        $stmt=$conn->prepare("UPDATE pengajuan SET status=?, catatan_dosen=? WHERE id_pengajuan=?");
        $stmt->bind_param('ssi',$newStatus,$catatan,$id); $stmt->execute();
        $uid=$conn->query("SELECT id_user FROM pengajuan WHERE id_pengajuan=$id")->fetch_assoc()['id_user'];
        notify($conn,$uid,"Pengajuan #$id $newStatus oleh pihak berwenang.");
        flash('success','Keputusan tersimpan.');
        header('Location: persetujuan.php'); exit;
    }
}
$p=$conn->query("SELECT p.*, u.nama, u.npm, u.prodi, u.email, l.nama_layanan FROM pengajuan p JOIN user u ON u.id_user=p.id_user JOIN jenis_layanan l ON l.id_layanan=p.id_layanan WHERE p.id_pengajuan=$id")->fetch_assoc();
if (!$p) { flash('error','Tidak ditemukan'); header('Location: persetujuan.php'); exit; }
$dok=$conn->query("SELECT * FROM dokumen WHERE id_pengajuan=$id");
$pageTitle='Tinjau Pengajuan #'.$p['id_pengajuan'];
include __DIR__ . '/../includes/header.php';
?>
<div class="grid-2">
<div class="card">
<h3>Detail</h3>
<p><b>Mahasiswa:</b> <?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['npm']) ?>)</p>
<p><b>Prodi:</b> <?= htmlspecialchars($p['prodi']) ?></p>
<p><b>Layanan:</b> <?= htmlspecialchars($p['nama_layanan']) ?></p>
<p><b>Status:</b> <?= badge_status($p['status']) ?></p>
<p><b>Keperluan:</b><br><?= nl2br(htmlspecialchars($p['keperluan'])) ?></p>
<?php if($p['catatan_admin']): ?><p><b>Catatan Admin:</b><br><?= nl2br(htmlspecialchars($p['catatan_admin'])) ?></p><?php endif; ?>
<h3 style="margin-top:18px">Dokumen</h3>
<?php if($dok->num_rows===0): ?><div class="empty">—</div><?php else: ?>
<ul style="padding-left:18px"><?php while($d=$dok->fetch_assoc()): ?><li><a target="_blank" href="<?= base_url($d['file_path']) ?>"><?= htmlspecialchars($d['nama_file']) ?></a></li><?php endwhile; ?></ul>
<?php endif; ?>
</div>
<div class="card">
<h3>Keputusan</h3>
<form method="post">
<div class="form-group"><label>Catatan</label><textarea name="catatan_dosen"><?= htmlspecialchars($p['catatan_dosen']) ?></textarea></div>
<div class="actions">
<button name="aksi" value="setujui" class="btn btn-success">Setujui</button>
<button name="aksi" value="tolak" class="btn btn-danger" data-confirm="Yakin menolak?">Tolak</button>
</div>
</form>
</div>
</div>
<a href="persetujuan.php" class="btn btn-outline">← Kembali</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
