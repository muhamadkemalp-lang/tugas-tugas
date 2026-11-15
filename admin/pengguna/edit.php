<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_login('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "SELECT id_user, nama, email, role FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) { set_flash('gagal', 'Pengguna tidak ditemukan.'); redirect('index.php'); }

$error = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (strlen($nama) < 3) $error[] = 'Nama minimal 3 karakter.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error[] = 'Format email tidak valid.';
    if ($password !== '' && strlen($password) < 6) $error[] = 'Password baru minimal 6 karakter.';
    if (!in_array($role, ['admin', 'masyarakat'], true)) $error[] = 'Role tidak valid.';
    if ($id == $_SESSION['id_user'] && $role !== 'admin') $error[] = 'Anda tidak dapat menurunkan role akun sendiri.';

    if (!$error) {
        $cek = mysqli_prepare($koneksi, "SELECT id_user FROM users WHERE email = ? AND id_user <> ?");
        mysqli_stmt_bind_param($cek, "si", $email, $id);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) $error[] = 'Email sudah digunakan pengguna lain.';
    }
    if (!$error) {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $up = mysqli_prepare($koneksi, "UPDATE users SET nama=?, email=?, role=?, password=? WHERE id_user=?");
            mysqli_stmt_bind_param($up, "ssssi", $nama, $email, $role, $hash, $id);
        } else {
            $up = mysqli_prepare($koneksi, "UPDATE users SET nama=?, email=?, role=? WHERE id_user=?");
            mysqli_stmt_bind_param($up, "sssi", $nama, $email, $role, $id);
        }
        if (mysqli_stmt_execute($up)) {
            set_flash('sukses', 'Data pengguna berhasil diperbarui.');
            redirect('index.php');
        }
        $error[] = 'Gagal memperbarui data.';
    }
    $data = ['id_user' => $id, 'nama' => $nama, 'email' => $email, 'role' => $role];
}

$judul = 'Edit Pengguna'; $layout = 'admin';
include __DIR__ . '/../../includes/header.php';
?>
<div class="card p-4" style="max-width:560px">
    <?php foreach ($error as $err): ?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="nama" class="form-control" value="<?= e($data['nama']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($data['email']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Password Baru <span class="text-muted small">(kosongkan jika tidak diganti)</span></label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="masyarakat" <?= $data['role'] === 'masyarakat' ? 'selected' : '' ?>>Masyarakat</option>
                <option value="admin" <?= $data['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select></div>
        <button class="btn btn-primary">Perbarui</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
