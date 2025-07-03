<?php
require 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "✅ Koneksi ke database berhasil!";
} else {
    echo "❌ Koneksi ke database gagal.";
}
?>
