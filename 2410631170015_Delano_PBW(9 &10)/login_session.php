<?php
session_start();

// Jika session sudah aktif, langsung redirect ke dashboard
if (isset($_SESSION['nama'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');

    if (empty($nama)) {
        $error = 'Nama tidak boleh kosong.';
    } else {
        $_SESSION['nama'] = $nama;
        $_SESSION['login_time'] = date('d-m-Y H:i:s');
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Session PHP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
        .box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); width: 300px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Login Session</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login_session.php">
            <label>Masukkan Nama Anda:</label>
            <input type="text" name="nama" required autofocus>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
