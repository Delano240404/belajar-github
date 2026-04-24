-- ============================================================
-- STUDI KASUS: APLIKASI PENGELOLAAN BUKU
-- Database: pemrograman_web_contoh
-- Teknologi: PHP & MySQL | Pertemuan 10
-- ============================================================

-- 1. MEMBUAT DATABASE
-- ============================================================
DROP DATABASE IF EXISTS pemrograman_web_contoh;
CREATE DATABASE pemrograman_web_contoh;
USE pemrograman_web_contoh;

-- 2. MEMBUAT TABEL BUKU
-- ============================================================
CREATE TABLE buku (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Judul VARCHAR(255) NOT NULL,
    Penulis VARCHAR(255) NOT NULL,
    Tahun_Terbit INT,
    Harga DECIMAL(10,2) NOT NULL,
    Stok INT NOT NULL DEFAULT 0,
    CONSTRAINT chk_harga CHECK (Harga > 0),
    CONSTRAINT chk_stok CHECK (Stok >= 0)
);

-- 3. MEMBUAT TABEL PELANGGAN
-- ============================================================
CREATE TABLE pelanggan (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nama VARCHAR(255) NOT NULL,
    Alamat VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Telepon VARCHAR(20) NOT NULL
);

-- 4. MEMBUAT TABEL PESANAN
-- ============================================================
CREATE TABLE pesanan (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Tanggal_Pesanan DATE NOT NULL,
    Pelanggan_ID INT NOT NULL,
    Total_Harga DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (Pelanggan_ID) REFERENCES pelanggan(ID) ON DELETE RESTRICT,
    CONSTRAINT chk_total_harga CHECK (Total_Harga >= 0),
    INDEX idx_pelanggan (Pelanggan_ID),
    INDEX idx_tanggal (Tanggal_Pesanan)
);

-- 5. MEMBUAT TABEL DETAIL_PESANAN
-- ============================================================
CREATE TABLE detail_pesanan (
    Pesanan_ID INT NOT NULL,
    Buku_ID INT NOT NULL,
    Kuantitas INT NOT NULL,
    Harga_Per_Satuan DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (Pesanan_ID, Buku_ID),
    FOREIGN KEY (Pesanan_ID) REFERENCES pesanan(ID) ON DELETE CASCADE,
    FOREIGN KEY (Buku_ID) REFERENCES buku(ID) ON DELETE RESTRICT,
    CONSTRAINT chk_kuantitas CHECK (Kuantitas > 0),
    CONSTRAINT chk_hps CHECK (Harga_Per_Satuan > 0),
    INDEX idx_buku (Buku_ID)
);

-- 6. INSERT DATA CONTOH - TABEL BUKU
-- ============================================================
INSERT INTO buku (Judul, Penulis, Tahun_Terbit, Harga, Stok) VALUES
('Laskar Pelangi', 'Andrea Hirata', 2005, 75000.00, 50),
('Ayah', 'Gusti Agung Wesaka Puja', 2015, 65000.00, 30),
('Pergi', 'Tere Liye', 2014, 72000.00, 45),
('Cantik itu Luka', 'Eka Kurniawan', 2002, 68000.00, 25),
('Bumi Manusia', 'Pramoedya Ananta Toer', 1980, 85000.00, 15),
('Hujan', 'Tere Liye', 2013, 70000.00, 40),
('Seorang Laki-Laki', 'Budi Darma', 1999, 60000.00, 20);

-- 7. INSERT DATA CONTOH - TABEL PELANGGAN
-- ============================================================
INSERT INTO pelanggan (Nama, Alamat, Email, Telepon) VALUES
('Budi Santoso', 'Jl. Merdeka No. 123, Jakarta Pusat', 'budi.santoso@email.com', '081234567890'),
('Siti Nurhaliza', 'Jl. Ahmad Yani No. 45, Bandung', 'siti.nurhaliza@email.com', '081987654321'),
('Ahmad Wijaya', 'Jl. Sudirman No. 78, Surabaya', 'ahmad.wijaya@email.com', '082111223344'),
('Rina Putri', 'Jl. Gatot Subroto No. 56, Medan', 'rina.putri@email.com', '082555666777'),
('Doni Hermawan', 'Jl. Diponegoro No. 34, Yogyakarta', 'doni.hermawan@email.com', '082888999000');

-- 8. INSERT DATA CONTOH - TABEL PESANAN
-- ============================================================
INSERT INTO pesanan (Tanggal_Pesanan, Pelanggan_ID, Total_Harga) VALUES
('2024-01-15', 1, 147000.00),
('2024-01-16', 2, 72000.00),
('2024-01-17', 3, 294000.00),
('2024-01-18', 1, 72000.00),
('2024-01-19', 4, 210000.00),
('2024-01-20', 5, 217000.00),
('2024-01-21', 2, 140000.00);

