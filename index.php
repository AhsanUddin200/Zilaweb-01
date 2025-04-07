<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Tap Zila - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background: #333;
            color: white;
            padding: 10px 0;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        form {
            margin: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to One Tap Zila</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
    </nav>
</header>

<section>
    <h2>Register User</h2>
    <form action="insertData.php" method="POST">
        <input type="text" name="name" placeholder="Enter Name" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <button type="submit">Submit</button>
    </form>

    <h3>Users List</h3>
    <ul id="userList"></ul>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('getData.php')
            .then(response => response.json())
            .then(data => {
                let userList = document.getElementById("userList");
                data.forEach(user => {
                    let li = document.createElement("li");
                    li.textContent = user.name + " - " + user.email;
                    userList.appendChild(li);
                });
            });
    });
</script>

</body>
</html>
