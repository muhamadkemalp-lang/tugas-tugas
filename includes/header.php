<?php
$layout = $layout ?? 'public';   // public | user | admin
$judul  = $judul ?? 'LAPORIN';
$login  = isset($_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul) ?> | LAPORIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php if ($layout === 'admin'): ?>
<div class="admin-wrap">
    <aside id="sidebarMenu" class="sidebar collapse d-lg-flex flex-column">
        <a class="sidebar-brand" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-megaphone-fill"></i> LAPORIN</a>
        <nav class="nav flex-column">
            <a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="nav-link" href="<?= BASE_URL ?>/admin/pengaduan/index.php"><i class="bi bi-chat-square-text"></i> Pengaduan</a>
            <a class="nav-link" href="<?= BASE_URL ?>/admin/kategori/index.php"><i class="bi bi-tags"></i> Kategori</a>
            <a class="nav-link" href="<?= BASE_URL ?>/admin/pengguna/index.php"><i class="bi bi-people"></i> Pengguna</a>
        </nav>
        <a class="nav-link logout mt-auto" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right"></i> Keluar</a>
    </aside>
    <main class="admin-main">
        <div class="topbar">
            <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"><i class="bi bi-list"></i></button>
            <h1 class="h5 mb-0"><?= e($judul) ?></h1>
            <span class="ms-auto text-muted small"><i class="bi bi-person-circle"></i> <?= e($_SESSION['nama'] ?? '') ?></span>
        </div>
        <div class="content">
<?php else: ?>
<nav class="navbar navbar-expand-lg navbar-laporin">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php"><i class="bi bi-megaphone-fill"></i> LAPORIN</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">Beranda</a></li>
                <?php if ($login && $_SESSION['role'] === 'masyarakat'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/user/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/user/pengaduan/index.php">Pengaduan Saya</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-light" href="<?= BASE_URL ?>/user/pengaduan/tambah.php">Buat Pengaduan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout.php">Keluar</a></li>
                <?php elseif ($login): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout.php">Keluar</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login.php">Masuk</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-light" href="<?= BASE_URL ?>/register.php">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container py-4">
<?php endif; ?>
<?php tampil_flash(); ?>
