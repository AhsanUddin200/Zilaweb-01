<?php
session_start(); // Ensure session is started
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, Admin <?php echo $_SESSION['user']['name']; ?></h2>
    <p>This is the Admin Dashboard.</p>
    <a href="logout.php">Logout</a>
</body>
</html>