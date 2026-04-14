<?php
echo "<h3>Soal 4: Ternary Operator - Genap atau Ganjil</h3>";
?>

<form method="POST" action="">
    <div style="margin-bottom: 10px;">
        <label for="angka">Masukkan Angka:</label><br>
        <input type="number" id="angka" name="angka" required style="padding: 5px; width: 200px;">
    </div>
    <button type="submit" name="proses_soal4" style="padding: 7px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Cek Angka</button>
</form>

<hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

<?php
if (isset($_POST['proses_soal4'])) {
    $angka = $_POST['angka'];

    // Ternary operator
    $status = ($angka % 2 == 0) ? "Genap" : "Ganjil";

    echo "Angka <b>" . $angka . "</b> adalah bilangan <b>" . $status . "</b>.";
}
?>