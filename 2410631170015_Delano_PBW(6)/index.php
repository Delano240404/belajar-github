<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran UKT Mahasiswa</title>
    <style>
        body { font-family: Arial; background: #f4f7f6; padding: 20px; }
        .container { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .card { background: white; padding: 20px; border-radius: 10px; width: 380px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; }
        button { width: 100%; padding: 10px; background: orange; color: white; border: none; }
        table { width: 100%; margin-top: 15px; }
        td { padding: 5px; }
        .total { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">

<!-- FORM -->
<div class="card">
    <h2>Input UKT</h2>
    <form method="POST">
        <input type="text" name="npm" placeholder="NPM" required>
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="prodi" placeholder="Prodi" required>
        <input type="number" name="semester" placeholder="Semester" required>
        <input type="number" name="biaya_ukt" placeholder="Biaya UKT" required>
        <button type="submit" name="proses">Proses</button>
    </form>
</div>

<?php
// CEK apakah tombol ditekan
if (isset($_POST['proses'])) {

    // AMAN dari error
    $npm = isset($_POST['npm']) ? htmlspecialchars($_POST['npm']) : '';
    $nama = isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '';
    $prodi = isset($_POST['prodi']) ? htmlspecialchars($_POST['prodi']) : '';
    $semester = isset($_POST['semester']) ? (int)$_POST['semester'] : 0;
    $biaya_ukt = isset($_POST['biaya_ukt']) ? (float)$_POST['biaya_ukt'] : 0;

    // VALIDASI
    if ($semester <= 0 || $biaya_ukt <= 0) {
        echo "<div class='card'><p style='color:red'>Input tidak valid!</p></div>";
    } else {

        // LOGIKA DISKON
        if ($biaya_ukt >= 5000000) {
            if ($semester > 8) {
                $persen = 15;
                $ket = "Mahasiswa Akhir";
            } else {
                $persen = 10;
                $ket = "Diskon Reguler";
            }
        } elseif ($biaya_ukt >= 2000000) {
            $persen = 5;
            $ket = "Diskon Subsidi";
        } else {
            $persen = 0;
            $ket = "Tidak Ada Diskon";
        }

        // HITUNG
        $potongan = ($persen / 100) * $biaya_ukt;
        $total = $biaya_ukt - $potongan;

        // FORMAT RUPIAH
        function rupiah($angka) {
            return "Rp " . number_format($angka, 0, ',', '.');
        }
?>

<!-- OUTPUT -->
<div class="card">
    <h2>Hasil</h2>
    <table>
        <tr><td>NPM</td><td>: <?= $npm ?></td></tr>
        <tr><td>Nama</td><td>: <?= $nama ?></td></tr>
        <tr><td>Prodi</td><td>: <?= $prodi ?></td></tr>
        <tr><td>Semester</td><td>: <?= $semester ?></td></tr>
        <tr><td>Biaya</td><td>: <?= rupiah($biaya_ukt) ?></td></tr>
        <tr><td>Diskon</td><td>: <?= $persen ?>% (<?= $ket ?>)</td></tr>
        <tr><td>Potongan</td><td>: - <?= rupiah($potongan) ?></td></tr>
        <tr class="total"><td>Total Bayar</td><td>: <?= rupiah($total) ?></td></tr>
    </table>
</div>

<?php 
    } // end validasi
} // end isset
?>

</div>

</body>
</html>