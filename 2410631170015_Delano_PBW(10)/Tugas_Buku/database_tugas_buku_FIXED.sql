-- ============================================
-- DATABASE LENGKAP: Aplikasi Pengelolaan Buku
-- Jalankan file ini sekali dari awal di phpMyAdmin
-- ============================================

-- 1. Buat database
CREATE DATABASE IF NOT EXISTS pemrograman_web_contoh
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pemrograman_web_contoh;

-- ============================================
-- 2. TABEL BUKU
-- ============================================
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS users;

CREATE TABLE buku (
    ID           INT(11)        NOT NULL AUTO_INCREMENT,
    Judul        VARCHAR(255)   NOT NULL,
    Penulis      VARCHAR(100)   NOT NULL,
    Tahun_Terbit INT(4)         NOT NULL,
    Harga        DECIMAL(15,2)  NOT NULL DEFAULT 0.00,
    Stok         INT(11)        NOT NULL DEFAULT 0,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. TABEL PELANGGAN
-- ============================================
CREATE TABLE pelanggan (
    ID        INT(11)      NOT NULL AUTO_INCREMENT,
    Nama      VARCHAR(100) NOT NULL,
    Alamat    TEXT,
    Email     VARCHAR(100),
    Telepon   VARCHAR(20),
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. TABEL PESANAN
-- ============================================
CREATE TABLE pesanan (
    ID               INT(11)       NOT NULL AUTO_INCREMENT,
    Tanggal_Pesanan  DATE          NOT NULL,
    Pelanggan_ID     INT(11)       NOT NULL,
    Total_Harga      DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (ID),
    FOREIGN KEY (Pelanggan_ID) REFERENCES pelanggan(ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. TABEL DETAIL PESANAN
-- ============================================
CREATE TABLE detail_pesanan (
    ID               INT(11)       NOT NULL AUTO_INCREMENT,
    Pesanan_ID       INT(11)       NOT NULL,
    Buku_ID          INT(11)       NOT NULL,
    Kuantitas        INT(11)       NOT NULL DEFAULT 1,
    Harga_Per_Satuan DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (ID),
    FOREIGN KEY (Pesanan_ID) REFERENCES pesanan(ID),
    FOREIGN KEY (Buku_ID)    REFERENCES buku(ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. TABEL USERS (Login/Logout)
-- ============================================
CREATE TABLE users (
    id           INT(11)       NOT NULL AUTO_INCREMENT,
    username     VARCHAR(50)   NOT NULL UNIQUE,
    password     VARCHAR(255)  NOT NULL,
    nama_lengkap VARCHAR(100)  NOT NULL DEFAULT '',
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. DATA CONTOH
-- ============================================
INSERT INTO buku (Judul, Penulis, Tahun_Terbit, Harga, Stok) VALUES
('Pemrograman PHP Dasar',     'Budi Raharjo',       2020, 85000.00, 15),
('Belajar MySQL dari Nol',    'Jubilee Enterprise', 2021, 75000.00, 20),
('Laravel untuk Pemula',      'Sandhika Galih',     2022, 95000.00, 10),
('JavaScript Modern',         'Ilham Permana',      2021, 80000.00, 18),
('Desain Web dengan CSS',     'Ahmad Fauzi',        2020, 70000.00, 25);

INSERT INTO pelanggan (Nama, Alamat, Email, Telepon) VALUES
('Andi Saputra', 'Jl. Merdeka No. 1, Jakarta',          'andi@email.com',  '081234567890'),
('Budi Santoso', 'Jl. Sudirman No. 5, Bandung',          'budi@email.com',  '082345678901'),
('Citra Dewi',   'Jl. Gatot Subroto No. 10, Surabaya',  'citra@email.com', '083456789012');

-- ============================================
-- 8. USER LOGIN
-- Username : admin
-- Password : admin123
-- ============================================
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$nC3255TwnDyCh7JH.80Kj.9kIf6HN.1xxQgIgHPuUnKAWAU1HVXK6', 'Administrator');
