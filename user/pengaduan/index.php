<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('masyarakat');

$id     = (int)$_SESSION['id_user'];
$cari   = trim($_GET['cari'] ?? '');
$status = $_GET['status'] ?? '';
$like   = '%' . $cari . '%';

$sql = "SELECT p.id_pengaduan, p.judul, p.status, p.created_at, k.nama_kategori
        FROM pengaduan p JOIN kategori k ON p.id_kategori = k.id_kategori
        WHERE p.id_user = ? AND (p.judul LIKE ? OR k.nama_kategori LIKE ?)";
$tipe = "iss";
$param = [$id, $like, $like];
if (in_array($status, STATUS_LIST, true)) {
    $sql .= " AND p.status = ?";
    $tipe .= "s";
    $param[] = $status;
}
$sql .= " ORDER BY p.created_at DESC";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, $tipe, ...$param);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

$judul = 'Pengaduan Saya'; $layout = 'user';
include __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Pengaduan Saya</h1>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Pengaduan</a>
</div>
<div class="card p-3">
    <form class="row g-2 mb-3" method="get">
        <div class="col-md-6"><input type="text" name="cari" class="form-control" placeholder="Cari judul / kategori" value="<?= e($cari) ?>"></div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <?php foreach (STATUS_LIST as $s): ?>
                <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill">Cari</button>
            <a href="index.php" class="btn btn-light">Reset</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($hasil)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e($r['judul']) ?></td>
                    <td><?= e($r['nama_kategori']) ?></td>
                    <td><?= badge_status($r['status']) ?></td>
                    <td class="small text-muted"><?= tgl($r['created_at']) ?></td>
                    <td class="text-end"><a href="detail.php?id=<?= $r['id_pengaduan'] ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            <?php endwhile; ?>
            <?php if ($no === 1): ?><tr><td colspan="6" class="text-center text-muted py-4">Belum ada pengaduan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
