<?php
require_once __DIR__ . '/../includes/auth.php';
wajib_login('admin');

$total     = ambil_angka($koneksi, "SELECT COUNT(*) FROM pengaduan");
$jml_user  = ambil_angka($koneksi, "SELECT COUNT(*) FROM users WHERE role='masyarakat'");
$jml_kat   = ambil_angka($koneksi, "SELECT COUNT(*) FROM kategori");
$per_status = [];
foreach (STATUS_LIST as $s) {
    $per_status[$s] = ambil_angka($koneksi, "SELECT COUNT(*) FROM pengaduan WHERE status='$s'");
}
$terbaru = mysqli_query($koneksi, "SELECT p.id_pengaduan, p.judul, p.status, p.created_at, u.nama, k.nama_kategori
    FROM pengaduan p
    JOIN users u ON p.id_user = u.id_user
    JOIN kategori k ON p.id_kategori = k.id_kategori
    ORDER BY p.created_at DESC LIMIT 5");

$cards = [
    ['Total Pengaduan', $total, 'bi-chat-square-text'],
    ['Menunggu', $per_status['Menunggu'], 'bi-hourglass-split'],
    ['Diproses', $per_status['Diproses'], 'bi-arrow-repeat'],
    ['Selesai', $per_status['Selesai'], 'bi-check2-circle'],
    ['Ditolak', $per_status['Ditolak'], 'bi-x-circle'],
    ['Pengguna', $jml_user, 'bi-people'],
    ['Kategori', $jml_kat, 'bi-tags'],
];

$judul = 'Dashboard Admin'; $layout = 'admin';
include __DIR__ . '/../includes/header.php';
?>
<div class="row g-3 mb-4">
    <?php foreach ($cards as $c): ?>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="text-muted small"><?= e($c[0]) ?></div><div class="angka"><?= $c[1] ?></div></div>
                <div class="ikon"><i class="bi <?= $c[2] ?>"></i></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h6 mb-0">Pengaduan Terbaru</h2>
        <a href="<?= BASE_URL ?>/admin/pengaduan/index.php" class="small">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            <?php while ($r = mysqli_fetch_assoc($terbaru)): ?>
                <tr>
                    <td><a href="<?= BASE_URL ?>/admin/pengaduan/detail.php?id=<?= $r['id_pengaduan'] ?>"><?= e($r['judul']) ?></a></td>
                    <td><?= e($r['nama']) ?></td>
                    <td><?= e($r['nama_kategori']) ?></td>
                    <td><?= badge_status($r['status']) ?></td>
                    <td class="small text-muted"><?= tgl($r['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
