<?php
// ============================================
// FILE: generate_hash.php
// Deskripsi: Utilitas untuk generate hash password baru.
//            HAPUS file ini setelah digunakan!
// ============================================

// Ganti password di bawah sesuai kebutuhan
$password = 'admin123';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<pre>";
echo "Password asli : " . htmlspecialchars($password) . "\n";
echo "Hash bcrypt   : " . $hash . "\n\n";
echo "Salin hash di atas, lalu jalankan query SQL berikut:\n\n";
echo "UPDATE users SET password = '" . $hash . "' WHERE username = 'admin';\n";
echo "\nAtau saat INSERT:\n";
echo "INSERT INTO users (username, password, nama_lengkap) VALUES ('admin', '" . $hash . "', 'Administrator');\n";
echo "</pre>";
echo "<p style='color:red;font-weight:bold;'>⚠️ HAPUS file ini setelah selesai digunakan!</p>";
