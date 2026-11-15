<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$nama = $email = ''; $role = 'masyarakat'; $error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (strlen($nama) < 3) $error[] = 'Nama minimal 3 karakter.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error[] = 'Format email tidak valid.';
    if (strlen($password) < 6) $error[] = 'Password minimal 6 karakter.';
    if (!in_array($role, ['admin', 'masyarakat'], true)) $error[] = 'Role tidak valid.';

    if (!$error) {
        $cek = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE email = ?");
        mysqli_stmt_bind_param($cek, "s", $email);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) $error[] = 'Email sudah digunakan.';
    }
    if (!$error) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $hash, $role);
        if (mysqli_stmt_execute($stmt)) {
            set_flash('sukses', 'Pengguna berhasil ditambahkan.');
            redirect('index.php');
        }
        $error[] = 'Gagal menyimpan data.';
    }
}

$judul = 'Tambah Pengguna'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-4" style="max-width:560px">
    <?php foreach ($error as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="nama" class="form-control" value="<?= e($nama) ?>" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($email) ?>" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="masyarakat" <?= $role === 'masyarakat' ? 'selected' : '' ?>>Masyarakat</option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select></div>
        <button class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
