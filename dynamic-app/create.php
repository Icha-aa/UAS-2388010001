<?php
session_start();
include 'src/db.php';

// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: manajemen_user.php");
    exit;
}

// 2. Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // Jika password diisi, update dengan password baru
        $stmt = $pdo->prepare("UPDATE users SET nama = ?, username = ?, role = ?, password = ? WHERE id = ?");
        $stmt->execute([$nama, $username, $role, password_hash($password, PASSWORD_DEFAULT), $id]);
    } else {
        // Jika password kosong, jangan ubah password
        $stmt = $pdo->prepare("UPDATE users SET nama = ?, username = ?, role = ? WHERE id = ?");
        $stmt->execute([$nama, $username, $role, $id]);
    }
    
    header("Location: manajemen_user.php?status=success");
    exit;
}

// 3. Ambil data user saat ini
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User - CoreSystem Hijab</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg-workspace: #f9f8f6; --bg-card: #ffffff; --accent-brown: #8e7355; --text-main: #2c2520; --text-muted: #8a8073; --border-color: #ededeb; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); display: flex; }
        
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid var(--border-color); position: fixed; padding: 30px 20px; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .form-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; max-width: 700px; }
        .form-group { margin-bottom: 20px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; }
        .btn-update { background: var(--accent-brown); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand" style="font-weight:700; margin-bottom:40px;">Core<span>System</span></div>
        <ul style="list-style:none;">
            <li><a href="dashboard.php" style="text-decoration:none; color:var(--text-muted);">Dashboard</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Ubah Data Pengguna</h2>
        <div class="form-section">
            <form method="POST">
                <div class="form-group">
                    <label>ID Pengguna</label>
                    <input type="text" class="form-control" value="<?php echo $user['id']; ?>" disabled style="background:#f5f5f3;">
                </div>
                <div class="form-group">
                    <label>Nama Petugas</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo $user['nama']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Hak Akses</label>
                    <select name="role" class="form-control">
                        <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                        <option value="kasir" <?php if($user['role']=='kasir') echo 'selected'; ?>>Kasir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                <button type="submit" class="btn-update">Perbarui Akun</button>
                <a href="manajemen_user.php" style="margin-left:15px; color:var(--text-muted);">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>