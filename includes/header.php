<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? 'guest';
$panelTitle = [
    'mahasiswa' => 'Portal Mahasiswa',
    'admin'     => 'Panel Admin Akademik',
    'dosen'     => 'Panel Pihak Berwenang',
][$role] ?? 'SIALMA';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'SIALMA') ?> · SIALMA</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="app">
<aside class="sidebar">
  <div class="brand">
    <div class="brand-mark">S</div>
    <div>
      <div class="brand-name">SIALMA</div>
      <div class="brand-sub"><?= htmlspecialchars($panelTitle) ?></div>
    </div>
  </div>
  <nav class="nav">
    <?php if ($role === 'mahasiswa'): ?>
      <a href="<?= base_url('mahasiswa/dashboard.php') ?>">Dashboard</a>
      <a href="<?= base_url('mahasiswa/ajukan.php') ?>">Ajukan Layanan</a>
      <a href="<?= base_url('mahasiswa/riwayat.php') ?>">Riwayat Pengajuan</a>
      <a href="<?= base_url('mahasiswa/notifikasi.php') ?>">Notifikasi</a>
      <a href="<?= base_url('mahasiswa/profil.php') ?>">Profil</a>
    <?php elseif ($role === 'admin'): ?>
      <a href="<?= base_url('admin/dashboard.php') ?>">Dashboard</a>
      <a href="<?= base_url('admin/pengajuan.php') ?>">Kelola Pengajuan</a>
      <a href="<?= base_url('admin/layanan.php') ?>">Jenis Layanan</a>
      <a href="<?= base_url('admin/pengguna.php') ?>">Data Pengguna</a>
    <?php elseif ($role === 'dosen'): ?>
      <a href="<?= base_url('dosen/dashboard.php') ?>">Dashboard</a>
      <a href="<?= base_url('dosen/persetujuan.php') ?>">Persetujuan</a>
      <a href="<?= base_url('dosen/laporan.php') ?>">Laporan</a>
    <?php endif; ?>
  </nav>
  <div class="sidebar-foot">
    <div class="user-card">
      <div class="avatar"><?= strtoupper(substr($user['nama'] ?? '?', 0, 1)) ?></div>
      <div>
        <div class="user-name"><?= htmlspecialchars($user['nama'] ?? 'Tamu') ?></div>
        <div class="user-role"><?= htmlspecialchars(ucfirst($role)) ?></div>
      </div>
    </div>
    <a class="btn btn-ghost btn-block" href="<?= base_url('auth/logout.php') ?>">Keluar</a>
  </div>
</aside>
<main class="main">
<header class="topbar">
  <div>
    <h1 class="page-title"><?= htmlspecialchars($pageTitle ?? '') ?></h1>
    <?php if (!empty($pageSubtitle)): ?><p class="page-sub"><?= htmlspecialchars($pageSubtitle) ?></p><?php endif; ?>
  </div>
  <div class="topbar-right">
   
  </div>
</header>
<section class="content">
<?php if ($m = flash('success')): ?><div class="alert alert-success"><?= htmlspecialchars($m) ?></div><?php endif; ?>
<?php if ($m = flash('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($m) ?></div><?php endif; ?>
