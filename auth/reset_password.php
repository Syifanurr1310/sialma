<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$token = $_GET['token'] ?? ($_POST['token'] ?? '');
$error=''; $success='';
$valid=false; $row=null;

if ($token) {
    $hash = hash('sha256',$token);
    $stmt = $conn->prepare('SELECT pr.*, u.username FROM password_resets pr JOIN user u ON u.id_user=pr.id_user WHERE pr.token_hash=? LIMIT 1');
    $stmt->bind_param('s',$hash);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && strtotime($row['expires_at']) > time()) $valid=true;
    else $error='Tautan reset tidak valid atau sudah kedaluwarsa.';
} else { $error='Token tidak ditemukan.'; }

if ($valid && $_SERVER['REQUEST_METHOD']==='POST') {
    $p1 = $_POST['password'] ?? '';
    $p2 = $_POST['password2'] ?? '';
    if (strlen($p1) < 6) $error='Password minimal 6 karakter.';
    elseif ($p1 !== $p2) $error='Konfirmasi password tidak cocok.';
    else {
        $newHash = password_hash($p1, PASSWORD_BCRYPT);
        $stmt = $conn->prepare('UPDATE user SET password=? WHERE id_user=?');
        $stmt->bind_param('si',$newHash,$row['id_user']);
        $stmt->execute();
        $conn->query('DELETE FROM password_resets WHERE id_user='.(int)$row['id_user']);
        $success='Password berhasil diperbarui. Silakan masuk dengan password baru.';
        $valid=false;
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reset Password · SIALMA</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>"></head>
<body>
<div class="auth-shell">
  <div class="auth-hero">
    <div>
      <div style="display:flex;gap:10px;align-items:center;margin-bottom:28px">
        <div class="brand-mark">S</div><strong style="font-size:1.1rem;letter-spacing:.5px">SIALMA</strong>
      </div>
      <h1>Buat password baru untuk akun SIALMA-mu.</h1>
      <p>Gunakan password yang kuat dan mudah kamu ingat. Minimal 6 karakter.</p>
    </div>
  
  </div>
  <div class="auth-form-wrap">
    <div class="auth-card">
      <h2>Atur Password Baru</h2>
      <?php if($error):?><div class="alert alert-danger"><?= htmlspecialchars($error)?></div><?php endif;?>
      <?php if($success):?>
        <div class="alert alert-success"><?= htmlspecialchars($success)?></div>
        <a class="btn btn-primary btn-block" href="<?= base_url('auth/login.php')?>">Ke Halaman Masuk</a>
      <?php elseif($valid):?>
        <form method="post" novalidate>
          <input type="hidden" name="token" value="<?= htmlspecialchars($token)?>">
          <div class="form-group"><label>Password Baru</label><input type="password" name="password" required minlength="6"></div>
          <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="password2" required minlength="6"></div>
          <button class="btn btn-primary btn-block" type="submit">Simpan Password Baru</button>
        </form>
      <?php else:?>
        <p><a href="<?= base_url('auth/forgot_password.php')?>">Minta tautan reset baru</a></p>
      <?php endif;?>
    </div>
  </div>
</div></body></html>
