<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$id = (int)($_GET['id'] ?? 0);

// Proses ubah status + tanggapan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $tanggapan = trim($_POST['tanggapan'] ?? '');

    if (!in_array($status, STATUS_LIST, true)) {
        set_flash('gagal', 'Status tidak valid.');
    } else {
        $up = mysqli_prepare($koneksi, "UPDATE pengaduan SET status = ?, tanggapan = ? WHERE id_pengaduan = ?");
        mysqli_stmt_bind_param($up, "ssi", $status, $tanggapan, $id);
        if (mysqli_stmt_execute($up)) set_flash('sukses', 'Status pengaduan berhasil diperbarui.');
        else set_flash('gagal', 'Gagal memperbarui status.');
    }
    redirect('detail.php?id=' . $id);
}

$stmt = mysqli_prepare($koneksi, "SELECT p.*, u.nama, u.email, k.nama_kategori
    FROM pengaduan p
    JOIN users u ON p.id_user = u.id_user
    JOIN kategori k ON p.id_kategori = k.id_kategori
    WHERE p.id_pengaduan = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$p) { set_flash('gagal', 'Pengaduan tidak ditemukan.'); redirect('index.php'); }

$judul = 'Detail Pengaduan'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h2 class="h5 mb-0"><?= e($p['judul']) ?></h2>
                <?= badge_status($p['status']) ?>
            </div>
            <p class="text-muted small mb-3">
                <i class="bi bi-person"></i> <?= e($p['nama']) ?> (<?= e($p['email']) ?>) &nbsp;|&nbsp;
                <i class="bi bi-tag"></i> <?= e($p['nama_kategori']) ?> &nbsp;|&nbsp;
                <i class="bi bi-clock"></i> <?= tgl($p['created_at']) ?>
            </p>
            <p style="white-space:pre-line"><?= e($p['isi']) ?></p>
            <?php if ($p['foto']): ?>
                <img src="<?= BASE_URL ?>/uploads/<?= e($p['foto']) ?>" class="foto-bukti" alt="Foto bukti">
            <?php else: ?>
                <p class="text-muted fst-italic mb-0">Tidak ada foto bukti.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-4">
            <h3 class="h6">Tindak Lanjut</h3>
            <form method="post">
                <input type="hidden" name="id" value="<?= $p['id_pengaduan'] ?>">
                <div class="mb-3"><label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (STATUS_LIST as $s): ?>
                        <option value="<?= $s ?>" <?= $p['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select></div>
                <div class="mb-3"><label class="form-label">Tanggapan</label>
                    <textarea name="tanggapan" rows="4" class="form-control"><?= e($p['tanggapan']) ?></textarea></div>
                <button class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-light">Kembali</a>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
