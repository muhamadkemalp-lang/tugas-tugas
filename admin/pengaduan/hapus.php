<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
$id = (int)($_POST['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT foto FROM pengaduan WHERE id_pengaduan = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($p) {
    $del = mysqli_prepare($koneksi, "DELETE FROM pengaduan WHERE id_pengaduan = ?");
    mysqli_stmt_bind_param($del, "i", $id);
    if (mysqli_stmt_execute($del)) {
        hapus_foto($p['foto']);
        set_flash('sukses', 'Pengaduan berhasil dihapus.');
    } else {
        set_flash('gagal', 'Gagal menghapus pengaduan.');
    }
} else {
    set_flash('gagal', 'Pengaduan tidak ditemukan.');
}
redirect('index.php');
