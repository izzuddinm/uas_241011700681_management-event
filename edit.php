<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE id_event = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul_event'];
    $tanggal = $_POST['tanggal_event'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
    } else {
        $gambar = $event['gambar_event'];
    }

    $stmt = $conn->prepare("UPDATE events SET judul_event=?, tanggal_event=?, lokasi=?, deskripsi=?, gambar_event=? WHERE id_event=?");
    $stmt->execute([$judul, $tanggal, $lokasi, $deskripsi, $gambar, $id]);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Event Management Dashboard</title>
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
            transition: width 0.3s;
            position: relative;
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

        .input-group input, .input-group textarea, .input-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cce0ff;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s;
            background-color: #f8fbff;
        }

        .input-group input:focus, .input-group textarea:focus, .input-group select:focus {
            outline: none;
            border-color: #70b8ff;
            box-shadow: 0 0 0 3px rgba(112, 184, 255, 0.2);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #70b8ff, #0077cc);
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: linear-gradient(to right, #5ca8ff, #0066bb);
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
        <h2>Edit Event</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="judul_event">Judul</label>
                <input type="text" id="judul_event" name="judul_event" value="<?= htmlspecialchars($event['judul_event']) ?>" required>
            </div>

            <div class="input-group">
                <label for="tanggal_event">Tanggal</label>
                <input type="date" id="tanggal_event" name="tanggal_event" value="<?= htmlspecialchars($event['tanggal_event']) ?>" required>
            </div>

            <div class="input-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($event['lokasi']) ?>" required>
            </div>

            <div class="input-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($event['deskripsi']) ?></textarea>
            </div>

            <div class="input-group">
                <label for="gambar">Gambar</label>
                <input type="file" id="gambar" name="gambar">
                <small>Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>

            <button type="submit" class="btn-submit">Update</button>
        </form>
    </div>
</body>
</html>
