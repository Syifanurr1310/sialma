<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['tambah'])) {
        $stmt=$conn->prepare("INSERT INTO jenis_layanan (nama_layanan, deskripsi) VALUES (?,?)");
        $stmt->bind_param('ss', $_POST['nama_layanan'], $_POST['deskripsi']);
        $stmt->execute();
        flash('success','Layanan ditambahkan.');
    } elseif (isset($_POST['hapus'])) {
        $id=(int)$_POST['id']; $conn->query("DELETE FROM jenis_layanan WHERE id_layanan=$id");
        flash('success','Layanan dihapus.');
    } elseif (isset($_POST['toggle'])) {
        $id=(int)$_POST['id']; $conn->query("UPDATE jenis_layanan SET aktif=1-aktif WHERE id_layanan=$id");
    }
    header('Location: layanan.php'); exit;
}
$rows=$conn->query("SELECT * FROM jenis_layanan ORDER BY id_layanan");
$pageTitle='Jenis Layanan';
include __DIR__ . '/../includes/header.php';
?>
<div class="grid-2">
<div class="card">
<h3>Daftar Layanan</h3>
<table class="tbl"><thead><tr><th>Nama</th><th>Status</th><th></th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()): ?>
<tr>
  <td><b><?= htmlspecialchars($r['nama_layanan']) ?></b><br><small style="color:var(--muted)"><?= htmlspecialchars($r['deskripsi']) ?></small></td>
  <td><?= $r['aktif']?'<span class="badge badge-success">Aktif</span>':'<span class="badge badge-danger">Nonaktif</span>' ?></td>
  <td>
    <form method="post" style="display:inline"><input type="hidden" name="id" value="<?= $r['id_layanan'] ?>"><button class="btn btn-outline btn-sm" name="toggle" value="1">Toggle</button></form>
    <form method="post" style="display:inline"><input type="hidden" name="id" value="<?= $r['id_layanan'] ?>"><button class="btn btn-danger btn-sm" name="hapus" value="1" data-confirm="Hapus layanan ini?">Hapus</button></form>
  </td>
</tr>
<?php endwhile; ?>
</tbody></table>
</div>
<div class="card">
<h3>Tambah Layanan</h3>
<form method="post">
  <div class="form-group"><label>Nama Layanan</label><input name="nama_layanan" required></div>
  <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi"></textarea></div>
  <button name="tambah" value="1" class="btn btn-primary">Tambah</button>
</form>
</div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
