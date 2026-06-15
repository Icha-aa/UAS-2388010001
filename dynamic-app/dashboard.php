<?php
require_once 'src/db.php';
require_once 'src/auth.php';
urusLogin();

$total_produk = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_stok   = $pdo->query("SELECT SUM(stok) FROM products")->fetchColumn();
$total_user   = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$nama_user = $_SESSION['nama_petugas'] ?? 'Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial   = strtoupper(substr($nama_user, 0, 2));
?>
<?php
// Data dummy untuk produk terlaris
$produk_terlaris = [
    ['nama_produk' => 'Hijab Pashmina Silk', 'total_terjual' => 45],
    ['nama_produk' => 'Hijab Bergo Maryam', 'total_terjual' => 38],
    ['nama_produk' => 'Hijab Segiempat Laser Cut', 'total_terjual' => 32],
    ['nama_produk' => 'Hijab Instan Jersey', 'total_terjual' => 25],
    ['nama_produk' => 'Ciput Rajut Premium', 'total_terjual' => 19],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITI MALIKHA - 2388010001</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        
        /* Sidebar */
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
        .welcome-header h2 { font-size: 1.8rem; font-weight: 700; margin-bottom: 4px; }
        .welcome-header p { color: var(--text-muted); font-size: 0.95rem; }

        /* Cards */
        .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin: 30px 0; }
        .card { background: var(--bg-card); border: 1px solid var(--border-color); padding: 25px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; }
        .card-info span { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .card-info h3 { font-size: 1.75rem; font-weight: 700; margin-top: 5px; }
        .card-icon { width: 45px; height: 45px; border-radius: 8px; background: #fdfcfb; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--accent-brown); font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">MALIKHA<span>HOUSE</span></div>
            <ul class="menu-group">
                <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="manajemen_user.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?php echo $inisial; ?></div>
            <div><h4 style="font-size: 0.9rem;"><?php echo htmlspecialchars($nama_user); ?></h4><span style="font-size: 0.75rem; color: var(--text-muted);"><?php echo ucfirst($role_user); ?></span></div>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <h2>Siti Malikha</h2>
            <p>Berikut adalah ringkasan performa toko hijab.</p>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-info"><span>Total Produk</span><h3><?php echo $total_produk; ?> Varian</h3></div>
                <div class="card-icon"><i class="fa-solid fa-shirt"></i></div>
            </div>
            <div class="card">
                <div class="card-info"><span>Total Stok</span><h3><?php echo (int)$total_stok; ?> Pcs</h3></div>
                <div class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>
            <div class="card">
                <div class="card-info"><span>Petugas</span><h3><?php echo $total_user; ?> Akun</h3></div>
                <div class="card-icon"><i class="fa-solid fa-users-gear"></i></div>
            </div>
        </div>

        <div class="data-section" style="margin-top: 30px; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid var(--border-color);">
            <h3 style="margin-bottom: 20px;">Produk Terlaris Bulan Ini</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: var(--text-muted); font-size: 0.85rem;">
                        <th style="padding: 12px 0; border-bottom: 1px solid var(--border-color);">Nama Produk</th>
                        <th style="padding: 12px 0; border-bottom: 1px solid var(--border-color);">Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produk_terlaris as $item): ?>
                    <tr>
                        <td style="padding: 15px 0; border-bottom: 1px solid var(--border-color); font-weight: 500;">
                            <?php echo $item['nama_produk']; ?>
                        </td>
                        <td style="padding: 15px 0; border-bottom: 1px solid var(--border-color);">
                            <span style="background: #f0ede9; color: var(--accent-brown); padding: 4px 10px; border-radius: 6px; font-weight: 600;">
                                <?php echo $item['total_terjual']; ?> Pcs
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div> </body>
</html>