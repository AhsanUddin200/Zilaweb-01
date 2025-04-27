<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $karkun_id = $_POST['karkun_id'];
    $joining_date = $_POST['joining_date'];
    
    $update_query = "UPDATE arkan SET 
                    karkun_id = ?, 
                    joining_date = ?
                    WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "isi", $karkun_id, $joining_date, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: arkan.php");
        exit();
    }
}

// Add current karkun details query
$query = "SELECT a.*, k.* 
          FROM arkan a 
          LEFT JOIN karkunan k ON a.karkun_id = k.id 
          WHERE a.id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$arkan = mysqli_fetch_assoc($result);

// Get all karkunan for dropdown
$karkunan_query = "SELECT id, name, father_name FROM karkunan ORDER BY name ASC";
$karkunan_result = mysqli_query($conn, $karkunan_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Arkan - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 60px 0 0 0;
            background-color: #f0f2f5;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            color: white;
            padding: 0.3rem 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            background: #006600;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background: #008800;
        }

        .back-btn {
            text-decoration: none;
            color: #666;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1 style="margin: 0; font-size: 18px;">Edit Arkan - Digital Jamat</h1>
        <a href="admin.php" class="back-btn" style="color: white; padding-top: 16px;">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>

    <div class="container">
        <a href="arkan.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Arkan List
        </a>

        <?php if ($arkan): ?>
            <div class="current-info" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                <h3 style="margin-top: 0;">Current Karkun Details:</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($arkan['name']); ?></p>
                <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($arkan['father_name']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($arkan['gender']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($arkan['age']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($arkan['address']); ?></p>
                <p><strong>CNIC:</strong> <?php echo htmlspecialchars($arkan['cnic']); ?></p>
                <p><strong>Education:</strong> <?php echo htmlspecialchars($arkan['education']); ?></p>
                <p><strong>Responsibility:</strong> <?php echo htmlspecialchars($arkan['responsibility']); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="karkun_id">Select New Karkun:</label>
                <select name="karkun_id" id="karkun_id" required>
                    <option value="">Select Karkun</option>
                    <?php while ($karkun = mysqli_fetch_assoc($karkunan_result)): ?>
                        <option value="<?php echo $karkun['id']; ?>" 
                                <?php echo ($karkun['id'] == $arkan['karkun_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($karkun['name'] . ' (S/O ' . $karkun['father_name'] . ')'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="joining_date">Joining Date:</label>
                <input type="date" name="joining_date" id="joining_date" 
                       value="<?php echo htmlspecialchars($arkan['joining_date']); ?>" required>
            </div>

            <div class="button-group" style="display: flex; gap: 10px;">
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Update Arkan
                </button>
                <a href="arkan.php" class="btn" style="background: #666;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Add confirmation before form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to update this Arkan?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>