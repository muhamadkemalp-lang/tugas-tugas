-- LAPORIN - Sistem Informasi Pengaduan Masyarakat
-- Import lewat phpMyAdmin (tab Import) atau jalankan di tab SQL.

CREATE DATABASE IF NOT EXISTS laporin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laporin;

DROP TABLE IF EXISTS pengaduan;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id_user    INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','masyarakat') NOT NULL DEFAULT 'masyarakat',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE kategori (
    id_kategori   INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE,
    deskripsi     VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE pengaduan (
    id_pengaduan INT AUTO_INCREMENT PRIMARY KEY,
    id_user      INT NOT NULL,
    id_kategori  INT NOT NULL,
    judul        VARCHAR(150) NOT NULL,
    isi          TEXT NOT NULL,
    foto         VARCHAR(255) NULL,
    status       ENUM('Menunggu','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu',
    tanggapan    TEXT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pengaduan_user     FOREIGN KEY (id_user)     REFERENCES users(id_user)         ON DELETE CASCADE,
    CONSTRAINT fk_pengaduan_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)  ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Data dummy. Semua akun berpassword: password
INSERT INTO users (nama, email, password, role) VALUES
('Administrator', 'admin@laporin.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso',  'budi@laporin.test',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masyarakat'),
('Siti Aminah',   'siti@laporin.test',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masyarakat');

INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Infrastruktur',    'Jalan rusak, lampu jalan mati, jembatan, drainase'),
('Kebersihan',       'Sampah menumpuk, saluran tersumbat'),
('Keamanan',         'Gangguan keamanan dan ketertiban lingkungan'),
('Pelayanan Publik', 'Keluhan layanan kantor kelurahan/instansi'),
('Lingkungan',       'Pencemaran, penebangan liar, polusi');

INSERT INTO pengaduan (id_user, id_kategori, judul, isi, status, tanggapan) VALUES
(2, 1, 'Jalan berlubang di Jl. Melati', 'Terdapat lubang besar di depan gang 3 yang membahayakan pengendara motor.', 'Diproses', 'Tim sedang melakukan survei lokasi.'),
(2, 2, 'Sampah menumpuk di pasar',      'Sampah tidak diangkut selama tiga hari dan mulai berbau.', 'Menunggu', NULL),
(3, 1, 'Lampu jalan mati',               'Lampu penerangan di Jl. Kenanga mati sejak minggu lalu.', 'Selesai', 'Lampu sudah diperbaiki.'),
(3, 3, 'Kendaraan parkir sembarangan',   'Kendaraan parkir menutup akses keluar-masuk perumahan.', 'Ditolak', 'Mohon laporkan ke pihak kepolisian setempat.'),
(2, 5, 'Limbah dibuang ke sungai',       'Terlihat pembuangan limbah cair berwarna hitam ke sungai.', 'Menunggu', NULL);
