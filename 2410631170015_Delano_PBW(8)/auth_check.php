<?php
// Letakkan require_once file ini di paling atas setiap halaman yang dilindungi
session_start();

define('SESSION_TIMEOUT', 7200); // 2 jam

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
    session_destroy();
    header('Location: login.php?pesan=timeout');
    exit;
}

$_SESSION['login_time'] = time();
?>
