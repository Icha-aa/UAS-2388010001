<?php
// Contoh data dinamis (nanti bisa diambil dari database)
$brand_name = "MALIKHA";
$brand_suffix = "HOUSE";
$user_name = "Siti Admin";
$user_role = "Administrator";
$user_initial = "SM";

$users = [
    ['id' => 1, 'nama' => 'Siti Admin', 'username' => 'admin', 'role' => 'admin'],
    ['id' => 2, 'nama' => 'Icha Kasir', 'username' => 'kasir_icha', 'role' => 'kasir']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo $brand_name . ' ' . $brand_suffix; ?></title>
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
        .menu-item.active a { background: rgba(142, 115, 85, 0.05); color: var(--accent-brown); }
        .user-profile { display: flex; align-items: center; gap: 12px; padding-top: 20px; border-top: 1px solid var(--border-color); }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--accent-brown-light); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .logout-btn { color: var(--text-muted); text-decoration: none; font-size: 1.1rem; margin-left: auto; }

        /* Main Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .welcome-header { margin-bottom: 30px; }
        .welcome-header h2 { font-size: 1.8rem; font-weight: 700; margin-bottom: 4px; }
        
        /* Table & Data */
        .data-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
        td { padding: 18px 0; border-bottom: 1px solid var(--border-color); }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-admin { background: #fef3c7; color: #d97706; }
        .badge-kasir { background: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand"><?php echo $brand_name; ?> <span><?php echo $brand_suffix; ?></span></div>
            <ul class="menu-group">
                <li class="menu-item"><a href="#"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item active"><a href="#"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?php echo $user_initial; ?></div>
            <div><h4 style="font-size: 0.9rem;"><?php echo $user_name; ?></h4><span style="font-size: 0.75rem; color: var(--text-muted);"><?php echo $user_role; ?></span></div>
            <a href="#" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <h2>Selamat Datang di <?php echo $brand_name . ' ' . $brand_suffix; ?></h2>
        </div>

        <div class="data-section">
            <div class="section-header">
                <h3>Daftar Pengguna Sistem</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><strong><?php echo $user['nama']; ?></strong></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                        <td>
                            <a href="edit.php?id=<?php echo $user['id']; ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>