<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get karkun ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header("Location: karkundetail.php");
    exit();
}

// Check if this karkun exists
$query = "SELECT * FROM karkunan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: karkundetail.php?error=1");
    exit();
}

// Delete the karkun
$query = "DELETE FROM karkunan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: karkundetail.php?success=2"); // 2 indicates successful deletion
} else {
    header("Location: karkundetail.php?error=2"); // 2 indicates deletion failed
}

$stmt->close();
$conn->close();
?>