-- 9. INSERT DATA CONTOH - TABEL DETAIL_PESANAN
-- ============================================================
INSERT INTO detail_pesanan (Pesanan_ID, Buku_ID, Kuantitas, Harga_Per_Satuan) VALUES
-- Pesanan 1 (Budi Santoso) - Total: 147.000
(1, 1, 2, 75000.00),
(1, 2, 1, 65000.00),
-- Pesanan 2 (Siti Nurhaliza) - Total: 72.000
(2, 3, 1, 72000.00),
-- Pesanan 3 (Ahmad Wijaya) - Total: 294.000
(3, 1, 2, 75000.00),
(3, 2, 3, 65000.00),
(3, 3, 1, 72000.00),
-- Pesanan 4 (Budi Santoso) - Total: 72.000
(4, 5, 1, 85000.00),
-- Pesanan 5 (Rina Putri) - Total: 210.000
(5, 1, 1, 75000.00),
(5, 2, 2, 65000.00),
-- Pesanan 6 (Doni Hermawan) - Total: 217.000
(6, 3, 2, 72000.00),
(6, 4, 1, 68000.00),
(6, 6, 1, 70000.00),
-- Pesanan 7 (Siti Nurhaliza) - Total: 140.000
(7, 1, 1, 75000.00),
(7, 7, 1, 60000.00);

-- ============================================================
-- QUERY STANDAR - UNTUK VERIFIKASI DATA
-- ============================================================

-- Tampilkan semua buku
-- SELECT * FROM buku;

-- Tampilkan semua pelanggan
-- SELECT * FROM pelanggan;

-- Tampilkan semua pesanan
-- SELECT * FROM pesanan;

-- Tampilkan detail pesanan
-- SELECT * FROM detail_pesanan;

-- ============================================================
-- QUERY ANALISIS - LAPORAN PENJUALAN
-- ============================================================

-- Query 1: Lihat Semua Pesanan dengan Data Pelanggan
-- SELECT 
--     p.ID AS Pesanan_ID,
--     p.Tanggal_Pesanan,
--     pl.Nama AS Nama_Pelanggan,
--     pl.Email,
--     p.Total_Harga
-- FROM pesanan p
-- JOIN pelanggan pl ON p.Pelanggan_ID = pl.ID
-- ORDER BY p.Tanggal_Pesanan DESC;

-- Query 2: Detail Pesanan Lengkap dengan Buku dan Pelanggan
-- SELECT 
--     p.ID AS Pesanan_ID,
--     p.Tanggal_Pesanan,
--     pl.Nama AS Nama_Pelanggan,
--     b.Judul AS Judul_Buku,
--     b.Penulis,
--     dp.Kuantitas,
--     dp.Harga_Per_Satuan,
--     (dp.Kuantitas * dp.Harga_Per_Satuan) AS Subtotal
-- FROM detail_pesanan dp
-- JOIN pesanan p ON dp.Pesanan_ID = p.ID
-- JOIN buku b ON dp.Buku_ID = b.ID
-- JOIN pelanggan pl ON p.Pelanggan_ID = pl.ID
-- ORDER BY p.ID, b.Judul;

-- Query 3: Total Penjualan per Pelanggan
-- SELECT 
--     pl.Nama,
--     COUNT(p.ID) AS Jumlah_Pesanan,
--     SUM(p.Total_Harga) AS Total_Belanja,
--     AVG(p.Total_Harga) AS Rata_Rata_Pesanan
-- FROM pelanggan pl
-- LEFT JOIN pesanan p ON pl.ID = p.Pelanggan_ID
-- GROUP BY pl.ID, pl.Nama
-- ORDER BY Total_Belanja DESC;

-- Query 4: Buku Terlaris
-- SELECT 
--     b.ID,
--     b.Judul,
--     b.Penulis,
--     b.Harga,
--     b.Stok,
--     SUM(dp.Kuantitas) AS Total_Terjual,
--     SUM(dp.Kuantitas * dp.Harga_Per_Satuan) AS Nilai_Penjualan
-- FROM buku b
-- LEFT JOIN detail_pesanan dp ON b.ID = dp.Buku_ID
-- GROUP BY b.ID, b.Judul, b.Penulis, b.Harga, b.Stok
-- ORDER BY Total_Terjual DESC;

-- Query 5: Penjualan Harian
-- SELECT 
--     p.Tanggal_Pesanan,
--     COUNT(p.ID) AS Jumlah_Pesanan,
--     SUM(p.Total_Harga) AS Total_Penjualan
-- FROM pesanan p
-- GROUP BY p.Tanggal_Pesanan
-- ORDER BY p.Tanggal_Pesanan DESC;

-- Query 6: Detail Pesanan Tertentu
-- SELECT 
--     p.ID AS Pesanan_ID,
--     p.Tanggal_Pesanan,
--     pl.Nama,
--     b.Judul,
--     dp.Kuantitas,
--     dp.Harga_Per_Satuan
-- FROM detail_pesanan dp
-- JOIN pesanan p ON dp.Pesanan_ID = p.ID
-- JOIN buku b ON dp.Buku_ID = b.ID
-- JOIN pelanggan pl ON p.Pelanggan_ID = pl.ID
-- WHERE p.ID = 1;

-- ============================================================
-- STATISTIK DATABASE
-- ============================================================
-- Total buku: 7
-- Total pelanggan: 5
-- Total pesanan: 7
-- Total detail pesanan: 13
-- ============================================================
