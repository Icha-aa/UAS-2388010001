<?php
// 1. Inisialisasi Auth & Koneksi Database PDO
require_once 'src/db.php';
require_once 'src/auth.php';
urusLogin();

$error = '';
$success = '';

// 2. Proses Simpan Data jika Form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode     = trim($_POST['kode_produk']);
    $nama     = trim($_POST['nama_produk']);
    $kategori = $_POST['kategori'];
    $harga    = $_POST['harga_jual'];
    $stok     = $_POST['stok'];

    try {
        // Cek apakah kode produk sudah pernah dipakai sebelumnya
        $stmt_cek = $pdo->prepare("SELECT COUNT(*) FROM products WHERE kode_produk = ?");
        $stmt_cek->execute([$kode]);
        
        if ($stmt_cek->fetchColumn() > 0) {
            $error = "Kode produk <strong>$kode</strong> sudah terdaftar! Gunakan kode varian lain.";
        } else {
            // Melakukan insert data ke tabel 'products' sesuai skema database
            $sql = "INSERT INTO products (kode_produk, nama_produk, kategori, harga_jual, stok) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kode, $nama, $kategori, $harga, $stok]);

            $success = "Produk baru berhasil ditambahkan ke etalase!";
            // Redirect otomatis kembali ke data master produk dalam 1.5 detik
            header("Refresh: 1.5; URL=produk.php");
        }
    } catch (PDOException $e) {
        $error = "Gagal menyimpan ke database: " . $e->getMessage();
    }
}

// 3. Ambil Informasi Akun Login untuk Profile Sidebar
$nama_user = $_SESSION['nama_petugas'] ?? $_SESSION['nama'] ?? 'Siti Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_user, 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - CoreSystem Hijab</title>
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
        
        /* Sidebar Layout Component */
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

        /* Main Workspace Container */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .header-title h2 { font-size: 1.8rem; font-weight: 700; }
        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }
        
        /* Clean Form Card Design */
        .form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; max-width: 750px; margin-top: 30px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 0.85rem; font-weight: 600; color: var(--text-main); }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); background-color: #fdfdfd; border-radius: 8px; font-size: 0.95rem; outline: none; transition: all 0.2s; }
        .form-control:focus { border-color: var(--accent-brown); background-color: #fff; box-shadow: 0 0 0 4px rgba(142, 115, 85, 0.08); }
        
        /* Form Operational Actions */
        .btn-group-form { display: flex; gap: 12px; justify-content: flex-end; align-items: center; }
        .btn-submit { background: var(--accent-brown); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--accent-brown-light); }
        .btn-cancel { background: transparent; color: var(--text-muted); border: 1px solid var(--border-color); padding: 11px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.95rem; text-align: center; transition: all 0.2s; }
        .btn-cancel:hover { background: #f5f4f2; color: var(--text-main); }

        /* Floating Alert Box System */
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
                <li class="menu-item"><a href="manajemen_user.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item active"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
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
            <h2>Tambah Produk Baru</h2>
            <p>Masukkan varian model jilbab baru ke dalam database inventaris toko.</p>
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
                    <div class="form-group">
                        <label>Kode Varian (kode_produk)</label>
                        <input type="text" name="kode_produk" class="form-control" placeholder="Contoh: KRD-005" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori (kategori)</label>
                        <select name="kategori" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pashmina">Pashmina</option>
                            <option value="Segi Empat">Segi Empat</option>
                            <option value="Bergo">Bergo</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Nama Model Hijab (nama_produk)</label>
                        <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Pashmina Inner Ceruty" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Jual Satuan Rp (harga_jual)</label>
                        <input type="number" name="harga_jual" class="form-control" placeholder="Masukkan harga nominal" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Ketersediaan Stok Pcs (stok)</label>
                        <input type="number" name="stok" class="form-control" placeholder="Jumlah kuantitas barang" min="0" required>
                    </div>
                </div>

                <div class="btn-group-form">
                    <a href="produk.php" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>