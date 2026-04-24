<?php
/**
 * Database Configuration & Connection
 * Aplikasi Pengelolaan Buku
 * Pemrograman Web - Pertemuan 10
 */

// ============================================================
// 1. KONFIGURASI DATABASE
// ============================================================

class DatabaseConfig {
    // Development
    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const DATABASE = 'pemrograman_web_contoh';
    
    // Database Connection
    private static $pdo = null;
    
    /**
     * Get PDO Connection
     */
    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DATABASE . ';charset=utf8mb4';
                self::$pdo = new PDO($dsn, self::USER, self::PASSWORD);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Database Connection Error: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

// ============================================================
// 2. CLASS BUKU - CRUD OPERATIONS
// ============================================================

class Buku {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }
    
    /**
     * Create / Insert Buku Baru
     * 
     * @param string $judul
     * @param string $penulis
     * @param int $tahun_terbit
     * @param float $harga
     * @param int $stok
     * @return bool|int ID buku jika berhasil, false jika gagal
     */
    public function create($judul, $penulis, $tahun_terbit, $harga, $stok) {
        try {
            // Validasi input
            if (empty($judul) || strlen($judul) > 255) {
                throw new Exception('Judul tidak valid');
            }
            if ($harga <= 0) {
                throw new Exception('Harga harus lebih besar dari 0');
            }
            if ($stok < 0) {
                throw new Exception('Stok tidak boleh negatif');
            }
            
            $sql = "INSERT INTO buku (Judul, Penulis, Tahun_Terbit, Harga, Stok)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$judul, $penulis, $tahun_terbit, $harga, $stok]);
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Read / Ambil Data Buku
     * 
     * @param int $id (optional) - jika null, ambil semua
     * @return array
     */
    public function read($id = null) {
        try {
            if ($id !== null) {
                $sql = "SELECT * FROM buku WHERE ID = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id]);
                return $stmt->fetch();
            } else {
                $sql = "SELECT * FROM buku ORDER BY Judul ASC";
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll();
            }
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update Buku
     * 
     * @param int $id
     * @param string $judul
     * @param string $penulis
     * @param int $tahun_terbit
     * @param float $harga
     * @param int $stok
     * @return bool
     */
    public function update($id, $judul, $penulis, $tahun_terbit, $harga, $stok) {
        try {
            $sql = "UPDATE buku 
                    SET Judul = ?, Penulis = ?, Tahun_Terbit = ?, Harga = ?, Stok = ?
                    WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$judul, $penulis, $tahun_terbit, $harga, $stok, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete Buku
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM buku WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get Buku dengan Informasi Penjualan
     * 
     * @return array
     */
    public function getBukuDenganPenjualan() {
        try {
            $sql = "SELECT 
                        b.ID,
                        b.Judul,
                        b.Penulis,
                        b.Harga,
                        b.Stok,
                        COALESCE(SUM(dp.Kuantitas), 0) AS Total_Terjual,
                        COALESCE(SUM(dp.Kuantitas * dp.Harga_Per_Satuan), 0) AS Nilai_Penjualan
                    FROM buku b
                    LEFT JOIN detail_pesanan dp ON b.ID = dp.Buku_ID
                    GROUP BY b.ID
                    ORDER BY Total_Terjual DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

// ============================================================
// 3. CLASS PELANGGAN - CRUD OPERATIONS
// ============================================================

class Pelanggan {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }
    
    /**
     * Create Pelanggan Baru
     * 
     * @param string $nama
     * @param string $alamat
     * @param string $email
     * @param string $telepon
     * @return bool|int ID pelanggan jika berhasil
     */
    public function create($nama, $alamat, $email, $telepon) {
        try {
            // Validasi
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email tidak valid');
            }
            
            $sql = "INSERT INTO pelanggan (Nama, Alamat, Email, Telepon)
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nama, $alamat, $email, $telepon]);
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Read Pelanggan
     * 
     * @param int $id (optional)
     * @return array
     */
    public function read($id = null) {
        try {
            if ($id !== null) {
                $sql = "SELECT * FROM pelanggan WHERE ID = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id]);
                return $stmt->fetch();
            } else {
                $sql = "SELECT * FROM pelanggan ORDER BY Nama ASC";
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll();
            }
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update Pelanggan
     * 
     * @param int $id
     * @param string $nama
     * @param string $alamat
     * @param string $email
     * @param string $telepon
     * @return bool
     */
    public function update($id, $nama, $alamat, $email, $telepon) {
        try {
            $sql = "UPDATE pelanggan 
                    SET Nama = ?, Alamat = ?, Email = ?, Telepon = ?
                    WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nama, $alamat, $email, $telepon, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete Pelanggan
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM pelanggan WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get Pelanggan Premium (Top Spenders)
     * 
     * @return array
     */
    public function getPelangganPremium() {
        try {
            $sql = "SELECT 
                        pl.ID,
                        pl.Nama,
                        pl.Email,
                        pl.Telepon,
                        COUNT(p.ID) AS Jumlah_Pembelian,
                        ROUND(SUM(p.Total_Harga), 2) AS Total_Pengeluaran,
                        ROUND(AVG(p.Total_Harga), 2) AS Rata_Rata_Pembelian
                    FROM pelanggan pl
                    LEFT JOIN pesanan p ON pl.ID = p.Pelanggan_ID
                    GROUP BY pl.ID
                    HAVING COUNT(p.ID) > 0
                    ORDER BY Total_Pengeluaran DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

// ============================================================
// 4. CLASS PESANAN - CRUD OPERATIONS
// ============================================================

class Pesanan {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }
    
    /**
     * Create Pesanan Baru
     * 
     * @param int $pelanggan_id
     * @param string $tanggal_pesanan
     * @param float $total_harga
     * @return bool|int ID pesanan jika berhasil
     */
    public function create($pelanggan_id, $tanggal_pesanan, $total_harga = 0) {
        try {
            $sql = "INSERT INTO pesanan (Tanggal_Pesanan, Pelanggan_ID, Total_Harga)
                    VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$tanggal_pesanan, $pelanggan_id, $total_harga]);
            
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Read Pesanan
     * 
     * @param int $id (optional)
     * @return array
     */
    public function read($id = null) {
        try {
            if ($id !== null) {
                $sql = "SELECT 
                            p.*,
                            pl.Nama AS Nama_Pelanggan,
                            pl.Email,
                            pl.Telepon
                        FROM pesanan p
                        JOIN pelanggan pl ON p.Pelanggan_ID = pl.ID
                        WHERE p.ID = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$id]);
                return $stmt->fetch();
            } else {
                $sql = "SELECT 
                            p.*,
                            pl.Nama AS Nama_Pelanggan
                        FROM pesanan p
                        JOIN pelanggan pl ON p.Pelanggan_ID = pl.ID
                        ORDER BY p.Tanggal_Pesanan DESC";
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll();
            }
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update Total Harga Pesanan
     * 
     * @param int $pesanan_id
     * @return bool
     */
    public function updateTotalHarga($pesanan_id) {
        try {
            $sql = "UPDATE pesanan 
                    SET Total_Harga = (
                        SELECT SUM(Kuantitas * Harga_Per_Satuan) 
                        FROM detail_pesanan 
                        WHERE Pesanan_ID = ?
                    )
                    WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$pesanan_id, $pesanan_id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete Pesanan
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM pesanan WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
}

// ============================================================
// 5. CLASS DETAIL PESANAN
// ============================================================

class DetailPesanan {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }
    
    /**
     * Add Item ke Pesanan
     * 
     * @param int $pesanan_id
     * @param int $buku_id
     * @param int $kuantitas
     * @param float $harga_per_satuan
     * @return bool
     */
    public function addItem($pesanan_id, $buku_id, $kuantitas, $harga_per_satuan) {
        try {
            $sql = "INSERT INTO detail_pesanan (Pesanan_ID, Buku_ID, Kuantitas, Harga_Per_Satuan)
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$pesanan_id, $buku_id, $kuantitas, $harga_per_satuan]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get Detail Pesanan
     * 
     * @param int $pesanan_id
     * @return array
     */
    public function getDetail($pesanan_id) {
        try {
            $sql = "SELECT 
                        dp.*,
                        b.Judul AS Judul_Buku,
                        b.Penulis,
                        (dp.Kuantitas * dp.Harga_Per_Satuan) AS Subtotal
                    FROM detail_pesanan dp
                    JOIN buku b ON dp.Buku_ID = b.ID
                    WHERE dp.Pesanan_ID = ?
                    ORDER BY b.Judul ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$pesanan_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update Kuantitas Item
     * 
     * @param int $pesanan_id
     * @param int $buku_id
     * @param int $kuantitas
     * @return bool
     */
    public function updateKuantitas($pesanan_id, $buku_id, $kuantitas) {
        try {
            if ($kuantitas <= 0) {
                // Hapus item jika kuantitas 0
                $sql = "DELETE FROM detail_pesanan 
                        WHERE Pesanan_ID = ? AND Buku_ID = ?";
            } else {
                // Update kuantitas
                $sql = "UPDATE detail_pesanan 
                        SET Kuantitas = ?
                        WHERE Pesanan_ID = ? AND Buku_ID = ?";
            }
            
            $stmt = $this->pdo->prepare($sql);
            
            if ($kuantitas <= 0) {
                return $stmt->execute([$pesanan_id, $buku_id]);
            } else {
                return $stmt->execute([$kuantitas, $pesanan_id, $buku_id]);
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Remove Item dari Pesanan
     * 
     * @param int $pesanan_id
     * @param int $buku_id
     * @return bool
     */
    public function removeItem($pesanan_id, $buku_id) {
        try {
            $sql = "DELETE FROM detail_pesanan 
                    WHERE Pesanan_ID = ? AND Buku_ID = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$pesanan_id, $buku_id]);
        } catch (Exception $e) {
            return false;
        }
    }
}

// ============================================================
// 6. CONTOH PENGGUNAAN
// ============================================================

/*
// Buat instance class
$bukuManager = new Buku();
$pelangganManager = new Pelanggan();
$pesananManager = new Pesanan();
$detailManager = new DetailPesanan();

// ---- BUKU ----
// Create
$bukuId = $bukuManager->create('Contoh Judul', 'Penulis Contoh', 2024, 75000, 50);

// Read
$allBuku = $bukuManager->read();
$bukuDetail = $bukuManager->read(1);

// Update
$bukuManager->update(1, 'Judul Baru', 'Penulis Baru', 2024, 80000, 45);

// Delete
$bukuManager->delete(1);

// Get with sales info
$bukuDenganPenjualan = $bukuManager->getBukuDenganPenjualan();

// ---- PELANGGAN ----
// Create
$pelangganId = $pelangganManager->create(
    'Budi Santoso',
    'Jl. Merdeka No. 123',
    'budi@email.com',
    '081234567890'
);

// Get Premium Customers
$pelangganPremium = $pelangganManager->getPelangganPremium();

// ---- PESANAN & DETAIL ----
// Create Pesanan
$pesananId = $pesananManager->create(1, date('Y-m-d'), 0);

// Add Items
$detailManager->addItem($pesananId, 1, 2, 75000);
$detailManager->addItem($pesananId, 3, 1, 72000);

// Update Total Harga
$pesananManager->updateTotalHarga($pesananId);

// Get Detail
$detailPesanan = $detailManager->getDetail($pesananId);
*/

?>
