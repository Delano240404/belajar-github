-- ============================================
-- DATABASE: Sistem Manajemen Inventaris Produk
-- Mata Kuliah: Pemrograman Web PHP & MariaDB
-- Pertemuan 10 - CRUD dengan Prepared Statements
-- ============================================

-- Buat database
CREATE DATABASE IF NOT EXISTS db_inventaris
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Gunakan database
USE db_inventaris;

-- Hapus tabel jika sudah ada (untuk keperluan fresh install)
DROP TABLE IF EXISTS produk;

-- Buat tabel produk (minimal 5 kolom sesuai ketentuan)
CREATE TABLE produk (
    id          INT(11)         NOT NULL AUTO_INCREMENT,
    kode_produk VARCHAR(20)     NOT NULL UNIQUE,
    nama_produk VARCHAR(100)    NOT NULL,
    kategori    VARCHAR(50)     NOT NULL,
    harga       DECIMAL(15, 2)  NOT NULL DEFAULT 0.00,
    stok        INT(11)         NOT NULL DEFAULT 0,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DATA CONTOH (SAMPLE DATA)
-- ============================================
INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok) VALUES
('PRD-001', 'Laptop ASUS VivoBook 15', 'Elektronik', 8500000.00, 12),
('PRD-002', 'Mouse Wireless Logitech M185', 'Aksesoris', 185000.00, 45),
('PRD-003', 'Keyboard Mechanical Redragon', 'Aksesoris', 650000.00, 28),
('PRD-004', 'Monitor LG 24 inch Full HD', 'Elektronik', 2350000.00, 8),
('PRD-005', 'Headset Gaming Rexus HX20', 'Aksesoris', 320000.00, 33),
('PRD-006', 'Flash Drive SanDisk 64GB', 'Storage', 120000.00, 60),
('PRD-007', 'SSD External WD 1TB', 'Storage', 1450000.00, 15),
('PRD-008', 'Webcam Logitech C270', 'Aksesoris', 450000.00, 20),
('PRD-009', 'Printer Canon PIXMA E410', 'Elektronik', 1250000.00, 7),
('PRD-010', 'UPS APC Back-UPS 650VA', 'Elektronik', 950000.00, 10);

-- ============================================
-- TABEL USERS (Autentikasi Login)
-- ============================================
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id         INT(11)      NOT NULL AUTO_INCREMENT,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL DEFAULT '',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User default: admin / admin123
-- Password di-hash dengan password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$12$4fNkN3Fb3V3z0M/bXQe7s.mEQ5n3Xr5z9VqB2mK7J6NKWQ8s3BLSe', 'Administrator');
-- Catatan: Jalankan generate_hash.php untuk membuat hash password baru
