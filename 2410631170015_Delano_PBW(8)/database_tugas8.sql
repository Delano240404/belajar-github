-- ============================================
-- DATABASE: Aplikasi Pengelolaan Buku (Tugas 8)
-- ============================================

CREATE DATABASE IF NOT EXISTS aplikasi_pengelolaan_buku
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE aplikasi_pengelolaan_buku;

DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS users;

-- Tabel Buku
CREATE TABLE buku (
    ID           INT(11)        NOT NULL AUTO_INCREMENT,
    Judul        VARCHAR(255)   NOT NULL,
    Penulis      VARCHAR(100)   NOT NULL,
    Tahun_Terbit INT(4)         NOT NULL,
    Harga        DECIMAL(15,2)  NOT NULL DEFAULT 0.00,
    Stok         INT(11)        NOT NULL DEFAULT 0,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Users (Login)
CREATE TABLE users (
    id           INT(11)       NOT NULL AUTO_INCREMENT,
    username     VARCHAR(50)   NOT NULL UNIQUE,
    password     VARCHAR(255)  NOT NULL,
    nama_lengkap VARCHAR(100)  NOT NULL DEFAULT '',
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data contoh buku
INSERT INTO buku (Judul, Penulis, Tahun_Terbit, Harga, Stok) VALUES
('Pemrograman PHP Dasar',  'Budi Raharjo',       2020, 85000.00, 15),
('Belajar MySQL dari Nol', 'Jubilee Enterprise', 2021, 75000.00, 20),
('Laravel untuk Pemula',   'Sandhika Galih',     2022, 95000.00, 10);

-- User default: admin / admin123
-- Hash dibuat dengan password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$nC3255TwnDyCh7JH.80Kj.9kIf6HN.1xxQgIgHPuUnKAWAU1HVXK6', 'Administrator');
