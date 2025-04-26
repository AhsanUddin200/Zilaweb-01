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

ob_end_clean();
ob_start();

$qrData = json_encode([
    'id' => $karkun['id'],
    'name' => $karkun['name'],
    'status' => $karkun['member_status']
]);

require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}

// Create new PDF document
$pdf = new MYPDF('P', 'mm', array(85.6, 54));
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);

// Front Side
$pdf->AddPage();

// Blue background
$pdf->SetFillColor(0, 51, 153);
$pdf->Rect(0, 0, 85.6, 54, 'F');

// White wave shape
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(0, 15, 85.6, 39, 'F');

// Header
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(0, 5);
$pdf->Cell(85.6, 10, 'DIGITAL JAMAT', 0, 1, 'C');

// Member name
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetXY(10, 20);
$pdf->Cell(65.6, 6, $karkun['name'], 0, 1, 'L');

// Details with better spacing
$pdf->SetFont('helvetica', '', 9);
$details = array(
    'ID' => sprintf('%06d', $karkun['id']),
    'Status' => $karkun['member_status'],
    'Phone' => $karkun['mobile_number'],
    'Area' => $karkun['area']
);

$y = 28;
foreach($details as $label => $value) {
    $pdf->SetTextColor(80, 80, 80);
    $pdf->SetXY(10, $y);
    $pdf->Cell(15, 5, $label.':', 0, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(40, 5, $value, 0, 1, 'L');
    $y += 6;
}

// QR Code - moved to bottom right
$style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0, 51, 153),
    'bgcolor' => false
);
$pdf->write2DBarcode($qrData, 'QRCODE,M', 55, 25, 25, 25, $style);

// Footer
$pdf->SetFillColor(0, 51, 153);
$pdf->Rect(0, 50, 85.6, 4, 'F');
$pdf->SetFont('helvetica', '', 7);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(0, 50);
$pdf->Cell(85.6, 4, 'Digital Jamat ID Card • Valid Until: '.date('d/m/Y', strtotime('+1 year')), 0, 0, 'C');

// Back Side
$pdf->AddPage();

// Light gray background
$pdf->SetFillColor(245, 245, 245);
$pdf->Rect(0, 0, 85.6, 54, 'F');

// Instructions
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(80, 80, 80);
$pdf->SetXY(5, 5);
$pdf->Cell(75.6, 5, 'Instructions:', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 7);
$instructions = array(
    '1. This card must be carried at all times during Jamat activities.',
    '2. This card is non-transferable.',
    '3. Please report lost card immediately.',
    '4. Card validity: One year from issue date.',
    '5. Please return card upon leaving the Jamat.'
);

$y = 12;
foreach($instructions as $instruction) {
    $pdf->SetXY(5, $y);
    $pdf->Cell(75.6, 4, $instruction, 0, 1, 'L');
    $y += 5;
}

// Contact Info
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetXY(5, 35);
$pdf->Cell(75.6, 5, 'Contact:', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 7);
$pdf->SetXY(5, 40);
$pdf->Cell(75.6, 4, 'Digital Jamat Office', 0, 1, 'L');
$pdf->SetXY(5, 44);
$pdf->Cell(75.6, 4, 'Phone: +92-XXX-XXXXXXX', 0, 1, 'L');
$pdf->SetXY(5, 48);
$pdf->Cell(75.6, 4, 'Email: info@digitaljamat.org', 0, 1, 'L');

// Clean buffer and output PDF
ob_end_clean();
$pdf->Output('id_card_'.$karkun['id'].'.pdf', 'D');
?>