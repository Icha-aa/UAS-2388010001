<?php
session_start();
include 'src/db.php';

// Proses simpan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulasi simpan ke database atau config
    // Contoh: UPDATE settings SET site_name = ? ...
    $message = "Pengaturan berhasil diperbarui!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan - Malikha House</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">Malikha<span>House</span></div>
            <ul class="menu-group">
                <li class="menu-item"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="users.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
                <li class="menu-item active"><a href="setting.php"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header-title">
            <h2>Pengaturan Umum</h2>
            <p>Atur informasi dasar dan preferensi sistem Malikha House.</p>
        </div>

        <div class="form-section">
            <?php if(isset($message)) echo "<p style='color: green; margin-bottom: 20px;'>$message</p>"; ?>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Nama Toko / Sistem</label>
                        <input type="text" name="site_name" class="form-control" value="Malikha House" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Alamat Toko</label>
                        <textarea name="address" class="form-control" rows="3">Jl. Contoh No. 123, Cirebon</textarea>
                    </div>
                    <div class="form-group">
                        <label>Email Kontak</label>
                        <input type="email" name="email" class="form-control" value="admin@malikhahouse.com">
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="phone" class="form-control" value="08123456789">
                    </div>
                </div>
                <div class="btn-group-form">
                    <button type="submit" class="btn-submit">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>