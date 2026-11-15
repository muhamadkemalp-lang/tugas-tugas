<?php
require_once __DIR__ . '/../includes/auth.php';
wajib_login('masyarakat');

$id = (int)$_SESSION['id_user'];
$stat = ['Menunggu' => 0, 'Diproses' => 0, 'Selesai' => 0, 'Ditolak' => 0];
$stmt = mysqli_prepare($koneksi, "SELECT status, COUNT(*) AS jml FROM pengaduan WHERE id_user = ? GROUP BY status");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($r = mysqli_fetch_assoc($res)) $stat[$r['status']] = (int)$r['jml'];
$total = array_sum($stat);

$stmt = mysqli_prepare($koneksi, "SELECT p.id_pengaduan, p.judul, p.status, p.created_at, k.nama_kategori
    FROM pengaduan p JOIN kategori k ON p.id_kategori = k.id_kategori
    WHERE p.id_user = ? ORDER BY p.created_at DESC LIMIT 5");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$terbaru = mysqli_stmt_get_result($stmt);

$cards = [
    ['Total Pengaduan', $total, 'bi-chat-square-text'],
    ['Menunggu', $stat['Menunggu'], 'bi-hourglass-split'],
    ['Diproses', $stat['Diproses'], 'bi-arrow-repeat'],
    ['Selesai', $stat['Selesai'], 'bi-check2-circle'],
];

$judul = 'Dashboard'; $layout = 'user';
include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0">Halo, <?= e($_SESSION['nama']) ?> 👋</h1>
        <p class="text-muted mb-0">Pantau status pengaduan Anda di sini.</p>
    </div>
    <a href="pengaduan/tambah.php" class="btn btn-primary mt-2 mt-md-0"><i class="bi bi-plus-lg"></i> Buat Pengaduan</a>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($cards as $c): ?>
    <div class="col-6 col-lg-3"><div class="card stat-card p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div><div class="text-muted small"><?= e($c[0]) ?></div><div class="angka"><?= $c[1] ?></div></div>
            <div class="ikon"><i class="bi <?= $c[2] ?>"></i></div>
        </div>
    </div></div>
    <?php endforeach; ?>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between mb-2">
        <h2 class="h6 mb-0">Pengaduan Terbaru</h2>
        <a href="pengaduan/index.php" class="small">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            <?php $ada = false; while ($r = mysqli_fetch_assoc($terbaru)): $ada = true; ?>
                <tr>
                    <td><a href="pengaduan/detail.php?id=<?= $r['id_pengaduan'] ?>"><?= e($r['judul']) ?></a></td>
                    <td><?= e($r['nama_kategori']) ?></td>
                    <td><?= badge_status($r['status']) ?></td>
                    <td class="small text-muted"><?= tgl($r['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$ada): ?><tr><td colspan="4" class="text-center text-muted py-4">Belum ada pengaduan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
