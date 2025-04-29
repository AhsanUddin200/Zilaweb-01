<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Nastaliq+Urdu:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #006600;
            --primary-dark: #004d00;
            --primary-light: #008800;
            --text-dark: #333;
            --text-light: #666;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f2f5 0%, #e8eaf6 100%);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Modern Navbar */
               /* Navbar Styles */
               .navbar {
            background: #006600;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .logo-section h1 {
            color: white;
            font-size: 1.4rem;
            font-weight: 500;
            margin: 0;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }

        /* Main Content Styles */
        .container {
            max-width: 1400px;
            margin: 120px auto 60px;
            padding: 0 5%;
        }

        .about-section {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.8);
        }

        .about-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .about-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-dark), var(--primary-light));
            border-radius: 2px;
        }

        .about-header h1 {
            font-size: 3.5em;
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        /* Feature Cards */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }

        .feature-card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s ease;
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
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 1;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card:hover::before {
            opacity: 0.05;
        }

        .feature-card i {
            font-size: 2.5em;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        /* Timeline Section */
        .timeline {
            position: relative;
            padding: 40px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-dark), var(--primary-light));
            border-radius: 2px;
        }

        .step-card {
            width: calc(50% - 30px);
            margin: 30px 0;
            position: relative;
            padding: 30px;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .step-card:nth-child(odd) {
            margin-left: auto;
        }

        .step-number {
            position: absolute;
            top: 20px;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-light));
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .step-card:nth-child(odd) .step-number {
            left: -50px;
        }

        .step-card:nth-child(even) .step-number {
            right: -50px;
        }

        /* Leadership Section */
        .leadership-section {
            text-align: center;
            padding: 80px 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
            backdrop-filter: blur(10px);
            border-radius: 30px;
            margin: 60px 0;
        }

        .ceo-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background: var(--white);
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .ceo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            opacity: 0.05;
        }

        .ceo-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 0 auto 30px;
            border: 4px solid var(--primary-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.4s ease;
        }

        .ceo-image:hover {
            transform: scale(1.05);
        }

        /* Features Section */
        .features-list {
            display: flex;
            justify-content: space-between;
            gap: 25px;
            margin: 40px 0;
            padding: 20px 0;
        }

        .feature-item {
            flex: 1;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,102,0,0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(0,102,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-dark), var(--primary-light));
            transition: width 0.3s ease;
        }

        .feature-item:hover::before {
            width: 100%;
            opacity: 0.1;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,102,0,0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-light));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .feature-icon i {
            font-size: 2em;
            color: white;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-content h3 {
            color: var(--primary-color);
            font-size: 1.4em;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .feature-content p {
            color: var(--text-light);
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .feature-link {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            padding: 10px 0;
            transition: all 0.3s ease;
        }

        .feature-link i {
            transition: transform 0.3s ease;
        }

        .feature-link:hover {
            color: var(--primary-dark);
        }

        .feature-link:hover i {
            transform: translateX(5px);
        }

        /* Footer Styles */
        footer {
            background: #006600;
            color: white;
            padding: 40px 20px;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            padding: 0 20px;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background: rgba(255,255,255,0.3);
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 12px;
        }

        .footer-section ul li a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-section ul li a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            color: rgba(255,255,255,0.8);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo-section">
            <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Digital Jamat Logo">
            <h1>Digital Jamat</h1>
        </div>
        <a href="index.php" class="nav-link">Back to Home</a>
    </nav>

    <div class="container">
        <div class="about-section">
            <div class="about-header">
                <h1>About Digital Jamat</h1>
                <p>Transforming Community Management Through Technology</p>
            </div>

           

            <div class="content-section">
                <h2>Our Purpose</h2>
                <p>Digital Jamat is a comprehensive platform designed to streamline and modernize community management within Islamic organizations. Our system provides powerful tools for both administrators and community members.</p>
                
                <div class="urdu-text">
                    <p>ڈیجیٹل جماعت ایک جامع پلیٹ فارم ہے جو اسلامی تنظیموں میں کمیونٹی مینجمنٹ کو جدید اور موثر بنانے ڈیزائن کیا گیا ہے۔ ہمارا سسٹم منتظمین اور کمیونٹی ممبران دونوں کے لیے طاقتور ٹولز فراہم کرتا ہے۔</p>
                </div>
            </div>

            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Community Management</h3>
                        <p>Efficient member registration and management</p>
                        <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Analytics</h3>
                        <p>Detailed insights and reporting</p>
                        <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Resources</h3>
                        <p>Access to Islamic literature and materials</p>
                        <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="how-to-use">
                <h2>How to Use Digital Jamat</h2>
                <div class="timeline">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Create Your Account</h4>
                            <p>Choose between User or Admin registration. Fill in your basic information and verify your email address.</p>
                            <p class="urdu-text">اپنا اکاؤنٹ بنائیں - صارف یا ایڈمن رجسٹریشن کا انتخاب کریں۔ اپنی بنیادی معلومات درج کریں اور اپنا ای میل ایڈریس تصدیق کریں۔</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Complete Your Profile</h4>
                            <p>Add your personal details, upload a profile picture, and set your preferences for notifications.</p>
                            <p class="urdu-text">اپنی پروفائل مکمل کریں - اپنی ذاتی تفصیلات شامل کریں، پروفائل تصویر اپ لوڈ کریں، اور نوٹیفیکیشن کی ترجیحات طے کریں۔</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Explore Features</h4>
                            <p>For Users:</p>
                            <ul>
                                <li>Browse Islamic literature and resources</li>
                                <li>Learn about Jamaat's history and activities</li>
                                <li>Connect with community members</li>
                                <li>Stay updated with latest announcements</li>
                            </ul>
                            <p>For Admins:</p>
                            <ul>
                                <li>Manage member records</li>
                                <li>Generate and view analytics</li>
                                <li>Create and send announcements</li>
                                <li>Organize community activities</li>
                            </ul>
                            <p class="urdu-text">خصوصیات کی تلاش کریں - اسلامی لٹریچر اور وسائل دیکھیں، جماعت کی تاریخ اور سرگرمیوں کے بارے میں جانیں، کمیونٹی ممبران سے جڑیں۔</p>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Stay Connected</h4>
                            <p>Regularly check updates, participate in discussions, and contribute to the community's growth.</p>
                            <p class="urdu-text">جڑے رہیں - باقاعدگی سے اپ ڈیٹس چیک کریں، مباحثوں میں حصہ لیں، اور کمیونٹی کی ترقی میں حصہ ڈالیں۔</p>
                        </div>
                    </div>
                </div>
            </div>
            <div style="max-width: 500px; margin: 0 auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-top:60px;">
  <p style="margin-bottom: 10px; font-size: 1.1rem; color: #333;">Getting started with Digital Jamat is simple:</p>
  <ol style="padding-left: 20px; margin-bottom: 20px;">
    <li style="margin-bottom: 8px; font-size: 1rem; color: #555;">Create your account (User or Admin)</li>
    <li style="margin-bottom: 8px; font-size: 1rem; color: #555;">Complete your profile</li>
    <li style="margin-bottom: 8px; font-size: 1rem; color: #555;">Access features based on your role</li>
    <li style="margin-bottom: 8px; font-size: 1rem; color: #555;">Stay connected with your community</li>
  </ol>

  <div style="direction: rtl; text-align: right; font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Arial', sans-serif;">
    <p style="margin: 6px 0; font-size: 1.1rem; color: #222;">ڈیجیٹل جماعت کا استعمال بہت آسان ہے:</p>
    <p style="margin: 6px 0; font-size: 1.1rem; color: #222;">١. اپنا اکاؤنٹ بنائیں (صارف یا ایڈمن)</p>
    <p style="margin: 6px 0; font-size: 1.1rem; color: #222;">٢. اپنی پروفائل مکمل کریں</p>
    <p style="margin: 6px 0; font-size: 1.1rem; color: #222;">٣. اپنے کردار کے مطابق خصوصیات تک رسائی حاصل کریں</p>
    <p style="margin: 6px 0; font-size: 1.1rem; color: #222;">٤. اپنی کمیونٹی سے جڑے رہیں</p>
  </div>
</div>

            </div>

            <div class="team-section" style="margin-top: 20px;">
                <h2>Our Leadership</h2>
                <div class="ceo-info">
                <img 
  src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" 
  alt="CEO Hafiz Ahsan Nasir"
  style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #333; margin-top: 10px;"
>


                    <h3>Hafiz Ahsan Nasir</h3>
                    <p class="designation">CEO, HeaveTech</p>
                    <div class="quote">
                        <i class="fas fa-quote-left"></i>
                        <p>Our mission is to empower Islamic communities through innovative technology solutions.</p>
                        <i class="fas fa-quote-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4>About Us</h4>
                    <ul>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Our Mission</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Leadership</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Community</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Features</h4>
                    <ul>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Community Management</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Analytics</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Resources</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Events</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Documentation</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Help Center</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> FAQs</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <ul>
                        <li><a href="#"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Digital Jamat - A HeaveTech Initiative. All rights reserved.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </footer>
</body>
</html>