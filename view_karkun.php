<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header("Location: karkundetail.php");
    exit();
}

// Update the query to check member status from different tables
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

if (!$karkun) {
    header("Location: karkundetail.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Karkun - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Copy the basic styles from edit_karkun.php */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            padding: 8px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: white;
            margin: 0;
            font-size: 20px;
        }

        .container {
            max-width: 1000px;  /* Increased width */
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 20px;
        }

        .detail-group {
            margin-bottom: 0;  /* Reset margin */
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .detail-group:hover {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .detail-label {
            font-weight: 600;
            color: #006600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            padding: 8px 0;
            border-bottom: 2px solid #e9ecef;
        }

        /* Make certain groups full width */
        .detail-group.full-width {
            grid-column: 1 / -1;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>

    <div class="navbar">
        <h1>
            <i class="fas fa-user"></i>
            Karkun Details
        </h1>
        <a href="karkundetail.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <style>
        /* Add these action button styles */
        .action-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .action-btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-edit {
            background: #006600;
        }

        .btn-print {
            background: #555555;
        }

        .btn-delete {
            background: #dc3545;
        }

        .action-btn i {
            font-size: 16px;
        }
    </style>

    <!-- Then in the HTML, keep only the buttons -->
    <div class="container">
        <div class="action-bar">
            <a href="edit_karkun.php?id=<?php echo $karkun['id']; ?>" class="action-btn btn-edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="download_karkun.php?id=<?php echo $karkun['id']; ?>" class="action-btn btn-print">
                <i class="fas fa-print"></i> Print
            </a>
            <a href="#" onclick="confirmDelete(<?php echo $karkun['id']; ?>)" class="action-btn btn-delete">
                <i class="fas fa-trash"></i> Delete
            </a>
        </div>
        <div class="details-grid">
            <div class="detail-group">
                <div class="detail-label">Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['name']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Father's Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['father_name']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Age</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['age']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Gender</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['gender']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Area</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['area']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Marital Status</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['marital_status']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Member Status</div>
                <div class="detail-value" style="color: <?php echo $status_color; ?>; font-weight: 500;">
                    <?php echo htmlspecialchars($karkun['member_status']); ?>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-label">CNIC</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['cnic']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Education</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['education']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Phone Number</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['mobile_number']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Responsibility</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['responsibility']); ?></div>
            </div>

            <div class="detail-group full-width">
                <div class="detail-label">Address</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['address']); ?></div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this karkun?')) {
                window.location.href = 'delete_karkun.php?id=' + id;
            }
        }
    </script>
</body>
</html>