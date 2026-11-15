<?php
// Konfigurasi koneksi database (XAMPP default: user root, tanpa password)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "laporin";

// Matikan mode exception bawaan PHP 8.1+ agar error ditangani manual
mysqli_report(MYSQLI_REPORT_OFF);

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

// Ubah jika nama folder proyek di htdocs bukan "laporin"
define('BASE_URL', '/laporin');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
