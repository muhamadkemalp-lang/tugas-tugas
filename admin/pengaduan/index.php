<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$cari   = trim($_GET['cari'] ?? '');
$status = $_GET['status'] ?? '';
$like   = '%' . $cari . '%';

$sql = "SELECT p.id_pengaduan, p.judul, p.status, p.created_at, u.nama, k.nama_kategori
        FROM pengaduan p
        JOIN users u ON p.id_user = u.id_user
        JOIN kategori k ON p.id_kategori = k.id_kategori
        WHERE (p.judul LIKE ? OR u.nama LIKE ? OR k.nama_kategori LIKE ?)";
$tipe = "sss";
$param = [$like, $like, $like];
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

$judul = 'Data Pengaduan'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-3">
    <form class="row g-2 mb-3" method="get">
        <div class="col-md-6"><input type="text" name="cari" class="form-control" placeholder="Cari judul / pelapor / kategori" value="<?= e($cari) ?>"></div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <?php foreach (STATUS_LIST as $s): ?>
                <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Cari</button>
            <a href="index.php" class="btn btn-light">Reset</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Status</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($hasil)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e($r['judul']) ?></td>
                    <td><?= e($r['nama']) ?></td>
                    <td><?= e($r['nama_kategori']) ?></td>
                    <td><?= badge_status($r['status']) ?></td>
                    <td class="small text-muted"><?= tgl($r['created_at']) ?></td>
                    <td class="text-end text-nowrap">
                        <a href="detail.php?id=<?= $r['id_pengaduan'] ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                        <form action="hapus.php" method="post" class="d-inline form-hapus">
                            <input type="hidden" name="id" value="<?= $r['id_pengaduan'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($no === 1): ?><tr><td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
