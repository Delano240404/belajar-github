<?php
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { padding: 6px; width: 250px; }
        button { margin-top: 15px; padding: 8px 16px; }
    </style>
</head>
<body>
    <h2>Tambah Buku Baru</h2>
    <form action="proses_tambah.php" method="POST">
        <label>Judul</label>
        <input type="text" name="judul" required>

        <label>Penulis</label>
        <input type="text" name="penulis" required>

        <label>Tahun Terbit</label>
        <input type="number" name="tahun">

        <label>Harga</label>
        <input type="number" step="0.01" name="harga">

        <label>Stok</label>
        <input type="number" name="stok">

        <br><button type="submit">Simpan Buku</button>
    </form>
    <br><a href="index.php">Kembali</a>
</body>
</html>
