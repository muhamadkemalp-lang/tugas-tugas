<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
$id = (int)($_POST['id'] ?? 0);

if ($id == $_SESSION['id_user']) {
    set_flash('gagal', 'Anda tidak dapat menghapus akun sendiri.');
    redirect('index.php');
}

// Hapus foto milik pengguna ini terlebih dahulu (baris pengaduan ikut terhapus via ON DELETE CASCADE)
$stmt = mysqli_prepare($koneksi, "SELECT foto FROM pengaduan WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($r = mysqli_fetch_assoc($res)) hapus_foto($r['foto']);

$del = mysqli_prepare($koneksi, "DELETE FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($del, "i", $id);
if (mysqli_stmt_execute($del) && mysqli_stmt_affected_rows($del) > 0) {
    set_flash('sukses', 'Pengguna berhasil dihapus.');
} else {
    set_flash('gagal', 'Pengguna tidak ditemukan atau gagal dihapus.');
}
redirect('index.php');
