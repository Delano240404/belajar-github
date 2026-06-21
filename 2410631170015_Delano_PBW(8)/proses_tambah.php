<?php
require_once 'auth_check.php';
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul   = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tahun   = $_POST['tahun'] ?? '';
    $harga   = $_POST['harga'] ?? '';
    $stok    = $_POST['stok'] ?? '';

    // Validasi sederhana
    if (empty($judul) || empty($penulis) || !is_numeric($harga) || !is_numeric($stok)) {
        echo "Data tidak valid. <a href='tambah.php'>Kembali</a>";
        exit;
    }

    $harga = (float)$harga;
    $stok  = (int)$stok;
    $tahun = (int)$tahun;

    $stmt = $conn->prepare("INSERT INTO buku (Judul, Penulis, Tahun_Terbit, Harga, Stok) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidi", $judul, $penulis, $tahun, $harga, $stok);

    if ($stmt->execute()) {
        header("Location: index.php?pesan=tambah_sukses");
        exit;
    } else {
        echo "Gagal menambah data: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
