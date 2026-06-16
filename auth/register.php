<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $npm = trim($_POST['npm']);
    $prodi = trim($_POST['prodi']);
    $password = $_POST['password'];
    if (!$nama || !$username || !$password) {
        $error = 'Semua field wajib diisi.';
    } else {
        $check = $conn->prepare('SELECT id_user FROM user WHERE username = ?');
        $check->bind_param('s', $username);
        $check->execute();
        if ($check->get_result()->fetch_assoc()) {
            $error = 'Username sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO user (nama, username, password, email, npm, prodi, role) VALUES (?,?,?,?,?,?, 'mahasiswa')");
            $stmt->bind_param('ssssss', $nama, $username, $hash, $email, $npm, $prodi);
            $stmt->execute();
            flash('success', 'Pendaftaran berhasil. Silakan masuk.');
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar · SIALMA</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="auth-shell">
  <div class="auth-hero">
    <div>
      <div style="display:flex;gap:10px;align-items:center;margin-bottom:28px">
        <div class="brand-mark">S</div><strong>SIALMA</strong>
      </div>
      <h1>Daftar akun mahasiswa baru.</h1>
      <p>Buat akun untuk mulai mengajukan layanan administrasi secara online.</p>
    </div>
    
  </div>
  <div class="auth-form-wrap">
    <div class="auth-card">
      <h2>Pendaftaran Mahasiswa</h2>
      <p class="lead">Lengkapi data berikut untuk membuat akun.</p>
      <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <div class="form-group"><label>Nama Lengkap</label><input name="nama" required></div>
        <div class="form-row">
          <div class="form-group"><label>NPM</label><input name="npm"></div>
          <div class="form-group"><label>Program Studi</label><input name="prodi"></div>
        </div>
        <div class="form-group"><label>Email</label><input type="email" name="email"></div>
        <div class="form-row">
          <div class="form-group"><label>Username</label><input name="username" required></div>
          <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        </div>
        <button class="btn btn-primary btn-block">Daftar Sekarang</button>
      </form>
      <p style="margin-top:14px;font-size:.88rem;color:var(--muted)">Sudah punya akun? <a href="login.php"><b>Masuk</b></a></p>
    </div>
  </div>
</div>
</body>
</html>
