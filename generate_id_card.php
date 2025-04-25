<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Update query to include member status
$query = "SELECT k.*, 
    CASE 
        WHEN a.id IS NOT NULL THEN 'Arkan'
        WHEN u.id IS NOT NULL THEN 'Umedwar'
        ELSE 'Karkun'
    END as member_status
    FROM karkunan k
    LEFT JOIN arkan a ON k.id = a.karkun_id
    LEFT JOIN umedwar u ON k.id = u.karkun_id
    WHERE k.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$karkun = $result->fetch_assoc();

// Clear any output before generating PDF
ob_clean();

require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF('P', 'mm', array(85.6, 54));
$pdf->SetMargins(5, 5, 5);
$pdf->AddPage();

// Add content to ID card
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'DIGITAL JAMAT', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, $karkun['name'], 0, 1, 'C');
$pdf->Cell(0, 7, $karkun['member_status'], 0, 1, 'C');

// Add QR code with proper data
$qrData = 'ID: ' . $karkun['id'] . "\n" . 
          'Name: ' . $karkun['name'] . "\n" . 
          'Status: ' . $karkun['member_status'];

$style = array(
    'border' => false,
    'padding' => 2
);
$pdf->write2DBarcode($qrData, 'QRCODE,M', 60, 25, 20, 20, $style);

// Output PDF
$pdf->Output('id_card.pdf', 'D');
?>