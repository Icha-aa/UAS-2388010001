<?php
// 1. Inisialisasi Auth & Database PDO
require_once 'src/db.php';
require_once 'src/auth.php';

$error = '';
$success = '';

// 2. Ambil ID Produk secara aman dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: produk.php");
    exit;
}
$id = $_GET['id'];

// 3. Proses Update Data saat Form disubmit (Method POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_produk = trim($_POST['kode_produk']);
    $nama_produk = trim($_POST['nama_produk']);
    $kategori    = $_POST['kategori'];
    $harga_jual  = $_POST['harga_jual'];
    $stok        = $_POST['stok'];

    try {
        // Melakukan update ke tabel 'products' sesuai struktur uas_db kamu
        $sql_update = "UPDATE products SET kode_produk = ?, nama_produk = ?, kategori = ?, harga_jual = ?, stok = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$kode_produk, $nama_produk, $kategori, $harga_jual, $stok, $id]);

        $success = "Data produk berhasil diperbarui!";
        // Redirect otomatis kembali ke data master produk dalam 1.5 detik
        header("Refresh: 1.5; URL=produk.php");
    } catch (PDOException $e) {
        $error = "Gagal memperbarui data: " . $e->getMessage();
    }
}

// 4. Ambil Data Lama Produk untuk ditampilkan di dalam Form Input
try {
    $stmt_fetch = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt_fetch->execute([$id]);
    $p = $stmt_fetch->fetch();

    // Jika ID produk tidak ditemukan di SQL, lempar kembali ke halaman utama produk
    if (!$p) {
        header("Location: produk.php");
        exit;
    }
} catch (PDOException $e) {
    die("Koneksi bermasalah: " . $e->getMessage());
}

// Ambil Informasi Akun untuk Sidebar Profile
$nama_user = $_SESSION['nama_petugas'] ?? $_SESSION['nama'] ?? 'Siti Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_user, 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - CoreSystem Hijab</title>
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
        
        /* Sidebar Menu Layout */
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

        /* Main Workspace Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .header-title h2 { font-size: 1.8rem; font-weight: 700; }
        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }
        
        /* Card Panel Form */
        .form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; max-width: 750px; margin-top: 30px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 0.85rem; font-weight: 600; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); background-color: #fdfdfd; border-radius: 8px; font-size: 0.95rem; outline: none; }
        .form-control:focus { border-color: var(--accent-brown); background-color: #fff; box-shadow: 0 0 0 4px rgba(142, 115, 85, 0.08); }
        
        /* Action Buttons Group */
        .btn-group-form { display: flex; gap: 12px; justify-content: flex-end; }
        .btn-submit { background: var(--accent-brown); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: var(--accent-brown-light); }
        .btn-cancel { background: transparent; color: var(--text-muted); border: 1px solid var(--border-color); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; text-align: center; }

        /* System Alert Boxes */
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
                <li class="menu-item"><a href="tambah_user.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item active"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
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
            <h2>Ubah Data Produk Hijab</h2>
            <p>Modifikasi rincian data stok dan harga jual varian kerudung etalase.</p>
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
                        <input type="text" name="kode_produk" class="form-control" value="<?= htmlspecialchars($p['kode_produk'] ?? '') ?>" placeholder="Contoh: KRD-001" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori Produk (kategori)</label>
                        <select name="kategori" class="form-control" required>
                            <option value="Pashmina" <?= ($p['kategori'] ?? '') == 'Pashmina' ? 'selected' : '' ?>>Pashmina</option>
                            <option value="Segi Empat" <?= ($p['kategori'] ?? '') == 'Segi Empat' ? 'selected' : '' ?>>Segi Empat</option>
                            <option value="Bergo" <?= ($p['kategori'] ?? '') == 'Bergo' ? 'selected' : '' ?>>Bergo</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Nama Model Hijab (nama_produk)</label>
                        <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($p['nama_produk'] ?? '') ?>" placeholder="Contoh: Pashmina Silk Premium" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Jual Satuan Rp (harga_jual)</label>
                        <input type="number" name="harga_jual" class="form-control" value="<?= htmlspecialchars($p['harga_jual'] ?? 0) ?>" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Ketersediaan Stok Pcs (stok)</label>
                        <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($p['stok'] ?? 0) ?>" min="0" required>
                    </div>
                </div>

                <div class="btn-group-form">
                    <a href="produk.php" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>