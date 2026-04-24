<?php
// ============================================
// FILE: hapus.php
// Deskripsi: Logika DELETE dengan Prepared Statements
// ============================================

require_once 'koneksi.php';

// --- VALIDASI ID ---
// Ambil ID dari URL, pastikan berupa integer positif
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Jika ID tidak valid, langsung redirect ke halaman utama
if (!$id || $id <= 0) {
    header('Location: index.php');
    exit;
}

// --- PASTIKAN PRODUK ADA SEBELUM DIHAPUS ---
// Prepared Statement untuk cek keberadaan produk
$stmtCek = $koneksi->prepare("SELECT id, nama_produk FROM produk WHERE id = ? LIMIT 1");
$stmtCek->bind_param('i', $id);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek->num_rows === 0) {
    // Produk tidak ditemukan, redirect ke halaman utama
    $stmtCek->close();
    $koneksi->close();
    header('Location: index.php');
    exit;
}

$stmtCek->close();

// --- EKSEKUSI DELETE DENGAN PREPARED STATEMENTS ---
// Prepared Statement untuk DELETE — AMAN dari SQL Injection
$sql  = "DELETE FROM produk WHERE id = ?";
$stmt = $koneksi->prepare($sql);

// Bind parameter ID sebagai integer
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    // Hapus berhasil
    $stmt->close();
    $koneksi->close();
    header('Location: index.php?pesan=hapus_sukses');
} else {
    // Hapus gagal
    $stmt->close();
    $koneksi->close();
    header('Location: index.php?pesan=hapus_gagal');
}

exit;
