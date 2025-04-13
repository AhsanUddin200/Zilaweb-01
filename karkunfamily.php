<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$father_name = isset($_GET['name']) ? $_GET['name'] : '';
$address = isset($_GET['address']) ? $_GET['address'] : '';

// Query to get family members based on father's name or address
$query = "SELECT * FROM karkunan WHERE father_name = ? OR address = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $father_name, $address);
$stmt->execute();
$result = $stmt->get_result();
$family_members = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Members - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Copy the same styles from karkundetail.php */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            padding: 12px 40px;
            box-shadow: 0 4px 20px rgba(0,102,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 500;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .family-info {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        th {
            background: #006600;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9f8;
        }

        .female-row {
            background-color: #FFF0F5;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Family Members</h1>
        <div class="navbar-right">
            <a href="karkundetail.php" class="back-btn">Back to All Karkunan</a>
        </div>
    </div>

    <div class="container">
        <div class="family-info">
            <h2>Family Details</h2>
            <p><strong>Father/Husband Name:</strong> <?php echo htmlspecialchars($father_name); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
            <p><strong>Total Family Members:</strong> <?php echo count($family_members); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Father's/Husband's Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Area</th>
                    <th>Marital Status</th>
                    <th>Education</th>
                    <th>Responsibility</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($family_members as $index => $member): ?>
                <tr class="<?php echo $member['gender'] === 'Female' ? 'female-row' : ''; ?>">
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                    <td><?php echo htmlspecialchars($member['father_name']); ?></td>
                    <td><?php echo htmlspecialchars($member['gender']); ?></td>
                    <td><?php echo htmlspecialchars($member['age']); ?></td>
                    <td><?php echo htmlspecialchars($member['area']); ?></td>
                    <td><?php echo htmlspecialchars($member['marital_status']); ?></td>
                    <td><?php echo htmlspecialchars($member['education']); ?></td>
                    <td><?php echo htmlspecialchars($member['responsibility']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>