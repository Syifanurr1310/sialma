<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login($role = null) {
    if (!isset($_SESSION['user'])) {
        header('Location: ' . base_url('auth/login.php'));
        exit;
    }
    if ($role && $_SESSION['user']['role'] !== $role) {
        header('Location: ' . base_url('auth/login.php'));
        exit;
    }
}

function base_url($path = '') {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // naik ke root project
    $parts = explode('/', trim($scriptDir, '/'));
    // root project: hilangkan satu subfolder (mahasiswa/admin/dosen/auth)
    $roots = ['mahasiswa','admin','dosen','auth'];
    while (!empty($parts) && in_array(end($parts), $roots)) array_pop($parts);
    $base = '/' . implode('/', $parts);
    $base = rtrim($base, '/');
    return $base . '/' . ltrim($path, '/');
}

function flash($key, $msg = null) {
    if ($msg === null) {
        if (isset($_SESSION['flash'][$key])) {
            $v = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $v;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $msg;
}

function notify($conn, $id_user, $pesan) {
    $stmt = $conn->prepare("INSERT INTO notifikasi (id_user, pesan) VALUES (?, ?)");
    $stmt->bind_param('is', $id_user, $pesan);
    $stmt->execute();
}

function badge_status($s) {
    $map = [
        'Menunggu Verifikasi' => 'badge-warning',
        'Diverifikasi'        => 'badge-info',
        'Disetujui'           => 'badge-success',
        'Ditolak'             => 'badge-danger',
        'Selesai'             => 'badge-primary',
    ];
    $cls = $map[$s] ?? 'badge-info';
    return '<span class="badge ' . $cls . '">' . htmlspecialchars($s) . '</span>';
}
