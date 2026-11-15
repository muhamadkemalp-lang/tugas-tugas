<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$cari = trim($_GET['cari'] ?? '');
$like = '%' . $cari . '%';
$stmt = mysqli_prepare($koneksi, "SELECT id_user, nama, email, role, created_at FROM users
    WHERE nama LIKE ? OR email LIKE ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, "ss", $like, $like);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

$judul = 'Data Pengguna'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-3">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex gap-2" method="get">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama / email" value="<?= e($cari) ?>">
            <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        </form>
        <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pengguna</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Terdaftar</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($hasil)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e($r['nama']) ?></td>
                    <td><?= e($r['email']) ?></td>
                    <td><span class="badge bg-<?= $r['role'] === 'admin' ? 'primary' : 'secondary' ?>"><?= e($r['role']) ?></span></td>
                    <td class="small text-muted"><?= tgl($r['created_at']) ?></td>
                    <td class="text-end">
                        <a href="edit.php?id=<?= $r['id_user'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if ($r['id_user'] != $_SESSION['id_user']): ?>
                        <form action="hapus.php" method="post" class="d-inline form-hapus">
                            <input type="hidden" name="id" value="<?= $r['id_user'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($no === 1): ?><tr><td colspan="6" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
