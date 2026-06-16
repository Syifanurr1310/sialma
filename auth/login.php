<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (isset($_SESSION['user'])) {
    header('Location: ' . base_url($_SESSION['user']['role'] . '/dashboard.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT * FROM user WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res && password_verify($password, $res['password'])) {
        unset($res['password']);
        $_SESSION['user'] = $res;
        header('Location: ' . base_url($res['role'] . '/dashboard.php'));
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk · SIALMA</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="auth-shell">
  <div class="auth-hero">
    <div>
      <div style="display:flex;gap:10px;align-items:center;margin-bottom:28px">
        <div class="brand-mark">S</div>
        <strong style="font-size:1.1rem;letter-spacing:.5px">SIALMA</strong>
      </div>
      <h1>Layanan Administrasi Mahasiswa, kini sepenuhnya digital.</h1>
      <p>Ajukan surat keterangan, izin penelitian, dan legalisasi dokumen tanpa harus datang ke kampus. Pantau status pengajuan secara real-time.</p>
      <ul class="hero-features">
        <li>Pengajuan online 24/7 dengan unggah dokumen pendukung</li>
        <li>Verifikasi oleh admin akademik dan persetujuan dosen/fakultas</li>
        <li>Notifikasi otomatis untuk setiap perubahan status</li>
      </ul>
    </div>
  </div>
  <div class="auth-form-wrap">
    <div class="auth-card">
      <h2>Selamat datang kembali</h2>
      <p class="lead">Masuk menggunakan akun yang terdaftar.</p>
      <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post" novalidate>
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" required autofocus>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Masuk</button>
      </form>
      <p style="margin-top:14px;font-size:.88rem;color:var(--muted)"><a href="<?= base_url('auth/forgot_password.php') ?>" style="float:right;font-size:.85rem">Lupa password?</a>Belum punya akun mahasiswa? <a href="<?= base_url('auth/register.php') ?>"><b>Daftar di sini</b></a></p>
    </div>
  </div>
</div>
</body>
</html>
