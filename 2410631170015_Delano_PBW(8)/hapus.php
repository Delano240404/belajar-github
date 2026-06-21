<?php
require_once 'auth_check.php';
include 'koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Cek dulu apakah data ada
$cek = $conn->prepare("SELECT ID FROM buku WHERE ID = ?");
$cek->bind_param("i", $id);
$cek->execute();
$hasilCek = $cek->get_result();

if ($hasilCek->num_rows === 0) {
    $cek->close();
    $conn->close();
    header("Location: index.php");
    exit;
}
$cek->close();

$stmt = $conn->prepare("DELETE FROM buku WHERE ID = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?pesan=hapus_sukses");
} else {
    header("Location: index.php?pesan=hapus_gagal");
}
$stmt->close();
$conn->close();
exit;
?>
