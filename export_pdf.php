<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require 'config/database.php';
require 'fpdf/fpdf186/fpdf.php'; 

$db = new Database();
$conn = $db->getConnection();

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'judul_event';
$allowed = ['judul_event', 'lokasi', 'tanggal_event'];
if (!in_array($filter, $allowed)) $filter = 'judul_event';

$stmt = $conn->prepare("SELECT * FROM events WHERE $filter LIKE :search ORDER BY tanggal_event DESC");
$stmt->execute(['search' => "%$search%"]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi PDF
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Laporan Event',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,6,'Filter: '. ucfirst($filter) .'  |  Cari: '. ($search ?: '-') ,0,1,'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(230,240,255);
$headers = ['ID','Judul','Tanggal','Lokasi','Deskripsi'];
$widths = [20,60,30,50,120];
foreach($headers as $i => $h) {
    $pdf->Cell($widths[$i],8,$h,1,0,'C',true);
}
$pdf->Ln();

// Isi data
$pdf->SetFont('Arial','',11);
foreach ($events as $e) {
    $pdf->Cell($widths[0],6,$e['id_event'],1);
    $pdf->Cell($widths[1],6,substr($e['judul_event'],0,30),1);
    $pdf->Cell($widths[2],6,$e['tanggal_event'],1);
    $pdf->Cell($widths[3],6,substr($e['lokasi'],0,25),1);
    $pdf->MultiCell($widths[4],6,substr($e['deskripsi'],0,100),1);
}
$pdf->Output('D','Laporan_Event_'.date('Ymd_His').'.pdf');
