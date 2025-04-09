<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get total counts from database
$arkan_count = 0;
$karkunan_count = 0;
$umeedwar_count = 0;

// Count Arkan
$query = "SELECT COUNT(*) as total FROM arkan";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $arkan_count = $row['total'];
}

// Count Karkunan
$query = "SELECT COUNT(*) as total FROM karkunan";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $karkunan_count = $row['total'];
}

// Count Umeedwar
$query = "SELECT COUNT(*) as total FROM umeedwar";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $umeedwar_count = $row['total'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - One Tap Zila</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .navbar {
            background-color: #006600;
            color: white;
            padding: 0.5rem 2rem; /* Reduced padding */
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .navbar h1 {
            font-size: 1.5rem; /* Reduced heading size */
            margin: 0;
        }
        .logout-btn {
            color: white;
            text-decoration: none;
            padding: 6px 15px; /* Reduced padding */
            background-color: #004d00;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.9rem; /* Reduced font size */
        }
            .slider-container {
            position: relative;
            height: 650px;
            overflow: hidden;
            margin-bottom: 40px;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            height: 650px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }
        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1;
        }
        .slide-content {
            position: relative;
            z-index: 2;
            padding: 20px;
        }
        
        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }
        .slide-content {
            position: relative;
            z-index: 1;
            padding: 20px;
        }
        .slide h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .slider-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }
        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
        }
        .slider-dot.active {
            background: white;
        }
        .welcome-section {
            text-align: center;
            padding: 40px;
            background-color: white;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .nav-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .nav-button {
            background-color: #006600;
            color: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            font-size: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .nav-button i {
            font-size: 36px;
            margin-bottom: 15px;
        }
        .nav-button:hover {
            background-color: #008800;
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .logout-btn {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #004d00;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #003300;
        }
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 0 40px;
            max-width: 1200px;
            margin: 0 auto 40px auto;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: #006600;
            margin: 0 0 10px 0;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Digital Jamat Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEQi2_pvlgA426euOx5mu2qkfgR4OSPUikMw&s" alt="Welcome Slide">
                <div class="slide-content">
                    <h2>Welcome to One Tap Zila</h2>
                    <p>Manage your organization efficiently</p>
                </div>
            </div>
            <div class="slide">
                <img src="data:image/jpeg;base64,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   " alt="Arkan Slide">
                <div class="slide-content">
                    <h2>Manage Arkan</h2>
                    <p>View and manage all Arkan details</p>
                </div>
            </div>
            <div class="slide">
                <img src="data:image/jpeg;base64,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" alt="Karkunan Slide">
                <div class="slide-content">
                    <h2>Karkunan Records</h2>
                    <p>Access and update Karkunan information</p>
                </div>
            </div>
        </div>
        <div class="slider-nav"></div>
    </div>

    <!-- Remove this welcome-section -->
    <!-- <div class="welcome-section">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <p>Please select a section to manage</p>
    </div> -->

    <div class="stats-section">
        <!-- stats content -->
    </div>

    <div class="nav-buttons">
        <a href="arkan.php" class="nav-button">
            <i class="fas fa-users"></i>
            Arkan
        </a>
        <a href="karkunan.php" class="nav-button">
            <i class="fas fa-user-tie"></i>
            Karkunan
        </a>
        <a href="umeedwar.php" class="nav-button">
            <i class="fas fa-user-plus"></i>
            Umeedwar
        </a>
    </div>

    <div class="stats-section">
    <div class="stat-card">
        <i class="fas fa-users" style="font-size: 2.5em; color: #006600; margin-bottom: 15px;"></i>
        <h3>Total Arkan</h3>
        <div class="stat-number"><?php echo $arkan_count; ?></div>
    </div>

    <div class="stat-card">
        <i class="fas fa-user-tie" style="font-size: 2.5em; color: #006600; margin-bottom: 15px;"></i>
        <h3>Total Karkunan</h3>
        <div class="stat-number"><?php echo $karkunan_count; ?></div>
    </div>

    <div class="stat-card">
        <i class="fas fa-user-plus" style="font-size: 2.5em; color: #006600; margin-bottom: 15px;"></i>
        <h3>Total Umeedwaran</h3>
        <div class="stat-number"><?php echo $umeedwar_count; ?></div>
    </div>
</div>

    <script>
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const nav = document.querySelector('.slider-nav');
        let currentSlide = 0;

        // Create navigation dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('slider-dot');
            dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            nav.appendChild(dot);
        });

        function goToSlide(n) {
            currentSlide = n;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            document.querySelectorAll('.slider-dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            goToSlide(currentSlide);
        }

        setInterval(nextSlide, 5000);
    </script>
</body>
</html>