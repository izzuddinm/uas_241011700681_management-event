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
$stmt = $conn->prepare("DELETE FROM events WHERE id_event = ?");
$stmt->execute([$id]);

header("Location: index.php");
