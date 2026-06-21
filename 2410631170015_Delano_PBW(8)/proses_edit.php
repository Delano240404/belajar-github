<?php
require_once 'auth_check.php';
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $id      = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $judul   = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tahun   = (int)($_POST['tahun'] ?? 0);
    $harga   = $_POST['harga'] ?? '';
    $stok    = $_POST['stok'] ?? '';

    if (!$id || empty($judul) || !is_numeric($harga) || !is_numeric($stok)) {
        echo "Data tidak valid. <a href='index.php'>Kembali</a>";
        exit;
    }

    $harga = (float)$harga;
    $stok  = (int)$stok;

    $stmt = $conn->prepare("UPDATE buku SET Judul=?, Penulis=?, Tahun_Terbit=?, Harga=?, Stok=? WHERE ID=?");
    $stmt->bind_param("ssidii", $judul, $penulis, $tahun, $harga, $stok, $id);

    if ($stmt->execute()) {
        header("Location: index.php?pesan=update_sukses");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
