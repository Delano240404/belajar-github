<?php
// Jalankan file ini sekali untuk membuat hash password admin.
// HAPUS file ini setelah selesai digunakan!

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<pre>";
echo "Password asli : " . htmlspecialchars($password) . "\n";
echo "Hash bcrypt   : " . $hash . "\n\n";
echo "Gunakan hash di atas pada query SQL berikut:\n\n";
echo "INSERT INTO users (username, password, nama_lengkap) VALUES ('admin', '" . $hash . "', 'Administrator');\n";
echo "</pre>";
echo "<p style='color:red;font-weight:bold;'>PENTING: Hapus file ini setelah digunakan!</p>";
?>
