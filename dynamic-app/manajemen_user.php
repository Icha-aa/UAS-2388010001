<?php
session_start();
include 'src/db.php'; // Pastikan koneksi database benar

// Proses simpan data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password === $konfirmasi) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
      // Pastikan variabel diawali dengan tanda dollar ($)
$stmt = $pdo->prepare("INSERT INTO users (nama_petugas, username, role, password) VALUES (?, ?, ?, ?)");
$stmt->execute([$nama, $username, $role, $hashed_password]);

echo "<script>alert('User berhasil ditambahkan!'); window.location='manajemen_user.php';</script>";
} else {
        echo "<script>alert('Password tidak cocok!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User-MALIKHA</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (Gunakan CSS yang sama seperti yang kamu kirim sebelumnya) */
        :root { --bg-workspace: #f9f8f6; --bg-card: #ffffff; --accent-brown: #8e7355; --text-muted: #8a8073; --border-color: #ededeb; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #ffffff; border-right: 1px solid var(--border-color); position: fixed; padding: 30px 20px; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .form-section { background: #fff; padding: 35px; border-radius: 12px; border: 1px solid var(--border-color); max-width: 700px; }
        .form-control { width: 100%; padding: 12px; margin-top: 8px; border: 1px solid var(--border-color); border-radius: 8px; }
        .btn-submit { background: var(--accent-brown); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">MALIKHA<span>HOUSE</span></div>
            <ul class="menu-group">
                <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="create.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar">SM</div>
            <div><h4 style="font-size: 0.9rem;">Siti Admin</h4><span style="font-size: 0.75rem; color: var(--text-muted);">Administrator</span></div>
            <a href="login.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <h2>Tambah Pengguna Baru</h2>
        <div class="form-section">
            <form method="POST">
                <<div class="form-group full-width">
                    <label>Nama Petugas</label>
                    <input type="text" name="nama_petugas" class="form-control" placeholder="Contoh: Siti Malikha" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Hak Akses</label>
                    <select name="role" class="form-control">
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Kata Sandi</label>
                    <input type="password" name="konfirmasi" class="form-control" required>
                </div>
                <br>
                <button type="submit" class="btn-submit">Simpan Pengguna</button>
            </form>
        </div>
    </div>
</body>
</html>