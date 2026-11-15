<?php
require_once __DIR__ . '/includes/auth.php';
sudah_login_redirect();

$nama = $email = '';
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirm  = $_POST['konfirmasi'] ?? '';

    if ($nama === '' || strlen($nama) < 3)               $error[] = 'Nama minimal 3 karakter.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))      $error[] = 'Format email tidak valid.';
    if (strlen($password) < 6)                           $error[] = 'Password minimal 6 karakter.';
    if ($password !== $konfirm)                          $error[] = 'Konfirmasi password tidak sama.';

    if (!$error) {
        $stmt = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) $error[] = 'Email sudah terdaftar.';
        mysqli_stmt_close($stmt);
    }

    if (!$error) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'masyarakat')");
        mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $hash);
        if (mysqli_stmt_execute($stmt)) {
            set_flash('sukses', 'Registrasi berhasil! Silakan login.');
            redirect(BASE_URL . '/login.php');
        }
        $error[] = 'Registrasi gagal, coba lagi.';
    }
}

$judul = 'Daftar'; $layout = 'public';
include __DIR__ . '/includes/header.php';
?>
<div class="card auth-card p-4">
    <h1 class="h4 mb-1">Buat Akun</h1>
    <p class="text-muted">Daftar untuk mulai menyampaikan pengaduan.</p>
    <?php foreach ($error as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="<?= e($nama) ?>" required></div>
        <div class="mb-3"><label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required></div>
        <div class="mb-3"><label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" minlength="6" required></div>
        <div class="mb-3"><label class="form-label">Konfirmasi Password</label>
            <input type="password" name="konfirmasi" class="form-control" required></div>
        <button class="btn btn-primary w-100">Daftar</button>
    </form>
    <p class="text-center mt-3 mb-0 small">Sudah punya akun? <a href="<?= BASE_URL ?>/login.php">Masuk</a></p>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
