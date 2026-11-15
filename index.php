<?php
require_once __DIR__ . '/includes/auth.php';
$total   = ambil_angka($koneksi, "SELECT COUNT(*) FROM pengaduan");
$selesai = ambil_angka($koneksi, "SELECT COUNT(*) FROM pengaduan WHERE status='Selesai'");
$warga   = ambil_angka($koneksi, "SELECT COUNT(*) FROM users WHERE role='masyarakat'");
$judul = 'Beranda'; $layout = 'public';
include __DIR__ . '/includes/header.php';
?>
<section class="hero text-center text-lg-start mb-5">
    <div class="row align-items-center">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold">Suarakan masalah di lingkunganmu.</h1>
            <p class="lead my-3">LAPORIN membantu masyarakat menyampaikan pengaduan dan memantau tindak lanjutnya secara transparan.</p>
            <a href="<?= BASE_URL ?>/register.php" class="btn btn-primary btn-lg me-2">Mulai Melapor</a>
            <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light btn-lg">Masuk</a>
        </div>
        <div class="col-lg-5 d-none d-lg-block text-center"><i class="bi bi-megaphone-fill" style="font-size:8rem;color:#4f8ef7"></i></div>
    </div>
</section>

<div class="row g-3 mb-5 text-center">
    <div class="col-md-4"><div class="card stat-card p-3"><div class="angka"><?= $total ?></div><div class="text-muted">Pengaduan Masuk</div></div></div>
    <div class="col-md-4"><div class="card stat-card p-3"><div class="angka"><?= $selesai ?></div><div class="text-muted">Pengaduan Selesai</div></div></div>
    <div class="col-md-4"><div class="card stat-card p-3"><div class="angka"><?= $warga ?></div><div class="text-muted">Warga Terdaftar</div></div></div>
</div>

<h2 class="h4 text-center mb-4">Bagaimana cara kerjanya?</h2>
<div class="row g-4">
    <div class="col-md-4"><div class="card h-100 p-4"><div class="feature-icon mb-3"><i class="bi bi-person-plus"></i></div><h3 class="h5">1. Daftar &amp; Masuk</h3><p class="text-muted mb-0">Buat akun gratis untuk mulai menyampaikan pengaduan.</p></div></div>
    <div class="col-md-4"><div class="card h-100 p-4"><div class="feature-icon mb-3"><i class="bi bi-camera"></i></div><h3 class="h5">2. Kirim Laporan</h3><p class="text-muted mb-0">Pilih kategori, jelaskan masalah, dan lampirkan foto bukti.</p></div></div>
    <div class="col-md-4"><div class="card h-100 p-4"><div class="feature-icon mb-3"><i class="bi bi-check2-circle"></i></div><h3 class="h5">3. Pantau Status</h3><p class="text-muted mb-0">Lihat perkembangan: Menunggu, Diproses, Selesai, atau Ditolak.</p></div></div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
