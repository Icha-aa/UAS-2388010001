<?php
// 1. Memanggil file koneksi database dan session auth
require_once 'src/db.php';
require_once 'src/auth.php';

$error = '';
$success = '';

// 2. Proses menyimpan data saat tombol "Simpan Pengguna" ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_petugas = trim($_POST['nama_petugas']);
    $username     = trim($_POST['username']);
    $role         = $_POST['role'];
    $password     = $_POST['password'];
    $konfirmasi   = $_POST['konfirmasi_password'];

    // Validasi apakah password & konfirmasi sudah cocok
    if ($password !== $konfirmasi) {
        $error = "Konfirmasi kata sandi tidak cocok!";
    } else {
        try {
            // Cek apakah username sudah pernah terdaftar di database uas_db
            $stmt_cek = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt_cek->execute([$username]);
            
            if ($stmt_cek->fetchColumn() > 0) {
                $error = "Username sudah digunakan, cari nama lain!";
            } else {
                // Keamanan: Enkripsi password dengan bcrypt sesuai standar database kamu
                $password_hashed = password_hash($password, PASSWORD_BCRYPT);

                // Query SQL disesuaikan dengan kolom 'nama_petugas' asli database-mu
                $sql = "INSERT INTO users (nama_petugas, username, password, role) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nama_petugas, $username, $password_hashed, $role]);

                $success = "User baru berhasil didaftarkan!";
            }
        } catch (PDOException $e) {
            $error = "Gagal menyimpan ke database: " . $e->getMessage();
        }
    }
}

// 3. Ambil data user paling update untuk langsung ditampilkan di tabel bawah
try {
    $query_users = $pdo->query("SELECT * FROM users ORDER BY id ASC");
    $list_users = $query_users->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data user: " . $e->getMessage());
}

// Data Akun Login saat ini (untuk ditampilkan di Sidebar)
$nama_user = $_SESSION['nama_petugas'] ?? $_SESSION['nama'] ?? 'Siti Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_user, 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - CoreSystem Hijab</title>
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
        
        /* Sidebar Navigasi */
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

        /* Main Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .header-title h2 { font-size: 1.8rem; font-weight: 700; }
        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }
        
        /* Box Panel Form & Tabel */
        .panel-box { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; margin-top: 25px; }
        .panel-box h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 20px; }
        
        /* Form Layout Grid */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 0.85rem; font-weight: 600; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); background-color: #fdfdfd; border-radius: 8px; font-size: 0.95rem; outline: none; }
        .form-control:focus { border-color: var(--accent-brown); background-color: #fff; box-shadow: 0 0 0 4px rgba(142, 115, 85, 0.08); }
        
        .btn-group-form { display: flex; gap: 12px; justify-content: flex-end; }
        .btn-submit { background: var(--accent-brown); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: var(--accent-brown-light); }

        /* Tabel Pengguna */
        table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 10px; }
        th { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
        td { padding: 18px 0; border-bottom: 1px solid var(--border-color); font-size: 0.95rem; }
        
        /* Badge Role */
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-admin { background: #fef3c7; color: #d97706; }
        .badge-kasir { background: #dbeafe; color: #2563eb; }
        
        .actions-cell a { color: var(--text-muted); text-decoration: none; margin-right: 15px; font-size: 1rem; }
        .actions-cell a:hover { color: var(--accent-brown); }

        /* Alert Status */
        .alert { padding: 12px 15px; border-radius: 6px; font-size: 0.9rem; font-weight: 500; margin-bottom: 20px; }
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
            <h2>Manajemen Data Pengguna</h2>
            <p>Kelola dan tambah hak akses petugas toko jilbab di bawah ini.</p>
        </div>

        <div class="panel-box">
            <h3><i class="fa-solid fa-user-plus" style="color: var(--accent-brown);"></i> Tambah Pengguna Baru</h3>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= $error ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= $success ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Nama Petugas (nama_petugas)</label>
                        <input type="text" name="nama_petugas" class="form-control" placeholder="Contoh: Icha Kasir" required>
                    </div>
                    <div class="form-group">
                        <label>Username (username)</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: kasir_icha" required>
                    </div>
                    <div class="form-group">
                        <label>Hak Akses (role)</label>
                        <select name="role" class="form-control" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi (password)</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Kata Sandi</label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div class="btn-group-form">
                    <button type="submit" class="btn-submit">Simpan Pengguna</button>
                </div>
            </form>
        </div>

        <div class="panel-box">
            <h3>Daftar Pengguna Sistem (Table: users)</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Petugas</th>
                        <th>Username</th>
                        <th>Hak Akses (Role)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($list_users) > 0): ?>
                        <?php foreach ($list_users as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['nama_petugas'] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <span class="badge <?= (strtolower($row['role'] ?? '')) == 'admin' ? 'badge-admin' : 'badge-kasir' ?>">
                                    <?= htmlspecialchars($row['role'] ?? 'kasir') ?>
                                </span>
                            </td>
                            <td class="actions-cell">
                                <a href="edit_user.php?id=<?= $row['id'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="hapus_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus user ini?')">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada data user dalam database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>