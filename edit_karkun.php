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
// Replace the existing POST handler section with this updated version
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $father_name = $_POST['father_name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $area = $_POST['area'];
    $marital_status = $_POST['marital_status'];
    $address = $_POST['address'];
    $cnic = $_POST['cnic'];
    $education = $_POST['education'];
    $responsibility = $_POST['responsibility'];
    $member_status = $_POST['member_status'];
    $joining_date = $_POST['joining_date'] ?? null;

    $conn->begin_transaction();
    try {
        // Update karkunan table
        $query = "UPDATE karkunan SET 
                  name = ?, 
                  father_name = ?, 
                  gender = ?,
                  age = ?, 
                  area = ?, 
                  marital_status = ?, 
                  address = ?,  
                  cnic = ?, 
                  education = ?, 
                  responsibility = ? 
                  WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssissssssi", 
            $name, 
            $father_name, 
            $gender, 
            $age, 
            $area, 
            $marital_status, 
            $address,
            $cnic, 
            $education, 
            $responsibility, 
            $id
        );
        $stmt->execute();

        // Remove existing status
        $conn->query("DELETE FROM arkan WHERE karkun_id = $id");
        $conn->query("DELETE FROM umedwar WHERE karkun_id = $id");

        // Add new status
        if ($member_status === 'arkan' && $joining_date) {
            $stmt = $conn->prepare("INSERT INTO arkan (karkun_id, joining_date) VALUES (?, ?)");
            $stmt->bind_param("is", $id, $joining_date);
            $stmt->execute();
        } elseif ($member_status === 'umedwar') {
            $stmt = $conn->prepare("INSERT INTO umedwar (karkun_id, application_date) VALUES (?, CURRENT_DATE())");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }

        $conn->commit();
        header("Location: karkundetail.php?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error updating record: " . $e->getMessage() . "');</script>";
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
// Add this code after fetching karkun details (after the if (!$karkun) check)
// Get member status and joining date
$member_status = 'karkun'; // default status
$joining_date = null;

// Check if karkun is arkan
$stmt = $conn->prepare("SELECT joining_date FROM arkan WHERE karkun_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $member_status = 'arkan';
    $joining_date = $result->fetch_assoc()['joining_date'];
}

// Check if karkun is umedwar
if ($member_status === 'karkun') {
    $stmt = $conn->prepare("SELECT application_date FROM umedwar WHERE karkun_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $member_status = 'umedwar';
    }
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
                    <label>Father's/Husband's Name</label>
                    <input type="text" name="father_name" value="<?php echo htmlspecialchars($karkun['father_name']); ?>" required>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        <?php echo $karkun['gender'] === 'Female' && $karkun['marital_status'] === 'Married' ? 
                            "Enter husband's name for married women" : "Enter father's name"; ?>
                    </small>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" id="gender" onchange="updateNameLabel()" required>
                        <option value="Male" <?php echo $karkun['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $karkun['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
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
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($karkun['address']); ?>" required>
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

                <div class="form-group">
                    <label>Member Status</label>
                    <select name="member_status" required onchange="toggleJoiningDate()">
                        <option value="karkun" <?php echo $member_status === 'karkun' ? 'selected' : ''; ?>>Karkun</option>
                        <option value="arkan" <?php echo $member_status === 'arkan' ? 'selected' : ''; ?>>Arkan</option>
                        <option value="umedwar" <?php echo $member_status === 'umedwar' ? 'selected' : ''; ?>>Umedwar</option>
                    </select>
                </div>

                <div class="form-group joining-date" style="display: <?php echo $member_status === 'arkan' ? 'block' : 'none'; ?>">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" value="<?php echo $joining_date; ?>" <?php echo $member_status === 'arkan' ? 'required' : ''; ?>>
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
    <script>
        function toggleJoiningDate() {
            const memberStatus = document.querySelector('select[name="member_status"]').value;
            const joiningDateDiv = document.querySelector('.joining-date');
            const joiningDateInput = document.querySelector('input[name="joining_date"]');
            
            if (memberStatus === 'arkan') {
                joiningDateDiv.style.display = 'block';
                joiningDateInput.required = true;
            } else {
                joiningDateDiv.style.display = 'none';
                joiningDateInput.required = false;
            }
        }

        function updateNameLabel() {
            const gender = document.getElementById('gender').value;
            const maritalStatus = document.querySelector('select[name="marital_status"]').value;
            const small = document.querySelector('input[name="father_name"]').nextElementSibling;
            
            if (gender === 'Female' && maritalStatus === 'Married') {
                small.textContent = "Enter husband's name for married women";
            } else {
                small.textContent = "Enter father's name";
            }
        }

        // Add listener for marital status changes
        document.querySelector('select[name="marital_status"]').addEventListener('change', updateNameLabel);
    </script>
</body>
</html>