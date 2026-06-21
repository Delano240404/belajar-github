<?php
// ============================================
// FILE: login.php
// Deskripsi: Halaman login menggunakan class Auth (OOP)
// ============================================

require_once 'Buku_Classes_PHP.php';

$auth = new Auth();

// Sudah login? Langsung ke index
if ($auth->isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $auth->login($username, $password);

    if ($result['success']) {
        header('Location: index.php');
        exit;
    } else {
        $error = $result['message'];
    }
}

$pesanLogout = ($_GET['pesan'] ?? '') === 'logout';
$pesanTimeout = ($_GET['pesan'] ?? '') === 'timeout';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Pengelolaan Buku</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 8px 36px rgba(0,0,0,0.18);
            padding: 42px 38px;
            width: 100%;
            max-width: 400px;
        }

        .login-card h2 {
            text-align: center;
            color: #764ba2;
            margin-bottom: 6px;
            font-size: 1.6rem;
        }

        .login-card p.subtitle {
            text-align: center;
            color: #888;
            font-size: 0.88rem;
            margin-bottom: 28px;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus { border-color: #764ba2; }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 6px;
        }

        .btn-login:hover { opacity: 0.88; }

        .alert { border-radius: 8px; padding: 10px 14px; font-size: 0.88rem; margin-bottom: 18px; }
        .alert-error   { background: #fde8e8; color: #b91c1c; border: 1px solid #fca5a5; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        .info-box {
            margin-top: 22px;
            background: #f5f3ff;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 0.82rem;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>📚 Login</h2>
        <p class="subtitle">Aplikasi Pengelolaan Buku</p>

        <?php if ($pesanLogout): ?>
            <div class="alert alert-success">✅ Anda telah berhasil logout.</div>
        <?php elseif ($pesanTimeout): ?>
            <div class="alert alert-warning">⏰ Sesi Anda telah berakhir. Silakan login kembali.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       placeholder="Masukkan username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn-login">🔐 Masuk</button>
        </form>

        <div class="info-box">
            Default: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </div>
</body>
</html>
