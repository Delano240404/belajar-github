<?php
require_once 'auth_check.php';
include 'koneksi.php';

$sql    = "SELECT * FROM buku ORDER BY ID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 12px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger  { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="topbar">
        <h2>Daftar Koleksi Buku</h2>
        <div>
            Halo, <b><?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?></b>
            | <a href="logout.php" onclick="return confirm('Yakin logout?')">Logout</a>
        </div>
    </div>

    <?php if (isset($_GET['pesan'])):
        $pesanMap = [
            'tambah_sukses' => ['success', 'Buku berhasil ditambahkan.'],
            'update_sukses' => ['success', 'Data buku berhasil diperbarui.'],
            'hapus_sukses'  => ['success', 'Buku berhasil dihapus.'],
            'hapus_gagal'   => ['danger',  'Gagal menghapus buku.'],
        ];
        $kode = $_GET['pesan'];
        if (isset($pesanMap[$kode])):
            [$tipe, $teks] = $pesanMap[$kode];
    ?>
        <div class="alert alert-<?php echo $tipe; ?>"><?php echo $teks; ?></div>
    <?php endif; endif; ?>

    <a href="tambah.php">[+] Tambah Buku Baru</a><br><br>
    <table border="1" cellpadding="10">
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Judul']); ?></td>
            <td><?php echo htmlspecialchars($row['Penulis']); ?></td>
            <td><?php echo $row['Tahun_Terbit']; ?></td>
            <td><?php echo number_format($row['Harga'], 2); ?></td>
            <td><?php echo $row['Stok']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['ID']; ?>">Edit</a> |
                <a href="hapus.php?id=<?php echo $row['ID']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
