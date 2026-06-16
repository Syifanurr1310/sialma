<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$info = ''; $error=''; $resetLink='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $ident = trim($_POST['identifier'] ?? '');
    if ($ident==='') { $error='Masukkan username atau email.'; }
    else {
        $stmt = $conn->prepare('SELECT id_user, nama, email, username FROM user WHERE username=? OR email=? LIMIT 1');
        $stmt->bind_param('ss',$ident,$ident);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();
        if ($u) {
            $token = bin2hex(random_bytes(32));
            $hash = hash('sha256',$token);
            $expires = date('Y-m-d H:i:s', time()+3600);
            $conn->query("DELETE FROM password_resets WHERE id_user=".(int)$u['id_user']);
            $stmt2 = $conn->prepare('INSERT INTO password_resets (id_user, token_hash, expires_at) VALUES (?,?,?)');
            $stmt2->bind_param('iss',$u['id_user'],$hash,$expires);
            $stmt2->execute();
            $base = (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off'?'https':'http').'://'.$_SERVER['HTTP_HOST'].base_url('auth/reset_password.php?token='.$token);
            $resetLink = $base;
            $info = 'Tautan reset password berhasil dibuat. Berlaku 1 jam.';
        } else { $error='Akun tidak ditemukan.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lupa Password · SIALMA</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>"></head>
<body>
<div class="auth-shell">
  <div class="auth-hero">
    <div>
      <div style="display:flex;gap:10px;align-items:center;margin-bottom:28px">
        <div class="brand-mark">S</div><strong style="font-size:1.1rem;letter-spacing:.5px">SIALMA</strong>
      </div>
      <h1>Lupa password? Tenang, kami bantu pulihkan akses akunmu.</h1>
      <p>Masukkan username atau email akun SIALMA-mu. Sistem akan membuat tautan reset password yang berlaku selama 1 jam.</p>
    </div>
    
  </div>
  <div class="auth-form-wrap">
    <div class="auth-card">
      <h2>Reset Password</h2>
      <p class="lead">Pulihkan akses akun SIALMA-mu.</p>
      <?php if($error):?><div class="alert alert-danger"><?= htmlspecialchars($error)?></div><?php endif;?>
      <?php if($info):?><div class="alert alert-success"><?= htmlspecialchars($info)?></div><?php endif;?>
      <?php if($resetLink):?>
        <div class="alert alert-info" style="word-break:break-all">
          <b>Tautan Reset:</b><br>
          <a href="<?= htmlspecialchars($resetLink)?>"><?= htmlspecialchars($resetLink)?></a>
          
        </div>
      <?php endif;?>
      <form method="post" novalidate>
        <div class="form-group">
          <label>Username atau Email</label>
          <input type="text" name="identifier" required autofocus>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Kirim Tautan Reset</button>
      </form>
      <p style="margin-top:14px;font-size:.88rem;color:var(--muted)">Ingat password? <a href="<?= base_url('auth/login.php')?>"><b>Kembali ke Masuk</b></a></p>
    </div>
  </div>
</div></body></html>
