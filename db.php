<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "onetapzila";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
