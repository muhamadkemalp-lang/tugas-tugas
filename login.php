<?php
require_once __DIR__ . '/includes/auth.php';
sudah_login_redirect();

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id_user, nama, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];
            set_flash('sukses', 'Selamat datang, ' . $user['nama'] . '!');
            redirect(BASE_URL . '/' . ($user['role'] === 'admin' ? 'admin' : 'user') . '/dashboard.php');
        }
        $error = 'Email atau password salah.';
    }
}

$judul = 'Masuk'; $layout = 'public';
include __DIR__ . '/includes/header.php';
?>
<div class="card auth-card p-4">
    <h1 class="h4 mb-1">Masuk</h1>
    <p class="text-muted">Gunakan akun LAPORIN Anda.</p>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required></div>
        <div class="mb-3"><label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-primary w-100">Masuk</button>
    </form>
    <p class="text-center mt-3 mb-0 small">Belum punya akun? <a href="<?= BASE_URL ?>/register.php">Daftar</a></p>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
