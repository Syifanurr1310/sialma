<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
if (isset($_SESSION['user'])) {
    $r = $_SESSION['user']['role'];
    header('Location: ' . base_url($r . '/dashboard.php'));
    exit;
}
header('Location: ' . base_url('auth/login.php'));
exit;
