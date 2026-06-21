<?php
session_start();

// Menghancurkan semua data session
$_SESSION = [];
session_destroy();

header('Location: login_session.php');
exit;
?>
