<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$cari = trim($_GET['cari'] ?? '');
$like = '%' . $cari . '%';
$stmt = mysqli_prepare($koneksi, "SELECT k.id_kategori, k.nama_kategori, k.deskripsi, COUNT(p.id_pengaduan) AS jumlah
    FROM kategori k LEFT JOIN pengaduan p ON p.id_kategori = k.id_kategori
    WHERE k.nama_kategori LIKE ? GROUP BY k.id_kategori ORDER BY k.nama_kategori");
mysqli_stmt_bind_param($stmt, "s", $like);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

$judul = 'Kategori Pengaduan'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-3">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex gap-2" method="get">
            <input type="text" name="cari" class="form-control" placeholder="Cari kategori" value="<?= e($cari) ?>">
            <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        </form>
        <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Pengaduan</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($hasil)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e($r['nama_kategori']) ?></td>
                    <td class="text-muted"><?= e($r['deskripsi']) ?></td>
                    <td><?= (int)$r['jumlah'] ?></td>
                    <td class="text-end">
                        <a href="edit.php?id=<?= $r['id_kategori'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="hapus.php" method="post" class="d-inline form-hapus">
                            <input type="hidden" name="id" value="<?= $r['id_kategori'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($no === 1): ?><tr><td colspan="5" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
