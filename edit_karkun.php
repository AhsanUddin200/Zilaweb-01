<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get karkun ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header("Location: karkundetail.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $father_name = $_POST['father_name'];
    $age = $_POST['age'];
    $area = $_POST['area'];
    $marital_status = $_POST['marital_status'];
    $cnic = $_POST['cnic'];
    $education = $_POST['education'];
    $responsibility = $_POST['responsibility'];

    $query = "UPDATE karkunan SET 
              name = ?, 
              father_name = ?, 
              age = ?, 
              area = ?, 
              marital_status = ?, 
              cnic = ?, 
              education = ?, 
              responsibility = ? 
              WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisssssi", $name, $father_name, $age, $area, $marital_status, $cnic, $education, $responsibility, $id);

    if ($stmt->execute()) {
        header("Location: karkundetail.php?success=1");
        exit();
    }
}

// Get karkun details
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
    <title>Edit Karkun - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            padding: 8px 40px;  /* Reduced from 15px to 8px */
            box-shadow: 0 4px 20px rgba(0,102,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;  /* Reduced from 30px to 20px */
        }

        .navbar h1 {
            color: white;
            margin: 0;
            font-size: 20px;  /* Reduced from 24px to 20px */
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
            font-size: 15px;
        }

        input, select {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input:focus, select:focus {
            border-color: #006600;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,102,0,0.1);
        }

        .btn-group {
            grid-column: 1 / -1;
            display: flex;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn {
            padding: 8px 20px;  /* Reduced from 14px 30px */
            border-radius: 8px;  /* Reduced from 10px */
            font-size: 14px;    /* Reduced from 16px */
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 120px;   /* Reduced from 150px */
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;          /* Reduced from 8px */
        }

        /* Adjust navbar button specifically */
        .navbar .btn {
            padding: 6px 15px;  /* Even smaller for navbar */
            font-size: 13px;    /* Smaller font for navbar */
            min-width: auto;    /* Remove min-width for navbar buttons */
        }
        .btn-primary {
            background: #006600;
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #444;
            border: 2px solid #ddd;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 20px;
                margin: 10px;
            }
            .btn-group {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>
            <i class="fas fa-user-edit"></i>
            Edit Karkun Details
        </h1>
        <a href="karkundetail.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="container">
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($karkun['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Father's Name</label>
                    <input type="text" name="father_name" value="<?php echo htmlspecialchars($karkun['father_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" value="<?php echo htmlspecialchars($karkun['age']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Area</label>
                    <input type="text" name="area" value="<?php echo htmlspecialchars($karkun['area']); ?>" required>
                </div>

                <div class="form-group">
                    <label>CNIC</label>
                    <input type="text" name="cnic" value="<?php echo htmlspecialchars($karkun['cnic']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Marital Status</label>
                    <select name="marital_status" required>
                        <option value="Single" <?php echo $karkun['marital_status'] === 'Single' ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo $karkun['marital_status'] === 'Married' ? 'selected' : ''; ?>>Married</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Education</label>
                    <input type="text" name="education" value="<?php echo htmlspecialchars($karkun['education']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Responsibility</label>
                    <input type="text" name="responsibility" value="<?php echo htmlspecialchars($karkun['responsibility']); ?>" required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Karkun
                    </button>
                    <a href="karkundetail.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>