<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_event'];
    $judul = $_POST['judul_event'];
    $tanggal = $_POST['tanggal_event'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "uploads/" . $gambar);

    $stmt = $conn->prepare("INSERT INTO events (id_event, judul_event, tanggal_event, lokasi, deskripsi, gambar_event)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id, $judul, $tanggal, $lokasi, $deskripsi, $gambar]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event - Event Management Dashboard</title>
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
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(to bottom right, #e6f2ff, #f0f8ff);
        }

        .event-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 119, 204, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .event-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #70b8ff, #0077cc);
        }

        .event-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .event-header h2 {
            color: #0077cc;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #0077cc;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cce0ff;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s;
            background-color: #f8fbff;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #70b8ff;
            box-shadow: 0 0 0 3px rgba(112, 184, 255, 0.2);
        }

        .btn-save {
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
            margin-top: 10px;
        }

        .btn-save:hover {
            background: linear-gradient(to right, #5ca8ff, #0066bb);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 119, 204, 0.2);
        }

        @media (max-width: 480px) {
            .event-container {
                padding: 30px 20px;
                margin: 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="event-container">
        <div class="event-header">
            <h2>Tambah Event</h2>
        </div>

        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="id_event">ID Event</label>
                <!-- <input type="text" id="id_event" name="id_event" value="241011700681" readonly> -->
            </div>

            <div class="input-group">
                <label for="judul_event">Judul</label>
                <input type="text" id="judul_event" name="judul_event" required placeholder="Masukkan judul event">
            </div>

            <div class="input-group">
                <label for="tanggal_event">Tanggal</label>
                <input type="date" id="tanggal_event" name="tanggal_event" required>
            </div>

            <div class="input-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" required placeholder="Masukkan lokasi event">
            </div>

            <div class="input-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required placeholder="Masukkan deskripsi event"></textarea>
            </div>

            <div class="input-group">
                <label for="gambar">Gambar</label>
                <input type="file" id="gambar" name="gambar" required>
            </div>

            <button type="submit" class="btn-save">Simpan</button>
        </form>
    </div>
</body>
</html>
