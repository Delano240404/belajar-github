<?php
// PROTEKSI SESSION (OOP) - Wajib login sebelum akses halaman ini
require_once 'Buku_Classes_PHP.php';

$auth = new Auth();
$auth->requireLogin();
$currentUser = $auth->getCurrentUser();

$objBuku = new Buku();
$semuaBuku = $objBuku->read();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pengelolaan Buku - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 1.8em;
            color: #667eea;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        
        .tab-button {
            padding: 12px 24px;
            background: #f0f0f0;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px 5px 0 0;
            transition: all 0.3s;
        }
        
        .tab-button:hover {
            background: #e0e0e0;
        }
        
        .tab-button.active {
            background: #667eea;
            color: white;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 5px;
            font-size: 1em;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        button {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #ccc;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #bbb;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        table tr:hover {
            background: #f9f9f9;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-buttons button {
            padding: 6px 12px;
            font-size: 0.9em;
            border-radius: 3px;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            font-size: 1em;
            opacity: 0.9;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        
        .alert-warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .empty-state svg {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            table {
                font-size: 0.9em;
            }
            
            table th, table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header" style="position:relative;">
            <h1>📚 Aplikasi Pengelolaan Buku</h1>
            <p>Sistem Manajemen Buku, Pelanggan & Penjualan</p>
            <div style="position:absolute;top:16px;right:16px;display:flex;align-items:center;gap:12px;">
                <span style="color:rgba(255,255,255,0.8);font-size:0.85rem;">
                    👤 <?= htmlspecialchars($currentUser['nama_lengkap'] ?? $currentUser['username']) ?>
                </span>
                <a href="logout.php"
                   onclick="return confirm('Yakin ingin logout?')"
                   style="background:rgba(255,255,255,0.2);color:#fff;padding:6px 14px;border-radius:6px;text-decoration:none;font-size:0.85rem;font-weight:600;border:1px solid rgba(255,255,255,0.4);">
                   🚪 Logout
                </a>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            
            <!-- Dashboard Overview -->
            <div class="section">
                <h2 class="section-title">Dashboard Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>7</h3>
                        <p>Total Buku</p>
                    </div>
                    <div class="stat-card">
                        <h3>5</h3>
                        <p>Total Pelanggan</p>
                    </div>
                    <div class="stat-card">
                        <h3>7</h3>
                        <p>Total Pesanan</p>
                    </div>
                    <div class="stat-card">
                        <h3>Rp 1,2M</h3>
                        <p>Total Penjualan</p>
                    </div>
                </div>
            </div>
            
            <!-- Data Management Section -->
            <div class="section">
                <h2 class="section-title">Manajemen Data</h2>
                
                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-button active" onclick="showTab('buku')">📖 Buku</button>
                    <button class="tab-button" onclick="showTab('pelanggan')">👥 Pelanggan</button>
                    <button class="tab-button" onclick="showTab('pesanan')">📦 Pesanan</button>
                </div>
                
                <!-- Tab: Buku -->
                <div id="buku" class="tab-content active">
                    <h3 style="margin-bottom: 20px;">Form Tambah/Edit Buku</h3>
                    <form onsubmit="handleBukuSubmit(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="judul">Judul Buku *</label>
                                <input type="text" id="judul" name="judul" required placeholder="Masukkan judul buku">
                            </div>
                            <div class="form-group">
                                <label for="penulis">Penulis *</label>
                                <input type="text" id="penulis" name="penulis" required placeholder="Masukkan nama penulis">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="tahun">Tahun Terbit</label>
                                <input type="number" id="tahun" name="tahun" placeholder="2024">
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga (Rp) *</label>
                                <input type="number" id="harga" name="harga" required placeholder="75000" step="1000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok Buku *</label>
                            <input type="number" id="stok" name="stok" required placeholder="50" min="0">
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-primary">💾 Simpan Buku</button>
                            <button type="reset" class="btn-secondary">🔄 Reset</button>
                        </div>
                    </form>
                    
                    <h3 style="margin-top: 40px; margin-bottom: 20px;">Daftar Buku</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tahun</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if (!empty($semua_buku)) : ?>
        <?php foreach ($semua_buku as $data) : ?>
            <tr>
                <td><?= $data['ID']; ?></td>
                <td><?= htmlspecialchars($data['Judul']); ?></td>
                <td><?= htmlspecialchars($data['Penulis']); ?></td>
                <td><?= $data['Tahun_Terbit']; ?></td>
                <td>Rp <?= number_format($data['Harga'], 0, ',', '.'); ?></td>
                <td><?= $data['Stok']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="6" style="text-align:center;">Data buku tidak ditemukan atau database kosong.</td>
        </tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
                
                <!-- Tab: Pelanggan -->
                <div id="pelanggan" class="tab-content">
                    <h3 style="margin-bottom: 20px;">Form Tambah/Edit Pelanggan</h3>
                    <form onsubmit="handlePelangganSubmit(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nama">Nama Pelanggan *</label>
                                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama">
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required placeholder="example@email.com">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="telepon">Telepon *</label>
                                <input type="tel" id="telepon" name="telepon" required placeholder="081234567890">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat *</label>
                                <input type="text" id="alamat" name="alamat" required placeholder="Masukkan alamat">
                            </div>
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="btn-primary">💾 Simpan Pelanggan</button>
                            <button type="reset" class="btn-secondary">🔄 Reset</button>
                        </div>
                    </form>
                    
                    <h3 style="margin-top: 40px; margin-bottom: 20px;">Daftar Pelanggan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Budi Santoso</td>
                                <td>budi.santoso@email.com</td>
                                <td>081234567890</td>
                                <td>Jl. Merdeka No. 123, Jakarta</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-primary" onclick="alert('Edit functionality')">✏️ Edit</button>
                                        <button class="btn-danger" onclick="alert('Delete functionality')">🗑️ Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Siti Nurhaliza</td>
                                <td>siti.nurhaliza@email.com</td>
                                <td>081987654321</td>
                                <td>Jl. Ahmad Yani No. 45, Bandung</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-primary" onclick="alert('Edit functionality')">✏️ Edit</button>
                                        <button class="btn-danger" onclick="alert('Delete functionality')">🗑️ Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Tab: Pesanan -->
                <div id="pesanan" class="tab-content">
                    <h3 style="margin-bottom: 20px;">Daftar Pesanan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Jumlah Item</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>2024-01-15</td>
                                <td>Budi Santoso</td>
                                <td>2</td>
                                <td>Rp 147.000</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-success" onclick="alert('Detail pesanan')">👁️ Detail</button>
                                        <button class="btn-danger" onclick="alert('Batalkan pesanan')">❌ Batalkan</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>2024-01-16</td>
                                <td>Siti Nurhaliza</td>
                                <td>1</td>
                                <td>Rp 72.000</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-success" onclick="alert('Detail pesanan')">👁️ Detail</button>
                                        <button class="btn-danger" onclick="alert('Batalkan pesanan')">❌ Batalkan</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Remove active from buttons
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Add active to clicked button
            event.target.classList.add('active');
        }
        
        function handleBukuSubmit(e) {
            e.preventDefault();
            alert('Buku berhasil disimpan!\n\nCatatan: Ini adalah demo. Untuk implementasi sebenarnya, data akan dikirim ke server PHP');
            e.target.reset();
        }
        
        function handlePelangganSubmit(e) {
            e.preventDefault();
            alert('Pelanggan berhasil disimpan!\n\nCatatan: Ini adalah demo. Untuk implementasi sebenarnya, data akan dikirim ke server PHP');
            e.target.reset();
        }
    </script>
</body>
</html>
