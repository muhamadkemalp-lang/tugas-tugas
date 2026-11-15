<?php
require_once __DIR__ . '/config/koneksi.php';
$_SESSION = [];
session_destroy();
session_start();
require_once __DIR__ . '/includes/fungsi.php';
set_flash('sukses', 'Anda berhasil keluar.');
redirect(BASE_URL . '/login.php');
