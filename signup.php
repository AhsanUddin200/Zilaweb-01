<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // admin or user

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    
    if ($stmt->execute()) {
        echo "Signup successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - One Tap Zila</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Google Font -->
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Custom font */
            background-color: #009aff; /* Background color */
            color: #333; /* Text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background-color: #ffffff; /* Container color */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px; /* Max width for the card */
        }
        h2 {
            margin-bottom: 20px;
            color: #006600; /* Header color */
            font-size: 24px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Include padding in width */
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            border-color: #006600; /* Change border color on focus */
            outline: none; /* Remove outline */
        }
        button {
            background-color: #006600; /* Button color */
            color: #ffffff; /* Button text color */
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #004d00; /* Darker green on hover */
            transform: translateY(-2px); /* Lift effect */
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
            text-decoration: none; /* Remove underline */
            color: #006600; /* Link color */
        }
        a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRH2XLJ6IdwaNK3n4jyOqiywvkn_6JrmZoRg0w4T-kp5yUPbyj9qMDB0o4HRYqUeuFHJnE&usqp=CAU" alt="Logo" class="logo" width="100" height="100"> <!-- Replace with your logo path -->
        <h2>Signup - One Tap Zila</h2>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Enter Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <select name="role">
                <option value="user">User</option>@
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Signup</button>
        </form>
        <div class="footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>