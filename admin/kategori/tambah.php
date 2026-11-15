<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$nama = $deskripsi = ''; $error = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    if ($nama === '') $error[] = 'Nama kategori wajib diisi.';
    if (strlen($nama) > 100) $error[] = 'Nama kategori maksimal 100 karakter.';

    if (!$error) {
        $cek = mysqli_prepare($koneksi, "SELECT id_kategori FROM kategori WHERE nama_kategori = ?");
        mysqli_stmt_bind_param($cek, "s", $nama);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) $error[] = 'Nama kategori sudah ada.';
    }
    if (!$error) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $nama, $deskripsi);
        if (mysqli_stmt_execute($stmt)) {
            set_flash('sukses', 'Kategori berhasil ditambahkan.');
            redirect('index.php');
        }
        $error[] = 'Gagal menyimpan data.';
    }
}
$judul = 'Tambah Kategori'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-4" style="max-width:560px">
    <?php foreach ($error as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Nama Kategori</label><input type="text" name="nama_kategori" class="form-control" value="<?= e($nama) ?>" required></div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" rows="3" class="form-control"><?= e($deskripsi) ?></textarea></div>
        <button class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
