<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('masyarakat');

$kategori = mysqli_query($koneksi, "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori");
$daftar_kategori = mysqli_fetch_all($kategori, MYSQLI_ASSOC);
$id_ids = array_column($daftar_kategori, 'id_kategori');

$judul_p = $isi = ''; $id_kategori = 0; $error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_p = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $id_kategori = (int)($_POST['id_kategori'] ?? 0);

    if (strlen($judul_p) < 5 || strlen($judul_p) > 150) $error[] = 'Judul harus 5–150 karakter.';
    if (strlen($isi) < 20) $error[] = 'Isi pengaduan minimal 20 karakter.';
    if (!in_array((string)$id_kategori, array_map('strval', $id_ids), true)) $error[] = 'Pilih kategori yang valid.';

    $nama_foto = null;
    if (!$error) {
        [$ok, $hasil] = upload_foto($_FILES['foto'] ?? null);
        if ($ok) $nama_foto = $hasil; else $error[] = $hasil;
    }

    if (!$error) {
        $id_user = (int)$_SESSION['id_user'];
        $stmt = mysqli_prepare($koneksi, "INSERT INTO pengaduan (id_user, id_kategori, judul, isi, foto, status) VALUES (?, ?, ?, ?, ?, 'Menunggu')");
        mysqli_stmt_bind_param($stmt, "iisss", $id_user, $id_kategori, $judul_p, $isi, $nama_foto);
        if (mysqli_stmt_execute($stmt)) {
            set_flash('sukses', 'Pengaduan berhasil dikirim. Status: Menunggu.');
            redirect('index.php');
        }
        hapus_foto($nama_foto);
        $error[] = 'Gagal menyimpan pengaduan.';
    }
}

$judul = 'Buat Pengaduan'; $layout = 'user';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-4 mx-auto" style="max-width:720px">
    <h1 class="h4 mb-3">Buat Pengaduan Baru</h1>
    <?php foreach ($error as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3"><label class="form-label">Judul Pengaduan</label>
            <input type="text" name="judul" class="form-control" value="<?= e($judul_p) ?>" required></div>
        <div class="mb-3"><label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih kategori --</option>
                <?php foreach ($daftar_kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>" <?= $id_kategori == $k['id_kategori'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select></div>
        <div class="mb-3"><label class="form-label">Isi Pengaduan</label>
            <textarea name="isi" rows="5" class="form-control" required><?= e($isi) ?></textarea></div>
        <div class="mb-3"><label class="form-label">Foto Bukti <span class="text-muted small">(JPG/PNG, maks 2 MB)</span></label>
            <input type="file" name="foto" id="foto" class="form-control" accept=".jpg,.jpeg,.png" required>
            <img id="preview" class="foto-bukti mt-2 d-none" alt="Pratinjau"></div>
        <button class="btn btn-primary">Kirim Pengaduan</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
