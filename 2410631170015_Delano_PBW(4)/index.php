<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan Perhitungan Toko</title>
    <style>
        /*Gaya sederhana agar tampilan mirip kotak di gambar */
        .container {
            border: 2px solid black;
            padding: 20px;
            width: 450px;
            font-family: Arial, sans-serif; 
        }
        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }
    </style>
</head>
<body>
  
    <div class="container">
        <h3>Perhitungan Total Pembelian (dengan Array)</h3>
        <hr>

        <?php
        // 1. pajak 10% dijadikan KONSTANTA
        define("PAJAK", 0.10);

        // 2.Informasi harga barang disimpan dalam ARRAY 
        $barang = [
            "nama" => "Keyboard",
            "harga" => 150000
            ];
        
        // 3. Jumlah yang dibeli (dibuat VARIABLE)
        $JumlahBeli = 2;

        // 4. Perhitungan mengunakan OPERATOR ARITMATIKA
    $totalHarga = $barang["harga"] * $JumlahBeli; 
    $totalPajak = $totalHarga * PAJAK ; 
    $totalBayar = $totalHarga + $totalPajak; 

    //Menampilkan hasil (output)
    echo "Nama Barang: " .$barang["nama"] . "<br>";
    echo "Harga Satuan: Rp " . number_format($barang["harga"], 0, ',', ',') . "<br>";
    echo "Jumlah Beli: " .$JumlahBeli . "<br>";
    echo "Total Harga  (Sebelum Pajak): Rp " . number_format($totalHarga, 0, ',', ',') . "<br>";
    echo "Pajak (10): Rp " . number_format($totalPajak, 0, ',', ',') . "<br>";
    echo "<b>Total Bayar: Rp " . number_format($totalBayar, 0, ',', ',') . "</br>";
    ?>
</div>

</body>
</html>