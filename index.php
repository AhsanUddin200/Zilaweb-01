<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Jamat - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #006600;
            --primary-dark: #004d00;
            --primary-light: #008800;
            --secondary: #4CAF50;
            --text-dark: #333;
            --text-light: #666;
            --white: #ffffff;
            --gradient: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Enhanced Navbar */
        /* Navbar Styles */
        .navbar {
            background: #006600;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-section img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .logo-section h1 {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .nav-links a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .logo-section img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            transition: transform 0.3s;
        }

        .logo-section img:hover {
            transform: scale(1.1);
            border-color: var(--white);
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient);
            padding: 180px 5% 100px;
            text-align: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: url('path/to/pattern.png');
            opacity: 0.1;
            animation: rotate 60s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .hero-content p {
            font-size: 1.2em;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        /* Enhanced Portal Cards */
        .portal-sections {
            margin-top: -50px;
            padding: 0 5%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .portal-card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
        }

        .portal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .portal-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transform: rotate(45deg);
            transition: all 0.3s ease;
        }

        .portal-icon i {
            color: var(--white);
            font-size: 2em;
            transform: rotate(-45deg);
        }

        .portal-card:hover .portal-icon {
            transform: rotate(90deg);
        }

        .feature-list li {
            padding: 15px;
            margin: 10px 0;
            background: rgba(0,102,0,0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .feature-list li:hover {
            background: rgba(0,102,0,0.1);
            transform: translateX(10px);
        }

        .portal-btn {
            background: var(--gradient);
            color: var(--white);
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,102,0,0.2);
        }

        .portal-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,102,0,0.3);
        }

        /* Features Section */
        .features-section {
            padding: 80px 5%;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5em;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .feature-card:hover::before {
            opacity: 0.05;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        /* Enhanced Footer */
        footer {
            background: var(--primary-dark);
            color: var(--white);
            padding: 40px 5% 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-links a {
            color: var(--white);
            font-size: 1.5em;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--secondary);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .portal-sections {
                grid-template-columns: 1fr;
            }

            .hero-content h1 {
                font-size: 2.5em;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo-section">
            <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Digital Jamat">
            <h1>Digital Jamat</h1>
        </div>
        <div class="nav-links">
            <a href="about.php">About</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Welcome to Digital Jamat</h1>
            <p>Connecting and Strengthening Jamaat-e-Islami Through Digital Innovation</p>
        </div>
    </section>

    <!-- Portal Section -->
    <div class="portal-sections">
        <div class="portal-card">
            <div class="portal-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2>Admin Portal</h2>
            <ul class="feature-list">
                <li><i class="fas fa-users-cog"></i> Manage Community Records</li>
                <li><i class="fas fa-chart-pie"></i> Analytics Dashboard</li>
                <li><i class="fas fa-file-export"></i> Generate Reports</li>
                <li><i class="fas fa-user-plus"></i> Member Management</li>
            </ul>
            <a href="login.php" class="portal-btn">Admin Login</a>
        </div>

        <div class="portal-card">
            <div class="portal-icon">
                <i class="fas fa-mosque"></i>
            </div>
            <h2>User Portal</h2>
            <ul class="feature-list">
                <li><i class="fas fa-book-reader"></i> Islamic Literature</li>
                <li><i class="fas fa-history"></i> Jamaat History</li>
                <li><i class="fas fa-calendar-alt"></i> Community Events</li>
                <li><i class="fas fa-hands-helping"></i> Connect & Engage</li>
            </ul>
            <a href="dashboard.php" class="portal-btn">Access Portal</a>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features-section">
        <div class="section-title">
            <h2>Why Choose Digital Jamat?</h2>
            <p>Experience the benefits of our comprehensive platform</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-handshake"></i>
                <h3>Community Collaboration</h3>
                <p>Connect and engage with community members seamlessly</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-mobile-alt"></i>
                <h3>Easy Access</h3>
                <p>Access your portal anytime, anywhere on any device</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure Platform</h3>
                <p>Your data is protected with advanced security measures</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
            <p>&copy; 2024 Digital Jamat. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
