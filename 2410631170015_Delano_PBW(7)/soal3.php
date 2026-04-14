<?php
echo "<h3>Soal 3: Foreach - Daftar Hewan</h3>";
?>

<form method="POST" action="">
    <div style="margin-bottom: 10px;">
        <label for="hewan">Masukkan nama hewan (pisahkan dengan koma):</label><br>
        <input type="text" id="hewan" name="hewan" placeholder="Contoh: Kucing, Gajah, Singa" required style="padding: 5px; width: 300px;">
    </div>
    <button type="submit" name="proses_soal3" style="padding: 7px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Buat Daftar</button>
</form>

<hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

<?php
if (isset($_POST['proses_soal3'])) {
    // Mengambil input string
    $input_hewan = $_POST['hewan'];
    
    // Fungsi explode() digunakan untuk memecah string menjadi array berdasarkan tanda koma
    $daftar_hewan = explode(',', $input_hewan);

    echo "Daftar Hewan:<br>";
    echo "<ul>";
    foreach ($daftar_hewan as $hewan) {
        // Fungsi trim() digunakan untuk menghapus spasi berlebih di awal/akhir kata
        echo "<li>" . trim($hewan) . "</li>";
    }
    echo "</ul>";
}
?>