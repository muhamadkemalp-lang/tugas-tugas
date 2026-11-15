<?php
const STATUS_LIST = ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'];

// Escape output agar aman dari XSS
function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Pesan notifikasi (flash message) satu kali tampil
function set_flash($tipe, $pesan) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function tampil_flash() {
    if (!isset($_SESSION['flash'])) return;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $kelas = ($f['tipe'] === 'sukses') ? 'success' : 'danger';
    echo '<div class="alert alert-' . $kelas . ' alert-dismissible fade show" role="alert">'
       . e($f['pesan'])
       . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function badge_status($status) {
    $map = ['Menunggu' => 'warning text-dark', 'Diproses' => 'info text-dark',
            'Selesai' => 'success', 'Ditolak' => 'danger'];
    $kelas = $map[$status] ?? 'secondary';
    return '<span class="badge bg-' . $kelas . '">' . e($status) . '</span>';
}

function tgl($waktu) {
    return date('d M Y, H:i', strtotime($waktu));
}

// Ambil satu angka dari query COUNT (query tanpa input pengguna)
function ambil_angka($koneksi, $sql) {
    $r = mysqli_query($koneksi, $sql);
    $baris = mysqli_fetch_row($r);
    return (int)$baris[0];
}

// Upload foto bukti: validasi ukuran, ekstensi, dan isi file
function upload_foto($f) {
    if (!isset($f) || $f['error'] === UPLOAD_ERR_NO_FILE) return [false, 'Foto bukti wajib diunggah.'];
    if ($f['error'] !== UPLOAD_ERR_OK) return [false, 'Upload gagal (kode ' . $f['error'] . ').'];
    if ($f['size'] > 2 * 1024 * 1024) return [false, 'Ukuran foto maksimal 2 MB.'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) return [false, 'Format foto harus JPG atau PNG.'];
    $info = @getimagesize($f['tmp_name']);
    if (!$info || !in_array($info['mime'], ['image/jpeg', 'image/png'], true)) {
        return [false, 'File yang diunggah bukan gambar yang valid.'];
    }
    $nama = 'bukti_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $nama)) {
        return [false, 'Gagal menyimpan file ke folder uploads.'];
    }
    return [true, $nama];
}

function hapus_foto($nama) {
    if ($nama) {
        $path = __DIR__ . '/../uploads/' . basename($nama);
        if (is_file($path)) unlink($path);
    }
}
