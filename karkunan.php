<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Your existing form handling code here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_karkun'])) {
        $stmt = $conn->prepare("INSERT INTO karkunan (name, father_name, age, marital_status, address, cnic, education, source_of_income, responsibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissssss", 
            $_POST['name'],
            $_POST['father_name'],
            $_POST['age'],
            $_POST['marital_status'],
            $_POST['address'],
            $_POST['cnic'],
            $_POST['education'],
            $_POST['source_of_income'],
            $_POST['responsibility']
        );
        $stmt->execute();
    }
}

$result = $conn->query("SELECT * FROM karkunan ORDER BY created_at DESC");
$karkunan = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karkunan Management - One Tap Zila</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Your existing CSS styles here -->
    <style>
        /* Copy all your existing styles from admin.php */
        /* Add this new style for the back button */
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #006600;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #004d00;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Karkunan Management</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <a href="admin.php" class="back-btn">Back to Dashboard</a>
        
        <!-- Your existing form and table code here -->
        <!-- Copy everything from the container div of your original admin.php -->
    </div>
</body>
</html>