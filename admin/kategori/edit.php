<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "SELECT id_kategori, nama_kategori, deskripsi FROM kategori WHERE id_kategori = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) { set_flash('gagal', 'Kategori tidak ditemukan.'); redirect('index.php'); }

$error = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    if ($nama === '') $error[] = 'Nama kategori wajib diisi.';
    if (strlen($nama) > 100) $error[] = 'Nama kategori maksimal 100 karakter.';

    if (!$error) {
        $cek = mysqli_prepare($koneksi, "SELECT id_kategori FROM kategori WHERE nama_kategori = ? AND id_kategori <> ?");
        mysqli_stmt_bind_param($cek, "si", $nama, $id);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) $error[] = 'Nama kategori sudah digunakan.';
    }
    if (!$error) {
        $up = mysqli_prepare($koneksi, "UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id_kategori = ?");
        mysqli_stmt_bind_param($up, "ssi", $nama, $deskripsi, $id);
        if (mysqli_stmt_execute($up)) {
            set_flash('sukses', 'Kategori berhasil diperbarui.');
            redirect('index.php');
        }
        $error[] = 'Gagal memperbarui data.';
    }
    $data['nama_kategori'] = $nama; $data['deskripsi'] = $deskripsi;
}
$judul = 'Edit Kategori'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-4" style="max-width:560px">
    <?php foreach ($error as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Nama Kategori</label><input type="text" name="nama_kategori" class="form-control" value="<?= e($data['nama_kategori']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" rows="3" class="form-control"><?= e($data['deskripsi']) ?></textarea></div>
        <button class="btn btn-primary">Perbarui</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
