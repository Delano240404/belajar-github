<?php
// ============================================
// FILE: koneksi.php
// Deskripsi: File koneksi ke database MariaDB/MySQL
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Ganti sesuai username database Anda
define('DB_PASS', '');           // Ganti sesuai password database Anda
define('DB_NAME', 'db_inventaris');
define('DB_PORT', 3306);

// Buat koneksi menggunakan MySQLi (Object-Oriented)
$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Cek apakah koneksi berhasil
if ($koneksi->connect_error) {
    // Hentikan eksekusi dan tampilkan pesan error
    die('<div style="font-family:sans-serif;padding:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:5px;margin:20px;">
            <strong>❌ Koneksi Database Gagal!</strong><br>
            Error: ' . htmlspecialchars($koneksi->connect_error) . '<br><br>
            <small>Pastikan server database berjalan dan konfigurasi pada <code>koneksi.php</code> sudah benar.</small>
         </div>');
}

// Set charset ke utf8mb4 untuk mendukung karakter Unicode
$koneksi->set_charset('utf8mb4');
