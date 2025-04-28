<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="umeedwar_list_' . date('Y-m-d') . '.xls"');

// Query to get all data
$query = "SELECT k.name, k.father_name, k.gender, k.address, k.cnic, k.education, u.application_date 
          FROM umedwar u 
          LEFT JOIN karkunan k ON u.karkun_id = k.id 
          ORDER BY k.name ASC";
$result = mysqli_query($conn, $query);

// Create Excel header
echo "Name\tFather Name\tGender\tAddress\tCNIC\tEducation\tApplication Date\n";

// Output data
while ($row = mysqli_fetch_assoc($result)) {
    echo implode("\t", array_map('htmlspecialchars', [
        $row['name'],
        $row['father_name'],
        $row['gender'],
        $row['address'],
        $row['cnic'],
        $row['education'],
        $row['application_date']
    ])) . "\n";
}
exit();
?>