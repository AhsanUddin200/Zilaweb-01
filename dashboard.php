<?php
session_start(); // Ensure session is started
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
    <p>This is the User Dashboard.</p>
    <a href="logout.php">Logout</a>
</body>
</html>