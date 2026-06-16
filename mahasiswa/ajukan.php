<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('mahasiswa');
$uid = $_SESSION['user']['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_layanan = (int)$_POST['id_layanan'];
    $keperluan  = trim($_POST['keperluan']);
    if ($id_layanan && $keperluan) {
        $stmt = $conn->prepare("INSERT INTO pengajuan (id_user, id_layanan, keperluan) VALUES (?,?,?)");
        $stmt->bind_param('iis', $uid, $id_layanan, $keperluan);
        $stmt->execute();
        $id_pengajuan = $stmt->insert_id;

        // Upload dokumen
        if (!empty($_FILES['dokumen']['name'][0])) {
            $upDir = __DIR__ . '/../uploads/';
            if (!is_dir($upDir)) mkdir($upDir, 0777, true);
            foreach ($_FILES['dokumen']['name'] as $i => $name) {
                if ($_FILES['dokumen']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $safe = time() . '_' . $i . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
                $dest = $upDir . $safe;
                if (move_uploaded_file($_FILES['dokumen']['tmp_name'][$i], $dest)) {
                    $rel = 'uploads/' . $safe;
                    $d = $conn->prepare("INSERT INTO dokumen (id_pengajuan, nama_file, file_path) VALUES (?,?,?)");
                    $d->bind_param('iss', $id_pengajuan, $name, $rel);
                    $d->execute();
                }
            }
        }
        // Notifikasi ke semua admin
        $admins = $conn->query("SELECT id_user FROM user WHERE role='admin'");
        while ($a = $admins->fetch_assoc()) notify($conn, $a['id_user'], "Pengajuan baru #$id_pengajuan dari " . $_SESSION['user']['nama']);
        notify($conn, $uid, "Pengajuan #$id_pengajuan berhasil dikirim dan menunggu verifikasi.");

        flash('success', 'Pengajuan berhasil dikirim.');
        header('Location: riwayat.php');
        exit;
    } else {
        flash('error', 'Lengkapi layanan dan keperluan.');
    }
}

$layanan = $conn->query("SELECT * FROM jenis_layanan WHERE aktif=1 ORDER BY nama_layanan");

$pageTitle = 'Ajukan Layanan';
$pageSubtitle = 'Isi formulir di bawah untuk mengajukan layanan administrasi.';
include __DIR__ . '/../includes/header.php';
?>
<div class="card" style="max-width:780px">
  <form method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Jenis Layanan</label>
      <select name="id_layanan" required>
        <option value="">— Pilih layanan —</option>
        <?php while ($l = $layanan->fetch_assoc()): ?>
          <option value="<?= $l['id_layanan'] ?>"><?= htmlspecialchars($l['nama_layanan']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Keperluan / Deskripsi</label>
      <textarea name="keperluan" placeholder="Contoh: Untuk keperluan pengajuan beasiswa PPA semester ganjil 2026" required></textarea>
    </div>
    <div class="form-group">
      <label>Dokumen Pendukung (boleh lebih dari satu)</label>
      <input type="file" name="dokumen[]" multiple>
      <small style="color:var(--muted)">Format yang disarankan: PDF, JPG, PNG. Maks. sesuai konfigurasi server.</small>
    </div>
    <div class="actions" style="justify-content:flex-end">
      <a href="dashboard.php" class="btn btn-outline">Batal</a>
      <button class="btn btn-primary">Kirim Pengajuan</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
