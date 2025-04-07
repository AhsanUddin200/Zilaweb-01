<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
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
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .slider-container {
            position: relative;
            height: 300px;
            overflow: hidden;
            margin-bottom: 40px;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            height: 300px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
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
        <h1>One Tap Zila Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="slider-container">
        <div class="slider">
            <div class="slide" style="background-image: url('https://i.ibb.co/VxkP1gb/banner.jpg')">
                <div class="slide-content">
                    <h2>Welcome to One Tap Zila</h2>
                    <p>Manage your organization efficiently</p>
                </div>
            </div>
            <div class="slide" style="background-image: url('https://i.ibb.co/VxkP1gb/banner.jpg')">
                <div class="slide-content">
                    <h2>Manage Arkan</h2>
                    <p>View and manage all Arkan details</p>
                </div>
            </div>
            <div class="slide" style="background-image: url('https://i.ibb.co/VxkP1gb/banner.jpg')">
                <div class="slide-content">
                    <h2>Karkunan Records</h2>
                    <p>Access and update Karkunan information</p>
                </div>
            </div>
        </div>
        <div class="slider-nav"></div>
    </div>

    <div class="welcome-section">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <p>Please select a section to manage</p>
    </div>

    <div class="stats-section">
        <div class="stat-card">
            <h3>Total Arkan</h3>
            <div class="stat-number">150</div>
        </div>
        <div class="stat-card">
            <h3>Total Karkunan</h3>
            <div class="stat-number">485</div>
        </div>
        <div class="stat-card">
            <h3>Total Umeedwar</h3>
            <div class="stat-number">273</div>
        </div>
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

    <script>
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const nav = document.querySelector('.slider-nav');
        let currentSlide = 0;

        // Create navigation dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('slider-dot');
            if (index === 0) dot.classList.add('active');
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