<?php
// ============================================
// FILE: index.php
// Deskripsi: Halaman utama - Tampil data, Pencarian, dan navigasi CRUD
// ============================================

// PROTEKSI SESSION - Wajib login sebelum akses halaman ini
require_once 'auth_check.php';

require_once 'koneksi.php';

// --- LOGIKA PENCARIAN (Search dengan WHERE LIKE) ---
$keyword   = '';
$kondisiWhere = '';

if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $keyword = trim($_GET['search']);
    $kondisiWhere = "WHERE kode_produk LIKE ? OR nama_produk LIKE ? OR kategori LIKE ?";
}

// --- AMBIL DATA DENGAN PREPARED STATEMENTS ---
$sql  = "SELECT * FROM produk {$kondisiWhere} ORDER BY id DESC";
$stmt = $koneksi->prepare($sql);

if ($keyword !== '') {
    $likeKeyword = "%{$keyword}%";
    // Bind 3 parameter untuk 3 kolom pencarian
    $stmt->bind_param('sss', $likeKeyword, $likeKeyword, $likeKeyword);
}

$stmt->execute();
$result    = $stmt->get_result();
$totalData = $result->num_rows;
$stmt->close();

// --- HITUNG RINGKASAN STATISTIK ---
$stmtStats = $koneksi->prepare("SELECT COUNT(*) as total_produk, SUM(stok) as total_stok, SUM(harga * stok) as total_nilai FROM produk");
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();
$stmtStats->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Inventaris Produk</title>

    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-bg: #1a1d23;
        }
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }
        /* Navbar */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
        /* Kartu Statistik */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        /* Tabel */
        .table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .table thead th {
            background-color: #343a40;
            color: #fff;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border: none;
            padding: 14px 16px;
            white-space: nowrap;
        }
        .table tbody tr {
            transition: background-color 0.15s;
        }
        .table tbody tr:hover {
            background-color: #f0f4ff;
        }
        .table tbody td {
            vertical-align: middle;
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        /* Badge stok */
        .badge-stok-rendah { background-color: #dc3545; }
        .badge-stok-sedang { background-color: #fd7e14; }
        .badge-stok-aman   { background-color: #198754; }

        /* Tombol aksi */
        .btn-action { padding: 5px 10px; font-size: 0.8rem; border-radius: 6px; }

        /* Search highlight */
        mark { background-color: #fff3cd; padding: 0 2px; border-radius: 3px; }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card .stat-value { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

<!-- ======================== NAVBAR ======================== -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <i class="bi bi-box-seam-fill text-primary fs-5"></i>
            <span>Inventaris <span class="text-primary">Produk</span></span>
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-secondary small"><i class="bi bi-mortarboard-fill me-1"></i>Pemrograman Web — Pertemuan 10</span>
            <span class="text-white-50 small"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION["nama_lengkap"] ?? $_SESSION["username"]) ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin logout?')">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<!-- ======================== KONTEN UTAMA ======================== -->
<div class="container-fluid px-4 py-4">

    <!-- Pesan Sukses/Gagal dari aksi sebelumnya -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php
        $pesanMap = [
            'tambah_sukses' => ['success', 'bi-check-circle-fill', 'Produk berhasil ditambahkan ke database.'],
            'edit_sukses'   => ['success', 'bi-pencil-fill',       'Data produk berhasil diperbarui.'],
            'hapus_sukses'  => ['success', 'bi-trash-fill',        'Produk berhasil dihapus dari database.'],
            'tambah_gagal'  => ['danger',  'bi-x-circle-fill',     'Gagal menambahkan produk. Kode produk mungkin sudah terdaftar.'],
            'edit_gagal'    => ['danger',  'bi-x-circle-fill',     'Gagal memperbarui data produk. Silakan coba lagi.'],
            'hapus_gagal'   => ['danger',  'bi-x-circle-fill',     'Gagal menghapus produk. Silakan coba lagi.'],
        ];
        $kodeP = htmlspecialchars($_GET['pesan']);
        if (array_key_exists($kodeP, $pesanMap)):
            [$tipe, $icon, $teks] = $pesanMap[$kodeP];
        ?>
        <div class="alert alert-<?= $tipe ?> alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
            <i class="bi <?= $icon ?> fs-5"></i>
            <div><?= $teks ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- ===== KARTU STATISTIK ===== -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                    <div>
                        <div class="text-muted small">Total Produk</div>
                        <div class="stat-value fw-bold fs-3 lh-1"><?= number_format((int)$stats['total_produk']) ?></div>
                        <div class="text-muted" style="font-size:.75rem;">Jenis produk terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-stack"></i></div>
                    <div>
                        <div class="text-muted small">Total Stok</div>
                        <div class="stat-value fw-bold fs-3 lh-1"><?= number_format((int)$stats['total_stok']) ?></div>
                        <div class="text-muted" style="font-size:.75rem;">Unit tersedia di gudang</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <div class="text-muted small">Total Nilai Inventaris</div>
                        <div class="stat-value fw-bold fs-4 lh-1">Rp <?= number_format((float)$stats['total_nilai'], 0, ',', '.') ?></div>
                        <div class="text-muted" style="font-size:.75rem;">Estimasi nilai stok</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABEL UTAMA ===== -->
    <div class="card table-card">
        <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="mb-0 fw-bold"><i class="bi bi-table me-2 text-primary"></i>Daftar Produk</h5>
                <small class="text-muted">
                    <?php if ($keyword): ?>
                        Menampilkan <strong><?= $totalData ?></strong> hasil pencarian untuk "<strong><?= htmlspecialchars($keyword) ?></strong>"
                    <?php else: ?>
                        Total <strong><?= $totalData ?></strong> produk terdaftar
                    <?php endif; ?>
                </small>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <!-- Form Pencarian -->
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <div class="input-group" style="min-width:240px;">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input
                            type="text"
                            name="search"
                            class="form-control border-start-0 ps-0"
                            placeholder="Cari kode, nama, kategori..."
                            value="<?= htmlspecialchars($keyword) ?>"
                            autocomplete="off"
                        >
                    </div>
                    <button type="submit" class="btn btn-primary px-3"><i class="bi bi-search"></i></button>
                    <?php if ($keyword): ?>
                        <a href="index.php" class="btn btn-outline-secondary" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>

                <!-- Tombol Tambah -->
                <a href="tambah.php" class="btn btn-success d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i> <span>Tambah Produk</span>
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th style="width:150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($totalData > 0): ?>
                            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-muted small"><?= $no++ ?></td>
                                <td>
                                    <code class="text-primary fw-semibold"><?= htmlspecialchars($row['kode_produk']) ?></code>
                                </td>
                                <td class="fw-medium"><?= htmlspecialchars($row['nama_produk']) ?></td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 fw-normal">
                                        <?= htmlspecialchars($row['kategori']) ?>
                                    </span>
                                </td>
                                <td class="fw-semibold">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $stok = (int)$row['stok'];
                                    if ($stok <= 5) {
                                        echo "<span class='badge badge-stok-rendah'>$stok unit</span>";
                                    } elseif ($stok <= 15) {
                                        echo "<span class='badge badge-stok-sedang'>$stok unit</span>";
                                    } else {
                                        echo "<span class='badge badge-stok-aman'>$stok unit</span>";
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning btn-action me-1" title="Edit">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>"
                                       class="btn btn-sm btn-danger btn-action"
                                       title="Hapus"
                                       onclick="return confirm('Yakin ingin menghapus produk \'<?= addslashes(htmlspecialchars($row['nama_produk'])) ?>\'?\n\nAksi ini tidak dapat dibatalkan!')">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                    <?php if ($keyword): ?>
                                        Tidak ada produk yang cocok dengan "<strong><?= htmlspecialchars($keyword) ?></strong>".
                                        <br><a href="index.php" class="btn btn-sm btn-outline-primary mt-2">Tampilkan Semua</a>
                                    <?php else: ?>
                                        Belum ada data produk.
                                        <br><a href="tambah.php" class="btn btn-sm btn-success mt-2">+ Tambah Produk Pertama</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white text-muted small py-2 px-3">
            <i class="bi bi-info-circle me-1"></i>
            Warna stok: <span class="badge badge-stok-aman me-1">Aman (>15)</span>
            <span class="badge badge-stok-sedang me-1">Sedang (6–15)</span>
            <span class="badge badge-stok-rendah">Rendah (≤5)</span>
        </div>
    </div>

</div><!-- /container -->

<!-- Bootstrap 5 JS via CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $koneksi->close(); ?>
