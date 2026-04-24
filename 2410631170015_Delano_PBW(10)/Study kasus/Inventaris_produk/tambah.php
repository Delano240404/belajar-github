<?php
// ============================================
// FILE: tambah.php
// Deskripsi: Form input & logika INSERT dengan Prepared Statements
// ============================================

require_once 'koneksi.php';

$errors  = [];
$sukses  = false;
$oldData = ['kode_produk' => '', 'nama_produk' => '', 'kategori' => '', 'harga' => '', 'stok' => ''];

// --- PROSES FORM SAAT SUBMIT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Ambil & sanitasi input dari form
    $kode_produk = trim($_POST['kode_produk'] ?? '');
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $kategori    = trim($_POST['kategori']    ?? '');
    $harga       = $_POST['harga'] ?? '';
    $stok        = $_POST['stok']  ?? '';

    // Simpan data lama untuk isi ulang form jika ada error
    $oldData = compact('kode_produk', 'nama_produk', 'kategori', 'harga', 'stok');

    // 2. Validasi input
    if (empty($kode_produk))  $errors['kode_produk'] = 'Kode produk tidak boleh kosong.';
    if (strlen($kode_produk) > 20) $errors['kode_produk'] = 'Kode produk maksimal 20 karakter.';
    if (empty($nama_produk))  $errors['nama_produk'] = 'Nama produk tidak boleh kosong.';
    if (empty($kategori))     $errors['kategori']    = 'Kategori tidak boleh kosong.';
    if (!is_numeric($harga) || (float)$harga < 0) $errors['harga'] = 'Harga harus berupa angka positif.';
    if (!is_numeric($stok)  || (int)$stok   < 0)  $errors['stok']  = 'Stok harus berupa angka bulat positif.';

    // 3. Jika validasi lolos, INSERT dengan Prepared Statements
    if (empty($errors)) {
        $harga = (float)$harga;
        $stok  = (int)$stok;

        // Prepared Statement untuk INSERT — AMAN dari SQL Injection
        $sql  = "INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);

        // Bind parameter: s=string, d=double, i=integer
        $stmt->bind_param('sssdi', $kode_produk, $nama_produk, $kategori, $harga, $stok);

        if ($stmt->execute()) {
            $stmt->close();
            $koneksi->close();
            // Redirect ke index dengan pesan sukses
            header('Location: index.php?pesan=tambah_sukses');
            exit;
        } else {
            $stmt->close();
            // Cek apakah error karena duplikasi kode produk (error code 1062)
            if ($koneksi->errno == 1062) {
                $errors['kode_produk'] = "Kode produk '$kode_produk' sudah terdaftar. Gunakan kode lain.";
            } else {
                header('Location: index.php?pesan=tambah_gagal');
                exit;
            }
        }
    }
}

// Ambil daftar kategori unik yang sudah ada untuk datalist
$stmtKat = $koneksi->prepare("SELECT DISTINCT kategori FROM produk ORDER BY kategori ASC");
$stmtKat->execute();
$resKat = $stmtKat->get_result();
$kategoriList = [];
while ($row = $resKat->fetch_assoc()) {
    $kategoriList[] = $row['kategori'];
}
$stmtKat->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk — Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .form-card {
            max-width: 680px;
            margin: 2rem auto;
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.09);
        }
        .form-card .card-header {
            background: linear-gradient(135deg, #198754 0%, #0d6efd 100%);
            border-radius: 14px 14px 0 0;
            padding: 1.25rem 1.5rem;
        }
        .form-label { font-weight: 600; font-size: 0.88rem; color: #495057; }
        .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15); }
        .input-group-text { background-color: #f8f9fa; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <i class="bi bi-box-seam-fill text-primary fs-5"></i>
            <span>Inventaris <span class="text-primary">Produk</span></span>
        </a>
    </div>
</nav>

<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-fill"></i> Beranda</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </nav>

    <div class="card form-card">
        <!-- Header Form -->
        <div class="card-header text-white">
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Produk Baru</h5>
            <small class="opacity-75">Isi semua field yang bertanda bintang (*)</small>
        </div>

        <div class="card-body p-4">

            <!-- Tampilkan ringkasan error jika ada -->
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger d-flex gap-2 align-items-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                <div>
                    <strong>Terdapat <?= count($errors) ?> kesalahan. Perbaiki sebelum menyimpan:</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Form Tambah Produk -->
            <!-- Aksi menuju tambah.php itu sendiri (self-processing) -->
            <form action="tambah.php" method="POST" novalidate>

                <!-- Kode Produk -->
                <div class="mb-3">
                    <label for="kode_produk" class="form-label">Kode Produk <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                        <input
                            type="text"
                            id="kode_produk"
                            name="kode_produk"
                            class="form-control <?= isset($errors['kode_produk']) ? 'is-invalid' : '' ?>"
                            placeholder="Contoh: PRD-011"
                            value="<?= htmlspecialchars($oldData['kode_produk']) ?>"
                            maxlength="20"
                            required
                        >
                        <?php if (isset($errors['kode_produk'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['kode_produk']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text">Kode unik untuk mengidentifikasi produk (maks. 20 karakter).</div>
                </div>

                <!-- Nama Produk -->
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                        <input
                            type="text"
                            id="nama_produk"
                            name="nama_produk"
                            class="form-control <?= isset($errors['nama_produk']) ? 'is-invalid' : '' ?>"
                            placeholder="Contoh: Laptop ASUS VivoBook 15"
                            value="<?= htmlspecialchars($oldData['nama_produk']) ?>"
                            maxlength="100"
                            required
                        >
                        <?php if (isset($errors['nama_produk'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['nama_produk']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-folder-fill"></i></span>
                        <input
                            type="text"
                            id="kategori"
                            name="kategori"
                            class="form-control <?= isset($errors['kategori']) ? 'is-invalid' : '' ?>"
                            placeholder="Contoh: Elektronik"
                            value="<?= htmlspecialchars($oldData['kategori']) ?>"
                            list="kategoriSuggestions"
                            maxlength="50"
                            required
                        >
                        <?php if (isset($errors['kategori'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['kategori']) ?></div>
                        <?php endif; ?>
                    </div>
                    <!-- Datalist: saran kategori dari database -->
                    <datalist id="kategoriSuggestions">
                        <?php foreach ($kategoriList as $kat): ?>
                            <option value="<?= htmlspecialchars($kat) ?>">
                        <?php endforeach; ?>
                    </datalist>
                    <div class="form-text">Ketik atau pilih dari kategori yang ada.</div>
                </div>

                <!-- Harga & Stok (2 kolom) -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input
                                type="number"
                                id="harga"
                                name="harga"
                                class="form-control <?= isset($errors['harga']) ? 'is-invalid' : '' ?>"
                                placeholder="0"
                                value="<?= htmlspecialchars($oldData['harga']) ?>"
                                min="0"
                                step="100"
                                required
                            >
                            <?php if (isset($errors['harga'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['harga']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="stok" class="form-label">Stok (Unit) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                            <input
                                type="number"
                                id="stok"
                                name="stok"
                                class="form-control <?= isset($errors['stok']) ? 'is-invalid' : '' ?>"
                                placeholder="0"
                                value="<?= htmlspecialchars($oldData['stok']) ?>"
                                min="0"
                                required
                            >
                            <?php if (isset($errors['stok'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['stok']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="reset" class="btn btn-outline-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save-fill me-1"></i> Simpan Produk
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $koneksi->close(); ?>
