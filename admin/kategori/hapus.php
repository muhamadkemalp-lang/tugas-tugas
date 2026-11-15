<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
$id = (int)($_POST['id'] ?? 0);

// Kategori yang masih dipakai pengaduan tidak boleh dihapus (foreign key RESTRICT)
$cek = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM pengaduan WHERE id_kategori = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
mysqli_stmt_bind_result($cek, $jumlah);
mysqli_stmt_fetch($cek);
mysqli_stmt_close($cek);

if ($jumlah > 0) {
    set_flash('gagal', "Kategori tidak dapat dihapus karena masih dipakai oleh $jumlah pengaduan.");
    redirect('index.php');
}

$del = mysqli_prepare($koneksi, "DELETE FROM kategori WHERE id_kategori = ?");
mysqli_stmt_bind_param($del, "i", $id);
if (mysqli_stmt_execute($del) && mysqli_stmt_affected_rows($del) > 0) {
    set_flash('sukses', 'Kategori berhasil dihapus.');
} else {
    set_flash('gagal', 'Kategori tidak ditemukan atau gagal dihapus.');
}
redirect('index.php');
