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

$query = "SELECT * FROM karkunan WHERE id = ?";
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
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }

        .detail-group {
            margin-bottom: 20px;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: 500;
            color: #666;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #444;
            border: 1px solid #ddd;
        }

        .action-bar {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            border: 1px solid #e0e0e0;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
            <div class="detail-label">Area</div>
            <div class="detail-value"><?php echo htmlspecialchars($karkun['area']); ?></div>
        </div>

        <div class="detail-group">
            <div class="detail-label">Marital Status</div>
            <div class="detail-value"><?php echo htmlspecialchars($karkun['marital_status']); ?></div>
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
            <div class="detail-label">Responsibility</div>
            <div class="detail-value"><?php echo htmlspecialchars($karkun['responsibility']); ?></div>
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