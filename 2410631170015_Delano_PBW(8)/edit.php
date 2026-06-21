<?php
require_once 'auth_check.php';
include 'koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM buku WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { padding: 6px; width: 250px; }
        button { margin-top: 15px; padding: 8px 16px; }
    </style>
</head>
<body>
    <h2>Edit Data Buku</h2>
    <form action="proses_edit.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $data['ID']; ?>">

        <label>Judul</label>
        <input type="text" name="judul" value="<?php echo htmlspecialchars($data['Judul']); ?>">

        <label>Penulis</label>
        <input type="text" name="penulis" value="<?php echo htmlspecialchars($data['Penulis']); ?>">

        <label>Tahun Terbit</label>
        <input type="number" name="tahun" value="<?php echo $data['Tahun_Terbit']; ?>">

        <label>Harga</label>
        <input type="number" step="0.01" name="harga" value="<?php echo $data['Harga']; ?>">

        <label>Stok</label>
        <input type="number" name="stok" value="<?php echo $data['Stok']; ?>">

        <br><button type="submit" name="submit">Update Data</button>
    </form>
    <br><a href="index.php">Kembali</a>
</body>
</html>
