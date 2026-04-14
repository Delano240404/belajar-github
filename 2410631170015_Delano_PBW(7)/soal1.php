<?php
echo "<h3>Soal 1: Switch Case - Jenis Kendaraan</h3>";
?>

<form method="POST" action="">
    <div style="margin-bottom: 10px;">
        <label for="jumlah_roda">Masukkan Jumlah Roda:</label><br>
        <input type="number" id="jumlah_roda" name="jumlah_roda" required style="padding: 5px; width: 200px;">
    </div>
    <button type="submit" name="proses_soal1" style="padding: 7px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Cek Kendaraan</button>
</form>

<hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

<?php
if (isset($_POST['proses_soal1'])) {
    $jumlah_roda = $_POST['jumlah_roda'];
    
    echo "Jumlah roda yang diinput: <b>" . $jumlah_roda . "</b><br>";
    echo "Jenis Kendaraan: <b>";
    
    switch ($jumlah_roda) {
        case 2:
            echo "Sepeda Motor / Sepeda";
            break;
        case 3:
            echo "Becak / Bajaj";
            break;
        case 4:
            echo "Mobil";
            break;
        case 6:
        case 8:
        case 10:
            echo "Truk / Bus";
            break;
        default:
            echo "Kendaraan tidak teridentifikasi atau kendaraan khusus";
            break;
    }
    echo "</b>";
}
?>