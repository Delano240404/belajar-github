<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password tidak boleh kosong.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, nama_lengkap FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id']      = $user['id'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['login_time']   = time();

                $stmt->close();
                $conn->close();
                header('Location: index.php');
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        } else {
            $error = 'Username atau password salah.';
        }
        $stmt->close();
    }
    $conn->close();
}

$pesanLogout  = ($_GET['pesan'] ?? '') === 'logout';
$pesanTimeout = ($_GET['pesan'] ?? '') === 'timeout';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Aplikasi Pengelolaan Buku</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
        .login-box { background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); width: 320px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type=text], input[type=password] { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #667eea; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        button:hover { background: #5568d3; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9em; }
        .alert-error   { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-warning { background: #fff3cd; color: #856404; }
        .info { text-align: center; font-size: 0.85em; color: #666; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>📚 Login Buku</h2>

        <?php if ($pesanLogout): ?>
            <div class="alert alert-success">Anda berhasil logout.</div>
        <?php elseif ($pesanTimeout): ?>
            <div class="alert alert-warning">Sesi habis, silakan login kembali.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>Username</label>
            <input type="text" name="username" required autofocus>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Masuk</button>
        </form>

        <p class="info">Default: <b>admin</b> / <b>admin123</b></p>
    </div>
</body>
</html>
