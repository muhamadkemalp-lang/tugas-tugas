<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/fungsi.php';

// Panggil di awal halaman yang butuh login. $role = 'admin' / 'masyarakat' / null (semua role)
function wajib_login($role = null) {
    if (!isset($_SESSION['id_user'])) {
        set_flash('gagal', 'Silakan login terlebih dahulu.');
        redirect(BASE_URL . '/login.php');
    }
    if ($role !== null && $_SESSION['role'] !== $role) {
        set_flash('gagal', 'Anda tidak memiliki akses ke halaman tersebut.');
        redirect(BASE_URL . '/' . ($_SESSION['role'] === 'admin' ? 'admin' : 'user') . '/dashboard.php');
    }
}

// Untuk halaman login/register: jika sudah login, arahkan ke dashboard
function sudah_login_redirect() {
    if (isset($_SESSION['id_user'])) {
        redirect(BASE_URL . '/' . ($_SESSION['role'] === 'admin' ? 'admin' : 'user') . '/dashboard.php');
    }
}
