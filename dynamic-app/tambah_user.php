<?php
session_start();
include 'src/db.php'; // Pastikan koneksi ke uas_db benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mengamankan password

    $stmt = $pdo->prepare("INSERT INTO users (nama, username, role, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $username, $role, $password]);
    
    header("Location: dashboard.php"); // Kembali ke dashboard setelah berhasil
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User - Malikha House</title>
    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="main-content">
        <div class="header-title">
            <h2>Tambah Pengguna Baru</h2>
            <p>Masukkan data petugas untuk akses sistem Malikha House.</p>
        </div>
        
        <div class="form-section">
            <form method="POST"> <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Nama Petugas</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Siti Malikha" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: kasir_icha" required>
                    </div>
                    <div class="form-group">
                        <label>Hak Akses</label>
                        <select name="role" class="form-control" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat password baru" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div class="btn-group-form">
                    <a href="dashboard.php" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>