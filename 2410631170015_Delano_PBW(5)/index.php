<!DOCTYPE html>
<html>
<head>
    <title>Perbaikan Halaman 43 - Evaluasi Nilai</title>
    <style>
        table { border-collapse: collapse; width: 350px; margin-top: 20px; }
        td, th { border: 1px solid #000; padding: 10px; }
        th { background-color: #eee; }
        .lulus { color: green; font-weight: bold; }
        .gagal { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Form Evaluasi Mahasiswa</h2>
    <form method="POST">
        Nama: <input type="text" name="nama" required><br><br>
        Nilai: <input type="number" name="nilai" required><br><br>
        <button type="submit" name="proses">Cek Hasil</button>
    </form>

    <?php
    if (isset($_POST['proses'])) {
        $nama = $_POST['nama'];
        $nilai = $_POST['nilai'];

        // Logika penentuan Grade dan Predikat
        switch (true) {
            case ($nilai >= 85):
                $grade = "A";
                $predikat = "Sangat Memuaskan";
                break;
            case ($nilai >= 75):
                $grade = "B";
                $predikat = "Memuaskan";
                break;
            case ($nilai >= 60):
                $grade = "C";
                $predikat = "Cukup";
                break;
            case ($nilai >= 45):
                $grade = "D";
                $predikat = "Kurang";
                break;
            default:
                $grade = "E";
                $predikat = "Sangat Kurang";
                break;
        }

        // Penentuan Status
        $status = ($nilai >= 60) ? "<span class='lulus'>LULUS</span>" : "<span class='gagal'>GAGAL</span>";

        // Menampilkan Hasil
        echo "<table>
                <tr><th colspan='2'>Resume Hasil</th></tr>
                <tr><td>Nama</td><td>$nama</td></tr>
                <tr><td>Nilai</td><td>$nilai</td></tr>
                <tr><td>Grade</td><td>$grade</td></tr>
                <tr><td>Predikat</td><td>$predikat</td></tr>
                <tr><td>Status</td><td>$status</td></tr>
              </table>";
    }
    ?>
</body>
</html>