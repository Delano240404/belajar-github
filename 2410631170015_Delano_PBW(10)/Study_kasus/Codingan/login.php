<?php
// ============================================
// FILE: login.php
// Deskripsi: Halaman login & autentikasi user
// ============================================

session_start();

// Jika sudah login, langsung redirect ke halaman utama
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

$error   = '';
$success = '';

// --- PROSES LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi input tidak kosong
    if (empty($username) || empty($password)) {
        $error = 'Username dan password tidak boleh kosong.';
    } else {
        // Cari user berdasarkan username
        $stmt = $koneksi->prepare("SELECT id, username, password, nama_lengkap FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verifikasi password dengan password_verify (aman)
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID untuk mencegah session fixation
                session_regenerate_id(true);

                // Simpan data user di session
                $_SESSION['user_id']      = $user['id'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['login_time']   = time();

                $stmt->close();
                $koneksi->close();

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

    $koneksi->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris Produk</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            padding: 40px 36px;
            width: 100%;
            max-width: 400px;
        }

        .login-card h2 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 6px;
            font-size: 1.6rem;
        }

        .login-card p.subtitle {
            text-align: center;
            color: #777;
            font-size: 0.88rem;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d1d5db;
            border-radius: 7px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #1a73e8;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 6px;
        }

        .btn-login:hover { background: #1558b0; }

        .alert-error {
            background: #fde8e8;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            border-radius: 7px;
            padding: 10px 14px;
            font-size: 0.88rem;
            margin-bottom: 18px;
        }

        .info-box {
            margin-top: 22px;
            background: #f0f4ff;
            border-radius: 7px;
            padding: 12px 14px;
            font-size: 0.82rem;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>🔐 Login</h2>
        <p class="subtitle">Sistem Manajemen Inventaris Produk</p>

        <?php if ($error): ?>
            <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
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

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="info-box">
            Default: <strong>admin</strong> / <strong>admin123</strong><br>
            (Ganti password setelah login pertama)
        </div>
    </div>
</body>
</html>
