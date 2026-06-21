<?php
// ============================================
// FILE: logout.php
// Deskripsi: Proses logout menggunakan class Auth (OOP)
// ============================================

require_once 'Buku_Classes_PHP.php';

$auth = new Auth();
$auth->logout(); // Menghapus session & redirect ke login.php
