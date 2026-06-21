<?php
session_start();

// Redirect ke login jika session tidak ada
if (!isset($_SESSION['nama'])) {
    header('Location: login_session.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Session PHP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
        .box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); width: 350px; text-align: center; }
        a { display: inline-block; margin-top: 15px; color: #fff; background: #dc3545; padding: 8px 16px; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Selamat Datang!</h2>
        <p>Nama: <b><?php echo htmlspecialchars($_SESSION['nama']); ?></b></p>
        <p>Waktu Login: <?php echo htmlspecialchars($_SESSION['login_time']); ?></p>
        <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>
</body>
</html>
