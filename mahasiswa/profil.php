<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('mahasiswa');
$uid = $_SESSION['user']['id_user'];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nama=trim($_POST['nama']); $email=trim($_POST['email']); $npm=trim($_POST['npm']); $prodi=trim($_POST['prodi']); $hp=trim($_POST['no_hp']);
    $stmt=$conn->prepare("UPDATE user SET nama=?,email=?,npm=?,prodi=?,no_hp=? WHERE id_user=?");
    $stmt->bind_param('sssssi',$nama,$email,$npm,$prodi,$hp,$uid);
    $stmt->execute();
    if (!empty($_POST['password'])) {
        $h = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $conn->query("UPDATE user SET password='".$conn->real_escape_string($h)."' WHERE id_user=$uid");
    }
    $_SESSION['user'] = $conn->query("SELECT id_user,nama,username,email,npm,prodi,no_hp,role FROM user WHERE id_user=$uid")->fetch_assoc();
    flash('success','Profil diperbarui.');
    header('Location: profil.php'); exit;
}
$u = $conn->query("SELECT * FROM user WHERE id_user=$uid")->fetch_assoc();
$pageTitle='Profil Saya';
include __DIR__ . '/../includes/header.php';
?>
<div class="card" style="max-width:720px">
<form method="post">
  <div class="form-row">
    <div class="form-group"><label>Nama</label><input name="nama" value="<?= htmlspecialchars($u['nama']) ?>" required></div>
    <div class="form-group"><label>Username</label><input value="<?= htmlspecialchars($u['username']) ?>" disabled></div>
  </div>
  <div class="form-row">
    <div class="form-group"><label>NPM</label><input name="npm" value="<?= htmlspecialchars($u['npm']) ?>"></div>
    <div class="form-group"><label>Prodi</label><input name="prodi" value="<?= htmlspecialchars($u['prodi']) ?>"></div>
  </div>
  <div class="form-row">
    <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>"></div>
    <div class="form-group"><label>No. HP</label><input name="no_hp" value="<?= htmlspecialchars($u['no_hp']) ?>"></div>
  </div>
  <div class="form-group"><label>Password Baru (opsional)</label><input type="password" name="password" placeholder="Kosongkan jika tidak diganti"></div>
  <button class="btn btn-primary">Simpan Perubahan</button>
</form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
