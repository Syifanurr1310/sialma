<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('mahasiswa');
$uid = $_SESSION['user']['id_user'];
$conn->query("UPDATE notifikasi SET dibaca=1 WHERE id_user=$uid");
$rows = $conn->query("SELECT * FROM notifikasi WHERE id_user=$uid ORDER BY created_at DESC");
$pageTitle = 'Notifikasi';
include __DIR__ . '/../includes/header.php';
?>
<div class="card">
<?php if ($rows->num_rows===0): ?>
  <div class="empty">Tidak ada notifikasi.</div>
<?php else: ?>
  <div class="timeline">
    <?php while ($n = $rows->fetch_assoc()): ?>
      <div class="tl-item"><div class="tl-dot"></div>
        <div class="tl-body"><?= htmlspecialchars($n['pesan']) ?><small><?= date('d M Y H:i', strtotime($n['created_at'])) ?></small></div>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
