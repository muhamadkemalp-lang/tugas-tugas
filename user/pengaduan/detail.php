<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('masyarakat');

$id = (int)($_GET['id'] ?? 0);
$id_user = (int)$_SESSION['id_user'];

// Hanya boleh melihat pengaduan miliknya sendiri
$stmt = mysqli_prepare($koneksi, "SELECT p.*, k.nama_kategori
    FROM pengaduan p JOIN kategori k ON p.id_kategori = k.id_kategori
    WHERE p.id_pengaduan = ? AND p.id_user = ?");
mysqli_stmt_bind_param($stmt, "ii", $id, $id_user);
mysqli_stmt_execute($stmt);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$p) { set_flash('gagal', 'Pengaduan tidak ditemukan.'); redirect('index.php'); }

$judul = 'Detail Pengaduan'; $layout = 'user';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-4 mx-auto" style="max-width:760px">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <h1 class="h4 mb-0"><?= e($p['judul']) ?></h1>
        <?= badge_status($p['status']) ?>
    </div>
    <p class="text-muted small mb-3"><i class="bi bi-tag"></i> <?= e($p['nama_kategori']) ?> &nbsp;|&nbsp; <i class="bi bi-clock"></i> <?= tgl($p['created_at']) ?></p>
    <p style="white-space:pre-line"><?= e($p['isi']) ?></p>
    <?php if ($p['foto']): ?>
        <img src="<?= BASE_URL ?>/uploads/<?= e($p['foto']) ?>" class="foto-bukti mb-3" alt="Foto bukti">
    <?php endif; ?>
    <div class="alert alert-light border mb-3">
        <strong>Tanggapan Admin:</strong><br>
        <?= $p['tanggapan'] ? e($p['tanggapan']) : '<span class="text-muted">Belum ada tanggapan.</span>' ?>
    </div>
    <a href="index.php" class="btn btn-light">&larr; Kembali</a>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
