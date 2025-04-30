<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'admin';

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, is_approved) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    
    if ($stmt->execute()) {
        echo "<p style='color: #006600; background: #e8f5e9; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Registration successful! Please wait for admin approval.</p>";
    } else {
        echo "<p style='color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - One Tap Zila</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #009aff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        input:focus {
            border-color: #006600;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 0, 0.1);
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
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        button:hover {
            background-color: #004d00;
            transform: translateY(-2px);
        }
        .logo {
            margin-bottom: 20px;
            border-radius: 15px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        a {
            text-decoration: none;
            color: #006600;
            transition: all 0.3s ease;
        }
        a:hover {
            color: #004d00;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRH2XLJ6IdwaNK3n4jyOqiywvkn_6JrmZoRg0w4T-kp5yUPbyj9qMDB0o4HRYqUeuFHJnE&usqp=CAU" alt="Logo" class="logo" width="100" height="100">
        <h2>Admin Registration</h2>
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Enter Full Name" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter Email Address" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Create Password" required>
            </div>
            <button type="submit">
                <i class="fas fa-user-plus"></i>
                Register as Admin
            </button>
        </form>
        <div class="footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>