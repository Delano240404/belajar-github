<?php
echo "<h3>Soal 2: For Loop - Bilangan Genap</h3>";
?>

<form method="POST" action="">
    <div style="margin-bottom: 10px;">
        <label for="angka_awal">Angka Awal:</label><br>
        <input type="number" id="angka_awal" name="angka_awal" required style="padding: 5px; width: 200px;">
    </div>
    
    <div style="margin-bottom: 10px;">
        <label for="angka_akhir">Angka Akhir:</label><br>
        <input type="number" id="angka_akhir" name="angka_akhir" required style="padding: 5px; width: 200px;">
    </div>
    
    <button type="submit" name="proses_soal2" style="padding: 7px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Tampilkan</button>
</form>

<hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

<?php
if (isset($_POST['proses_soal2'])) {
    $awal = $_POST['angka_awal'];
    $akhir = $_POST['angka_akhir'];

    if ($awal > $akhir) {
        echo "<span style='color: red;'>Error: Angka awal tidak boleh lebih besar dari angka akhir!</span>";
    } else {
        echo "Bilangan genap dari <b>$awal</b> sampai <b>$akhir</b> adalah:<br><br>";
        echo "<div style='font-size: 18px; color: #2c3e50;'>";
        
        for ($i = $awal; $i <= $akhir; $i++) {
            if ($i % 2 == 0) {
                echo $i . " ";
            }
        }
        echo "</div>";
    }
}
?>