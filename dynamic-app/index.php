<?php
// 1. Hubungkan ke file auth atau session jika ada
// Kita gunakan session_start untuk mengecek apakah user sudah login
session_start();

// 2. Logika Pengalihan (Redirect)
if (!isset($_SESSION['login'])) {
    // Jika belum login, lempar ke halaman login
    header("Location: login.php");
    exit;
} else {
    // Jika sudah login, lempar ke dashboard
    header("Location: dashboard.php");
    exit;
}
?>