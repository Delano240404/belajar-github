<?php
// ============================================
// FILE: auth_check.php
// Deskripsi: Guard session - tempel require_once file ini
//            di bagian PALING ATAS setiap halaman yang dilindungi
// ============================================

session_start();

// Timeout otomatis: 2 jam tidak aktif = logout
define('SESSION_TIMEOUT', 7200);

if (!isset($_SESSION['user_id'])) {
    // Belum login
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
    // Session expired
    session_destroy();
    header('Location: login.php?pesan=timeout');
    exit;
}

// Perbarui waktu aktivitas terakhir
$_SESSION['login_time'] = time();
