<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Direct password comparison since it's stored as plain text in database
        if ($password === $user['password']) {
            if ($user['is_approved'] == 1) {
                $_SESSION['user'] = $user;
                header("Location: admin.php");
                exit();
            } else {
                echo "<p style='color:red;'>Your admin account is pending approval.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid password!</p>";
        }
    } else {
        echo "<p style='color:red;'>Admin not found!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - One Tap Zila</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #009aff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #006600;
            font-size: 24px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #006600;
            outline: none;
        }
        button {
            width: 100%;
            background-color: #006600;
            color: #ffffff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #004d00;
            transform: translateY(-2px);
        }
        .logo {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        a {
            text-decoration: none;
            color: #006600;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRH2XLJ6IdwaNK3n4jyOqiywvkn_6JrmZoRg0w4T-kp5yUPbyj9qMDB0o4HRYqUeuFHJnE&usqp=CAU" alt="Logo" class="logo" width="100" height="100">
        <h2>Admin Login - One Tap Zila</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter admin email" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit">Admin Login</button>
        </form>
        <div class="footer">
            Want to become an admin? <a href="signup.php">Register here</a>
        </div>
    </div>
</body>
</html>