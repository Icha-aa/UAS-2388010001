<?php
// 1. Inisialisasi Auth & Koneksi Database PDO
require_once 'src/db.php';
require_once 'src/auth.php';
urusLogin();

$error = '';
$success = '';

// 2. Validasi Parameter ID User yang akan diedit
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];

// 3. Ambil Data User Berdasarkan ID
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user_data = $stmt->fetch();

    if (!$user_data) {
        die("Data pengguna tidak ditemukan di sistem.");
    }
} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}

// 4. Proses Update Data saat Form Dikirim (Submit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_petugas = trim($_POST['nama_petugas']);
    $username     = trim($_POST['username']);
    $role         = $_POST['role'];
    $password     = $_POST['password'];
    $konfirmasi   = $_POST['konfirmasi_password'];

    try {
        // Cek apakah username diubah dan duplikat dengan akun lain
        $stmt_cek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
        $stmt_cek->execute([$username, $id]);
        
        if ($stmt_cek->fetchColumn() > 0) {
            $error = "Username <strong>$username</strong> sudah digunakan oleh petugas lain.";
        } else {
            // Jika password diisi, artinya user ingin mengganti password
            if (!empty($password)) {
                if ($password !== $konfirmasi) {
                    $error = "Konfirmasi kata sandi baru tidak cocok!";
                } else {
                    // Update dengan password baru (di-hash)
                    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET username = ?, password = ?, nama_petugas = ?, role = ? WHERE id = ?";
                    $stmt_update = $pdo->prepare($sql);
                    $stmt_update->execute([$username, $password_hashed, $nama_petugas, $role, $id]);
                    $success = "Data akun dan kata sandi berhasil diperbarui!";
                }
            } else {
                // Update tanpa mengubah password lama
                $sql = "UPDATE users SET username = ?, nama_petugas = ?, role = ? WHERE id = ?";
                $stmt_update = $pdo->prepare($sql);
                $stmt_update->execute([$username, $nama_petugas, $role, $id]);
                $success = "Data akun berhasil diperbarui!";
            }

            if (empty($error)) {
                // Refresh data terbaru agar langsung tampil di form
                $stmt->execute([$id]);
                $user_data = $stmt->fetch();
                
                // Kembalikan ke dashboard setelah 1.5 detik
                header("Refresh: 1.5; URL=dashboard.php");
            }
        }
    } catch (PDOException $e) {
        $error = "Gagal memperbarui data: " . $e->getMessage();
    }
}

// 5. Mengambil data login aktif untuk keperluan Sidebar Info
$nama_user = $_SESSION['nama_petugas'] ?? $_SESSION['nama'] ?? 'Siti Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_user, 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pengguna - CoreSystem Hijab</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-workspace: #f9f8f6;
            --bg-card: #ffffff;
            --accent-brown: #8e7355;
            --accent-brown-light: #b59f85;
            --text-main: #2c2520;
            --text-muted: #8a8073;
            --border-color: #ededeb;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); color: var(--text-main); display: flex; }
        
        /* Sidebar Navigation */
        .sidebar { width: 260px; height: 100vh; background: #ffffff; border-right: 1px solid var(--border-color); position: fixed; padding: 30px 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar-brand { font-size: 1.25rem; font-weight: 700; margin-bottom: 40px; padding-left: 10px; }
        .sidebar-brand span { color: var(--accent-brown); }
        .menu-group { list-style: none; }
        .menu-item { margin-bottom: 8px; }
        .menu-item a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 0.95rem; border-radius: 8px; }
        .menu-item.active a, .menu-item a:hover { background: rgba(142, 115, 85, 0.05); color: var(--accent-brown); }
        .user-profile { display: flex; align-items: center; gap: 12px; padding-top: 20px; border-top: 1px solid var(--border-color); }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--accent-brown-light); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .logout-btn { color: var(--text-muted); text-decoration: none; font-size: 1.1rem; margin-left: auto; }

        /* Main Workspace Layout */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .header-title h2 { font-size: 1.8rem; font-weight: 700; }
        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }

        /* Form Card */
        .form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; max-width: 750px; margin-top: 30px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 0.85rem; font-weight: 600; color: var(--text-main); }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); background-color: #fdfdfd; border-radius: 8px; font-size: 0.95rem; outline: none; transition: all 0.2s; }
        .form-control:focus { border-color: var(--accent-brown); background-color: #fff; box-shadow: 0 0 0 4px rgba(142, 115, 85, 0.08); }
        .password-note { font-size: 0.8rem; color: var(--text-muted); font-style: italic; margin-top: -4px; }
        
        /* Buttons */
        .btn-group-form { display: flex; gap: 12px; justify-content: flex-end; align-items: center; }
        .btn-submit { background: var(--accent-brown); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--accent-brown-light); }
        .btn-cancel { background: transparent; color: var(--text-muted); border: 1px solid var(--border-color); padding: 11px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.95rem; text-align: center; transition: all 0.2s; }
        .btn-cancel:hover { background: #f5f4f2; color: var(--text-main); }

        /* Notification Alerts */
        .alert { padding: 12px 15px; border-radius: 8px; font-size: 0.9rem; font-weight: 500; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        .alert-danger { background-color: #fde8e8; color: #e02424; border: 1px solid #fbd5d5; }
        .alert-success { background-color: #def7ec; color: #03543f; border: 1px solid #bcf0da; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">Core<span>System</span></div>
            <ul class="menu-group">
                <li class="menu-item"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item active"><a href="tambah_user.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
                <li class="menu-item"><a href="settin.php"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?= htmlspecialchars($inisial) ?></div>
            <div>
                <h4 style="font-size: 0.9rem;"><?= htmlspecialchars($nama_user) ?></h4>
                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($role_user) ?></span>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-title">
            <h2>Ubah Data Pengguna</h2>
            <p>Perbarui data detail operasional hak akses akun <strong><?= htmlspecialchars($user_data['nama_petugas']) ?></strong>.</p>
        </div>

        <div class="form-section">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= $error ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= $success ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Nama Lengkap Petugas</label>
                        <input type="text" name="nama_petugas" class="form-control" value="<?= htmlspecialchars($user_data['nama_petugas']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Username Akses</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user_data['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Hak Akses Sistem (role)</label>
                        <select name="role" class="form-control" required>
                            <option value="kasir" <?= $user_data['role'] === 'kasir' ? 'selected' : '' ?>>Kasir (Staff Operasional)</option>
                            <option value="admin" <?= $user_data['role'] === 'admin' ? 'selected' : '' ?>>Admin (Full Control)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kata Sandi Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah sandi">
                        <span class="password-note">*Biarkan kosong jika tidak ingin mengganti password lama</span>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="btn-group-form">
                    <a href="dashboard.php" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>