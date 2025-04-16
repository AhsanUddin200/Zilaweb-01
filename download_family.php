<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$father_name = isset($_GET['name']) ? $_GET['name'] : '';
$address = isset($_GET['address']) ? $_GET['address'] : '';

// Get family members (use the same query from karkunfamily.php)
$query = "SELECT DISTINCT k.* FROM karkunan k 
         WHERE k.address = ? 
         OR k.name IN (
             SELECT DISTINCT 
                 CASE 
                     WHEN k2.gender = 'Male' THEN k2.name 
                     WHEN k2.gender = 'Female' AND k2.marital_status = 'Married' THEN k2.father_name
                 END
             FROM karkunan k2 
             WHERE k2.address = ?
         )
         OR k.father_name IN (
             SELECT DISTINCT name 
             FROM karkunan 
             WHERE address = ?
         )
         OR k.name IN (
             SELECT DISTINCT father_name 
             FROM karkunan 
             WHERE address = ?
         )";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $address, $address, $address, $address);
$stmt->execute();
$result = $stmt->get_result();
$family_members = $result->fetch_all(MYSQLI_ASSOC);

// Find family head (use the same logic from karkunfamily.php)
$family_head = null;
foreach ($family_members as $member) {
    if ($member['gender'] === 'Male' && $member['marital_status'] === 'Married') {
        $family_head = $member;
        break;
    }
}

// Set headers for PDF-like document
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="family_details.pdf"');

// Add MPDF library
require_once __DIR__ . '/vendor/autoload.php';

// Start capturing HTML content
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #006600;
            padding-bottom: 15px;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .family-info {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #006600;
            color: white;
        }
        .female-row {
            background-color: #FFF0F5;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/logo.png" alt="Digital Jamat Logo" class="logo">
        <h1>Family Details Report</h1>
    </div>

    <div class="family-info">
        <h2>Family Head Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($family_head['name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Total Family Members:</strong> <?php echo count($family_members); ?></p>
        <p><strong>Area:</strong> <?php echo htmlspecialchars($family_head['area']); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Father's/Husband's Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Marital Status</th>
                <th>Education</th>
                <th>Relationship</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($family_members as $index => $member): 
                // Determine relationship (use the same logic from karkunfamily.php)
                $relationship = '';
                if ($member === $family_head) {
                    $relationship = 'Self';
                } elseif ($member['gender'] === 'Female' && $member['marital_status'] === 'Married' 
                          && $member['father_name'] === $family_head['name']) {
                    $relationship = 'Wife';
                } elseif ($member['father_name'] === $family_head['name']) {
                    $relationship = 'Child';
                } elseif ($member['name'] === $family_head['father_name']) {
                    $relationship = 'Father';
                }
            ?>
            <tr class="<?php echo $member['gender'] === 'Female' ? 'female-row' : ''; ?>">
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($member['name']); ?></td>
                <td><?php echo htmlspecialchars($member['father_name']); ?></td>
                <td><?php echo htmlspecialchars($member['gender']); ?></td>
                <td><?php echo htmlspecialchars($member['age']); ?></td>
                <td><?php echo htmlspecialchars($member['marital_status']); ?></td>
                <td><?php echo htmlspecialchars($member['education']); ?></td>
                <td><?php echo htmlspecialchars($relationship); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated by Digital Jamat System on <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

try {
    // Create new mPDF instance with basic settings
    $mpdf = new \Mpdf\Mpdf();
    
    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Output PDF
    $mpdf->Output('family_details.pdf', 'D');
} catch (Exception $e) {
    echo "Error generating PDF. Please try again.";
}

exit;
?>