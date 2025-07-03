<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
require 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'judul_event';
$allowed = ['judul_event', 'lokasi', 'tanggal_event'];
if (!in_array($filter, $allowed)) $filter = 'judul_event';

$query = "SELECT * FROM events WHERE $filter LIKE :search ORDER BY tanggal_event DESC";
$stmt = $conn->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Event Management</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:'Segoe UI',sans-serif;}
    body{display:flex;min-height:100vh;background:#f4faff;}
    .sidebar{width:230px;background:#0077cc;color:#fff;padding:20px;position:sticky;top:0;height:100vh;}
    .sidebar h3{text-align:center;margin-bottom:20px;font-size:20px;}
    .sidebar a{display:block;color:#fff;text-decoration:none;padding:10px;margin-bottom:10px;border-radius:5px;transition:background .3s;}
    .sidebar a:hover{background:#005fa3;}
    .main-content{flex:1;padding:30px;}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;border-bottom:2px solid #cce0ff;padding-bottom:15px;flex-wrap:wrap;gap:10px;}
    .header h1{font-size:28px;color:#0077cc;}
    .btn-export{padding:8px 14px;color:#fff;background:linear-gradient(to right,#70b8ff,#0077cc);border:none;border-radius:5px;cursor:pointer;text-decoration:none;transition:background .3s;font-size:14px;}
    .btn-export:hover{background:linear-gradient(to right,#5ca8ff,#0066bb);}
    .search-form{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;}
    .search-form select,.search-form input{padding:10px;border:1px solid #cce0ff;border-radius:5px;min-width:130px;flex:1;}
    .search-form button{padding:10px 20px;background:linear-gradient(to right,#70b8ff,#0077cc);color:#fff;border:none;border-radius:5px;cursor:pointer;transition:background .3s;}
    .search-form button:hover{background:linear-gradient(to right,#5ca8ff,#0066bb);}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 0 10px rgba(0,0,0,0.05);}
    th,td{padding:14px 16px;text-align:left;border-bottom:1px solid #eaeaea;}
    th{background:#e6f2ff;color:#0077cc;}
    tr:hover td{background:#f5faff;}
    td img{max-width:80px;border-radius:5px;}
    .no-data{text-align:center;padding:20px;font-style:italic;color:#888;}
    @media(max-width:768px){body{flex-direction:column;} .sidebar{width:100%;height:auto;position:static;} .main-content{padding:20px;} .header{flex-direction:column;align-items:flex-start;} .search-form{flex-direction:column;} table th,table td{font-size:14px;padding:10px;}}
  </style>
</head>
<body>
  <div class="sidebar">
    <h3>Menu</h3>
    <a href="index.php">🏠 Home</a>
    <a href="tambah_event.php">➕ Tambah Event</a>
    <?php if ($_SESSION['user']['role']==='admin'): ?>
      <a href="tambah_user.php">👤 Tambah User</a>
    <?php endif; ?>
    <a href="logout.php">🚪 Logout</a>
  </div>
  <div class="main-content">
    <div class="header">
      <h1>Event Management Dashboard</h1>
      <div>
        <a href="export_pdf.php?filter=<?=urlencode($filter)?>&search=<?=urlencode($search)?>" class="btn-export" target="_blank">Export PDF</a>
        <a href="export_excel.php?filter=<?=urlencode($filter)?>&search=<?=urlencode($search)?>" class="btn-export" target="_blank">Export Excel</a>
      </div>
    </div>

    <form method="get" class="search-form">
      <select name="filter">
        <option value="judul_event" <?= $filter==='judul_event'?'selected':'' ?>>Judul</option>
        <option value="lokasi" <?= $filter==='lokasi'?'selected':'' ?>>Lokasi</option>
        <option value="tanggal_event" <?= $filter==='tanggal_event'?'selected':'' ?>>Tanggal</option>
      </select>
      <input type="text" name="search" placeholder="Cari..." value="<?=htmlspecialchars($search)?>">
      <button type="submit">🔍 Cari</button>
    </form>

    <table>
      <tr><th>ID</th><th>Judul</th><th>Tanggal</th><th>Lokasi</th><th>Deskripsi</th><th>Gambar</th><th>Aksi</th></tr>
      <?php if (empty($events)): ?>
        <tr><td colspan="7" class="no-data">Tidak ada data ditemukan</td></tr>
      <?php else: foreach($events as $e): ?>
        <tr>
          <td><?=htmlspecialchars($e['id_event'])?></td>
          <td><?=htmlspecialchars($e['judul_event'])?></td>
          <td><?=htmlspecialchars($e['tanggal_event'])?></td>
          <td><?=htmlspecialchars($e['lokasi'])?></td>
          <td><?=htmlspecialchars($e['deskripsi'])?></td>
          <td><img src="uploads/<?=htmlspecialchars($e['gambar_event'])?>" alt="Gambar"></td>
          <td><a href="edit.php?id=<?=$e['id_event']?>">Edit</a> | <a href="hapus.php?id=<?=$e['id_event']?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a></td>
        </tr>
      <?php endforeach; endif; ?>
    </table>
  </div>
</body>
</html>
