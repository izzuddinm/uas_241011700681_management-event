<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('❌ Username sudah terdaftar. Silakan gunakan username lain.'); window.location.href='tambah_user.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, nama, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $nama, $role]);
    echo "<script>alert('✅ User berhasil ditambahkan!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Event Management Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f8ff;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #0077cc;
            color: white;
            padding: 20px;
        }

        .sidebar h3 {
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #005fa3;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        h2 {
            color: #0077cc;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #0077cc;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cce0ff;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            background-color: #f8fbff;
            transition: all 0.3s;
        }

        .input-group input:focus, .input-group select:focus {
            outline: none;
            border-color: #70b8ff;
            box-shadow: 0 0 0 3px rgba(112, 184, 255, 0.2);
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-submit,
        .btn-home {
            flex: 1;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit {
            background: linear-gradient(to right, #70b8ff, #0077cc);
            color: white;
        }

        .btn-submit:hover {
            background: linear-gradient(to right, #5ca8ff, #0066bb);
        }

        .btn-home {
            background: #e6f2ff;
            color: #0077cc;
            border: 1px solid #cce0ff;
        }

        .btn-home:hover {
            background: #d0e7ff;
        }

        @media (max-width: 600px) {
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Menu</h3>
        <a href="index.php">🏠 Home</a>
        <a href="tambah.php">➕ Tambah Event</a>
        <a href="tambah_user.php">👤 Tambah User</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <h2>Tambah User</h2>
        <form method="post">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Masukkan username baru">
            </div>

            <div class="input-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Buat password yang kuat">
            </div>

            <div class="input-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-submit">Tambah User</button>
                <a href="index.php" class="btn-home">Kembali ke Home</a>
            </div>
        </form>
    </div>
</body>
</html>
