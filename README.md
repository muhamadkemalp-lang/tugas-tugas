# LAPORIN – Sistem Informasi Pengaduan Masyarakat

Aplikasi web untuk menyampaikan dan menindaklanjuti pengaduan masyarakat. Dibuat sebagai tugas **UTS Pemrograman Web** menggunakan PHP Native tanpa framework.

## Teknologi
HTML5 · CSS3 · Bootstrap 5 · JavaScript · PHP Native · MySQL (MySQLi) · XAMPP

## Fitur
**Masyarakat:** registrasi, login/logout, dashboard, buat pengaduan (kategori + foto bukti), daftar & detail pengaduan milik sendiri, pantau status, cari & filter status.

**Admin:** login/logout, dashboard statistik, CRUD pengguna, CRUD kategori, kelola semua pengaduan (cari, filter, ubah status + tanggapan, hapus).

Status pengaduan: `Menunggu`, `Diproses`, `Selesai`, `Ditolak`.

**Keamanan:** `password_hash()` / `password_verify()`, prepared statements di semua query yang memakai input pengguna, escape output (`htmlspecialchars`), validasi upload (ukuran, ekstensi, isi gambar, nama file acak), pembatasan halaman per role, `session_regenerate_id()` saat login.

## Instalasi (XAMPP)
1. Install & jalankan **Apache** dan **MySQL** di XAMPP Control Panel.
2. Salin/clone folder ini ke `C:\xampp\htdocs\laporin` (nama folder harus `laporin`, atau ubah `BASE_URL` di `config/koneksi.php`).
3. Buka `http://localhost/phpmyadmin` → tab **Import** → pilih `database/laporin.sql` → **Import**.
4. Jika user/password MySQL berbeda dari default XAMPP, sesuaikan `config/koneksi.php`.
5. Buka `http://localhost/laporin/`.

## Akun pengujian
| Role | Email | Password |
|---|---|---|
| Admin | admin@laporin.test | password |
| Masyarakat | budi@laporin.test | password |
| Masyarakat | siti@laporin.test | password |

## Struktur folder
```
laporin/
├── index.php, login.php, register.php, logout.php
├── config/koneksi.php          koneksi database + session + BASE_URL
├── includes/                   header, footer, auth (cek role), fungsi bantu
├── admin/                      dashboard, pengguna/, kategori/, pengaduan/
├── user/                       dashboard, pengaduan/ (tambah, daftar, detail)
├── assets/                     css/style.css, js/script.js
├── uploads/                    foto bukti (tidak di-commit)
└── database/laporin.sql        skema + data dummy
```

## Skema database
- `users` (id_user PK, nama, email UNIQUE, password, role, created_at)
- `kategori` (id_kategori PK, nama_kategori UNIQUE, deskripsi)
- `pengaduan` (id_pengaduan PK, id_user FK→users *ON DELETE CASCADE*, id_kategori FK→kategori *ON DELETE RESTRICT*, judul, isi, foto, status, tanggapan, created_at, updated_at)

## Alur demonstrasi
1. Beranda → **Daftar** akun baru → login sebagai masyarakat.
2. **Buat Pengaduan** (pilih kategori, upload foto) → status awal *Menunggu*.
3. Logout → login sebagai **admin** → lihat statistik di dashboard.
4. Buka **Pengaduan** → cari/filter → **Detail** → ubah status ke *Diproses* + isi tanggapan.
5. Login lagi sebagai masyarakat → cek status & tanggapan di detail pengaduan.
6. Tunjukkan CRUD **Kategori** dan **Pengguna**, serta pembatasan akses (masyarakat membuka `/admin/dashboard.php` → dialihkan).

## Daftar screenshot untuk laporan
Beranda · Registrasi · Login · Dashboard masyarakat · Form pengaduan · Daftar & detail pengaduan · Dashboard admin · Kelola pengaduan (filter) · Ubah status · CRUD kategori · CRUD pengguna · Struktur tabel & relasi (phpMyAdmin Designer).

## Upload ke GitHub
```bash
cd laporin
git init
git add .
git commit -m "Initial commit: LAPORIN"
git branch -M main
git remote add origin https://github.com/USERNAME/laporin.git
git push -u origin main
```

## Pengembangan lanjutan
Token CSRF pada form, pagination, grafik statistik, reset password, batas percobaan login.
