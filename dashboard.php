<?php 
include 'db.php';
include 'get_daily_quotes_api.php';

$daily_quotes = getDailyQuotes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #006600;
            --primary-dark: #004d00;
            --white: #ffffff;
            --gradient: linear-gradient(135deg, var(--primary) 0%, #008800 100%);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #f0f2f5;
        }
        /* Updated Navbar */
        .navbar {
            background: var(--gradient);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        .nav-item {
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            transition: transform 0.3s;
        }
        .nav-item:hover {
            transform: translateY(-3px);
        }
        .nav-item i {
            font-size: 1.5em;
        }
        .nav-item span {
            font-size: 0.8em;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-section img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .logo-section h1 {
            color: white;
            font-size: 1.5rem;
        }
        /* Slider Styles */
        .slider-container {
            width: 100%;
            height: 300px;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .slider {
            width: 100%;
            height: 100%;
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-color: #eee; /* Placeholder color until images are added */
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
            transition: background 0.3s;
        }
        .slider-dot.active {
            background: var(--white);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo-section">
            <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Digital Jamat">
            <h1>Digital Jamat</h1>
        </div>
        <div class="nav-menu">
            <a href="#" class="nav-item">
                <i class="fas fa-bullhorn"></i>
                <span>دستور </span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-file-alt   "></i>
                <span>منشور</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-book"></i>
                <span>کتابیں</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-users"></i>
                <span>قیادت</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-calendar"></i>
                <span>تاریخ</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-kaaba"></i>
                <span>تعارف</span>
            </a>
        </div>
    </nav>

    <!-- Slider Section -->
    <div class="slider-container">
        <div class="slider">
            <div class="slide" style="background-image: url('assets/images/slider1.jpg')"></div>
            <div class="slide" style="background-image: url('assets/images/slider2.jpg')"></div>
            <div class="slide" style="background-image: url('assets/images/slider3.jpg')"></div>
        </div>
        <div class="slider-nav">
            <div class="slider-dot active"></div>
            <div class="slider-dot"></div>
            <div class="slider-dot"></div>
        </div>
    </div>

    <script>
        // Slider functionality
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');
        let currentSlide = 0;

        // Function to update slider
        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentSlide].classList.add('active');
        }

        // Add click events to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlider();
        }, 5000);
    </script>

    <!-- After slider section -->
        <style>
            .about-section {
                padding: 50px 5%;
                display: flex;
                gap: 30px;
                align-items: start;
                background: #fff;
                margin: 20px 0;
            }
            .about-image {
                flex: 0 0 25%;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .founder-title {
                text-align: center;
                margin-bottom: 20px;
                font-family: 'Noto Nastaliq Urdu', serif;
            }
            .founder-title h3 {
                color: var(--primary);
                font-size: 1.8em;
                margin-bottom: 5px;
            }
            .founder-title h4 {
                color: #333;
                font-size: 1.5em;
                margin-bottom: 5px;
            }
            .founder-title p {
                color: #666;
                font-size: 1.2em;
            }
            .about-image img {
                width: 100%;
                height: auto;
                display: block;
            }
            .about-content {
                flex: 0 0 75%;
                padding: 20px;
            }
            .about-heading {
                font-size: 2.5em;
                color: var(--primary);
                margin-bottom: 20px;
                text-align: right;
                font-family: 'Noto Nastaliq Urdu', serif;
            }
            .about-text {
                text-align: right;
                line-height: 1.8;
                color: #333;
                font-family: 'Noto Nastaliq Urdu', serif;
            }
            @media (max-width: 768px) {
                .about-section {
                    flex-direction: column;
                }
                .about-image, .about-content {
                    flex: 0 0 100%;
                }
            }
        </style>
    
        <!-- Content section -->
        <div class="about-section">
            <!-- Rest of your content -->
            <div class="about-image">
                <div class="founder-title">
                    <h3>جماعت اسلامی کے بانی</h3>
                    <h4>سید ابوالاعلیٰ مودودی</h4>
                    <p>(1903-1979)</p>
                </div>
                <img src="https://www.jamaat.org/themes/jip/assets/images/jui-founder.jpg" alt="Syed Abul Ala Maududi">
            </div>
            <div class="about-content">
                <h2 class="about-heading">جماعت اسلامی سب سے منفرد کیوں ہے ؟</h2>
                <div class="about-text">
                    <p>جماعت اسلامی اقامت دین کی تحریک ہے۔ اس کی بنیاد مفکر اسلام سید ابوالاعلی مودودیؒ نے 26 اگست 1941ء کو لاہور میں رکھی تھی۔ 73 افراد اور قلیل سرمائے سے آغاز کرنے والا قافلہ آج لاکھوں میں ہے۔ نہ صرف پاکستان بلکہ دنیا بھر میں اس کے اثرات محسوس کی جاتے ہیں۔ دنیا کے مختلف ممالک میں اسلامی تحریکوں نے سید مودودیؒ کی فکر سے فائدہ اٹھایا ہے۔</p>
                    <br>
                    <p>اسلام کو ایک مکمل ضابطہ حیات کے طور پر اپنانے کے لیے جماعت اسلامی لوگوں کے سامنے اسلام کی حقیقی تصویر پیش کرتی ہے تاکہ زندگی کے تمام پہلوؤں میں اسلامی تعلیمات کی پیروی کی اہمیت ان پر واضح ہو سکے۔ اللہ اور اس کے رسول ﷺ کی طرف سے مسلمانوں کو دیے گئے اسلام کے عادلانہ نظام کے نفاذ کے لیے جماعت اسلامی باکردار اور باصلاحیت افراد کی تیاری کے لیے مسلسل کوشاں ہے۔</p>
                    <br>
                    <p>پاکستان میں اسلامی نظام کی جدوجہد، قرارداد مقاصد کی منظوری، ختم نبوت کی تحریک، آئین پاکستان کی تیاری و منظوری، اتحاد امت اور خدمت خلق کے حوالے سے جماعت اسلامی کی خدمات نمایاں ہیں، جس کا سب اعتراف کرتے ہیں۔</p>
                </div>
            </div>
        </div>

    <!-- Manshoor Section remains unchanged -->
        <style>
            .manshoor-section {
                padding: 40px 5%;
                margin: 20px 0;
            }
            .manshoor-container {
                max-width: 1000px;
                margin: 0 auto;
            }
            .manshoor-title {
                font-size: 2em;
                color: #006600;
                margin-bottom: 25px;
                text-align: center;
                font-family: 'Noto Nastaliq Urdu', serif;
            }
            .manshoor-image {
                width: 100%;
                max-width: 900px;
                height: auto;
                margin: 0 auto;
                display: block;
            }
        </style>
    
        <div class="manshoor-section">
            <div class="manshoor-container">
                <h2 class="manshoor-title">منشور جماعت اسلامی</h2>
                <div class="manshoor-image">
                    <!-- Add your image here -->
                    <img src="https://www.jamaat.org/themes/jip/assets/images/achievements_grafix_white.jpg" alt="Manshoor Jamaat e Islami" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>

        <!-- Daily Quotes Section -->
            <style>
                .daily-section {
                    padding: 30px 5%;
                    background: #fff;
                    margin: 20px 0;
                }
                .daily-container {
                    display: flex;
                    gap: 25px;
                    max-width: 1000px;
                    margin: 0 auto;
                }
                .daily-card {
                    flex: 1;
                    background: #fff;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    overflow: hidden;
                }
                .daily-title {
                    background: #006699;
                    color: white;
                    padding: 12px;
                    text-align: center;
                    font-family: 'Noto Nastaliq Urdu', serif;
                    font-size: 1.3em;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                }
                .daily-title i {
                    font-size: 1.1em;
                }
                .daily-content {
                    padding: 20px;
                }
                .arabic-text {
                    font-family: 'Noto Naskh Arabic', serif;
                    font-size: 1.4em;
                    line-height: 1.8;
                    margin-bottom: 15px;
                    text-align: right;
                    color: #333;
                }
                .urdu-text {
                    font-family: 'Noto Nastaliq Urdu', serif;
                    font-size: 1.2em;
                    line-height: 1.8;
                    text-align: right;
                    color: #444;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #eee;
                }
                .daily-reference {
                    text-align: right;
                    color: #006699;
                    font-family: 'Noto Nastaliq Urdu', serif;
                    font-size: 1em;
                    padding-top: 10px;
                }
                @media (max-width: 768px) {
                    .daily-container {
                        flex-direction: column;
                    }
                    .daily-card {
                        margin-bottom: 25px;
                    }
                }
            </style>
                <div class="daily-container">
                    <div class="daily-card">
                        <div class="daily-title">
                            <i class="fas fa-quran"></i>
                            روزانہ قرآن
                        </div>
                        <div class="daily-content">
                            <p class="arabic-text"><?php echo $daily_quotes['quran']['arabic']; ?></p>
                            <p class="urdu-text"><?php echo $daily_quotes['quran']['urdu']; ?></p>
                            <div class="daily-reference"><?php echo $daily_quotes['quran']['reference']; ?></div>
                        </div>
                    </div>
            
                    <div class="daily-card">
                        <div class="daily-title">
                            <i class="fas fa-book-open"></i>
                            روزانہ کی حدیث
                        </div>
                        <div class="daily-content">
                            <p><?php echo $daily_quotes['hadith']['text']; ?></p>
                            <div class="daily-reference"><?php echo $daily_quotes['hadith']['reference']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Social Impact Section -->
    <style>
        .impact-section {
            padding: 50px 5%;
            background: #f8f9fa;
        }
        .impact-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .impact-title {
            font-size: 2.2em;
            color: var(--primary);
            text-align: center;
            margin-bottom: 40px;
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .impact-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .impact-card:hover {
            transform: translateY(-5px);
        }
        .impact-image {
            height: 200px;
            overflow: hidden;
        }
        .impact-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .impact-content {
            padding: 20px;
            text-align: right;
        }
        .impact-content h3 {
            color: var(--primary);
            font-size: 1.4em;
            margin-bottom: 10px;
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .impact-content p {
            color: #666;
            line-height: 1.6;
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .impact-stats {
            background: var(--primary);
            color: white;
            padding: 15px;
            text-align: center;
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        @media (max-width: 768px) {
            .impact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="impact-section">
        <div class="impact-container">
            <h2 class="impact-title">سماجی خدمات</h2>
            <div class="impact-grid">
                <div class="impact-card">
                    <div class="impact-image">
                        <img src="assets/images/welfare.jpg" alt="Welfare Projects">
                    </div>
                    <div class="impact-content">
                        <h3>فلاحی منصوبے</h3>
                        <p>غریبوں، یتیموں اور بیواؤں کی مدد کے لیے مختلف فلاحی منصوبے</p>
                    </div>
                    <div class="impact-stats">
                        سالانہ 10,000+ افراد مستفید
                    </div>
                </div>

                <div class="impact-card">
                    <div class="impact-image">
                        <img src="assets/images/education.jpg" alt="Educational Services">
                    </div>
                    <div class="impact-content">
                        <h3>تعلیمی خدمات</h3>
                        <p>اسکول، کالج اور جامعات کے ذریعے معیاری تعلیم کی فراہمی</p>
                    </div>
                    <div class="impact-stats">
                        500+ تعلیمی ادارے
                    </div>
                </div>

                <div class="impact-card">
                    <div class="impact-image">
                        <img src="assets/images/healthcare.jpg" alt="Healthcare Services">
                    </div>
                    <div class="impact-content">
                        <h3>صحت کی سہولیات</h3>
                        <p>ہسپتال، کلینک اور موبائل ہیلتھ یونٹس کے ذریعے علاج کی سہولت</p>
                    </div>
                    <div class="impact-stats">
                        سالانہ 50,000+ مریض
                    </div>
                </div>

                <div class="impact-card">
                    <div class="impact-image">
                        <img src="assets/images/community.jpg" alt="Community Services">
                    </div>
                    <div class="impact-content">
                        <h3>کمیونٹی سروسز</h3>
                        <p>مختلف سماجی پروگرام، تربیتی ورکشاپس اور کمیونٹی سینٹرز</p>
                    </div>
                    <div class="impact-stats">
                        100+ کمیونٹی سینٹرز
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Regional Activities Map Section -->
        <style>
            .map-section {
                padding: 50px 5%;
                background: #fff;
            }
            .map-container {
                max-width: 1200px;
                margin: 0 auto;
            }
            .map-title {
                font-size: 2.2em;
                color: var(--primary);
                text-align: center;
                margin-bottom: 30px;
                font-family: 'Noto Nastaliq Urdu', serif;
            }
            .map-wrapper {
                display: flex;
                gap: 30px;
                margin-bottom: 30px;
            }
            #jiMap {
                flex: 1;
                height: 500px;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .location-details {
                flex: 0 0 300px;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 12px;
                font-family: 'Noto Nastaliq Urdu', serif;
                text-align: right;
            }
            .location-title {
                color: var(--primary);
                font-size: 1.4em;
                margin-bottom: 15px;
            }
            .location-info {
                margin-bottom: 20px;
            }
            .location-info p {
                margin: 8px 0;
                color: #666;
            }
            .location-events {
                border-top: 1px solid #ddd;
                padding-top: 15px;
            }
            .event-item {
                margin: 10px 0;
                padding: 10px;
                background: white;
                border-radius: 8px;
            }
        </style>
    
        <div class="map-section">
            <div class="map-container">
                <h2 class="map-title">مقامی دفاتر</h2>
                <div class="map-wrapper">
                    <div id="jiMap"></div>
                    <div class="location-details">
                        <h3 class="location-title">مرکزی دفتر</h3>
                        <div class="location-info">
                            <p><i class="fas fa-map-marker-alt"></i> منصورہ، لاہور</p>
                            <p><i class="fas fa-phone"></i> 042-35330333</p>
                            <p><i class="fas fa-envelope"></i> info@jamaat.org</p>
                        </div>
                        <div class="location-events">
                            <h4>آنے والے پروگرام</h4>
                            <div class="event-item">
                                <p>ہفتہ وار اجتماع</p>
                                <small>ہر بدھ - صبح 9 بجے</small>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this in the head section -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <!-- Replace the Google Maps script with this -->
        <script>
            // JI Centers Data
            const jiCenters = [
                {
                    name: 'مرکزی دفتر منصورہ',
                    position: [31.5204, 74.3587],
                    address: 'منصورہ، لاہور',
                    phone: '042-35330333',
                    email: 'info@jamaat.org'
                },
                {
                    name: 'کراچی دفتر',
                    position: [24.9048, 67.0653], // Updated coordinates for Shikarpur Colony
                    address: 'ادارہ نور حق، 503، شکارپور کالونی، مسلم آباد، کراچی',
                    phone: '021-35375566',
                    email: 'karachi@jamaat.org'
                },
                {
                    name: 'اسلام آباد دفتر',
                    position: [33.6844, 73.0479],
                    address: 'سیکٹر ایف، اسلام آباد',
                    phone: '051-2876543',
                    email: 'isb@jamaat.org'
                }
            ];

            // Initialize Map
            function initMap() {
                const map = L.map('jiMap').setView([30.3753, 69.3451], 6);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Add markers for each center
                jiCenters.forEach(center => {
                    const marker = L.marker(center.position)
                        .addTo(map)
                        .bindPopup(center.name);

                    marker.on('click', () => {
                        updateLocationDetails(center);
                    });
                });
            }

            // Update location details panel
            function updateLocationDetails(center) {
                document.querySelector('.location-title').textContent = center.name;
                document.querySelector('.location-info').innerHTML = `
                    <p><i class="fas fa-map-marker-alt"></i> ${center.address}</p>
                    <p><i class="fas fa-phone"></i> ${center.phone}</p>
                    <p><i class="fas fa-envelope"></i> ${center.email}</p>
                `;
            }

            // Initialize map when page loads
            window.onload = initMap;
        </script>
    </script>

    <!-- Member Achievements Section -->
        <style>
            .achievements-section {
                padding: 50px 5%;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            }
            .achievements-container {
                max-width: 1200px;
                margin: 0 auto;
            }
            .achievements-title {
                font-size: 2.2em;
                color: var(--primary);
                text-align: center;
                margin-bottom: 40px;
                font-family: 'Noto Nastaliq Urdu', serif;
            }
            .success-stories {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
                margin-bottom: 50px;
                overflow: hidden;
            }
            .story-card {
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                text-align: right;
                opacity: 0;
                transform: translateX(100px);
                transition: all 0.5s ease;
                display: none;
            }
            .story-card.active {
                opacity: 1;
                transform: translateX(0);
                display: block;
            }
            .member-info {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                margin-bottom: 15px;
                gap: 15px;
            }
            .member-image {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                object-fit: cover;
            }
            .member-details h4 {
                color: var(--primary);
                font-family: 'Noto Nastaliq Urdu', serif;
                margin-bottom: 5px;
            }
            .member-details p {
                color: #666;
                font-size: 0.9em;
            }
            .story-content {
                font-family: 'Noto Nastaliq Urdu', serif;
                line-height: 1.6;
                color: #444;
            }
            .stats-counter {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin: 40px 0;
                text-align: center;
            }
            .counter-item {
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            }
            .counter-number {
                font-size: 2.5em;
                color: var(--primary);
                font-weight: bold;
                margin-bottom: 10px;
            }
            .counter-label {
                font-family: 'Noto Nastaliq Urdu', serif;
                color: #444;
            }
        </style>
    
        <div class="achievements-section">
            <div class="achievements-container">
                <h2 class="achievements-title">کامیابیاں اور خدمات</h2>
                
                <div class="stats-counter">
                    <div class="counter-item">
                        <div class="counter-number" data-target="50000">0</div>
                        <div class="counter-label">سرگرم رکنیت</div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-number" data-target="1000">0</div>
                        <div class="counter-label">مقامی یونٹس</div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-number" data-target="200">0</div>
                        <div class="counter-label">فلاحی منصوبے</div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-number" data-target="5000">0</div>
                        <div class="counter-label">رضاکار</div>
                    </div>
                </div>
    
                <div class="success-stories">
                    <div class="story-card">
                        <div class="member-info">
                            <div class="member-details">
                                <h4>محمد عمران</h4>
                                <p>کراچی</p>
                            </div>
                            <img src="assets/images/member1.jpg" alt="Member" class="member-image">
                        </div>
                        <div class="story-content">
                            <p>میں نے جماعت اسلامی کے پلیٹ فارم سے تعلیم یافتہ نوجوانوں کو روزگار دلوانے میں مدد کی۔ الحمدللہ اب 100 سے زائد نوجوان کامیابی سے اپنا کاروبار چلا رہے ہیں۔</p>
                    </div>
                </div>
    
                <div class="story-card">
                    <div class="member-info">
                        <div class="member-details">
                            <h4>عائشہ فاطمہ</h4>
                            <p>لاہور</p>
                        </div>
                        <img src="assets/images/member2.jpg" alt="Member" class="member-image">
                    </div>
                    <div class="story-content">
                        <p>خواتین کی تعلیم و تربیت کے لیے ہمارے سینٹر میں 500 سے زائد خواتین مستفید ہو رہی ہیں۔ یہ سب جماعت اسلامی کی مدد سے ممکن ہوا۔</p>
                    </div>
                </div>
    
                <div class="story-card">
                    <div class="member-info">
                        <div class="member-details">
                            <h4>عبدالرحمن</h4>
                            <p>پشاور</p>
                        </div>
                        <img src="assets/images/member3.jpg" alt="Member" class="member-image">
                    </div>
                    <div class="story-content">
                        <p>سیلاب متاثرین کی امداد کے لیے ہمارے رضاکاروں نے دن رات محنت کی۔ 1000 سے زائد خاندانوں کو بحال کیا گیا۔</p>
                    </div>
                </div>
                <div class="story-card">
                        <div class="member-info">
                            <div class="member-details">
                                <h4>سارہ خان</h4>
                                <p>اسلام آباد</p>
                            </div>
                            <img src="assets/images/member4.jpg" alt="Member" class="member-image">
                        </div>
                        <div class="story-content">
                            <p>ہمارے خواتین کے لیے ہنر سکھانے کے پروگرام سے 300 خواتین نے اپنا کاروبار شروع کیا۔</p>
                        </div>
                    </div>

                    <!-- Fifth Card -->
                    <div class="story-card">
                        <div class="member-info">
                            <div class="member-details">
                                <h4>عمر فاروق</h4>
                                <p>کوئٹہ</p>
                            </div>
                            <img src="assets/images/member5.jpg" alt="Member" class="member-image">
                        </div>
                        <div class="story-content">
                            <p>ہمارے تعلیمی مراکز میں 500 طلبہ مفت تعلیم حاصل کر رہے ہیں۔</p>
                        </div>
                    </div>

                    <!-- Sixth Card -->
                    <div class="story-card">
                        <div class="member-info">
                            <div class="member-details">
                                <h4>حفصہ علی</h4>
                                <p>ملتان</p>
                            </div>
                            <img src="assets/images/member6.jpg" alt="Member" class="member-image">
                        </div>
                        <div class="story-content">
                            <p>ہمارے صحت کے پروگرام سے 2000 مریضوں کو مفت علاج کی سہولت فراہم کی گئی۔</p>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script>
        // Animated Counter
        const counters = document.querySelectorAll('.counter-number');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-target'));
            const count = parseInt(counter.innerText);
            const increment = target / 200;

            if(count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(() => animateCounter(counter), 1);
            } else {
                counter.innerText = target;
            }
        };

        // Start animation when section is in view
        const observerCallback = (entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    counters.forEach(counter => animateCounter(counter));
                }
            });
        };

        const observer = new IntersectionObserver(observerCallback);
        observer.observe(document.querySelector('.stats-counter'));
    </script>

    <script>
            const storyCards = document.querySelectorAll('.story-card');
            let currentIndex = 0;
    
            function showCards() {
                // Hide all cards
                storyCards.forEach(card => {
                    card.classList.remove('active');
                    card.style.display = 'none';
                });
    
                // Show current 3 cards
                for(let i = 0; i < 3; i++) {
                    let index = (currentIndex + i) % storyCards.length;
                    storyCards[index].style.display = 'block';
                    setTimeout(() => {
                        storyCards[index].classList.add('active');
                    }, 50);
                }
    
                // Update index for next rotation
                currentIndex = (currentIndex + 1) % storyCards.length;
            }
    
            // Show initial cards
            showCards();
    
            // Rotate every 4 seconds
            setInterval(showCards, 4000);
        </script>
</body>
</html>

<!-- Add this style section -->
<style>
    .donation-main-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        text-align: right;
        max-width: 850px;
        margin: 50px auto;
        font-family: 'Noto Nastaliq Urdu', serif;
        border-top: 8px solid #006600;
    }

    .donation-main-title {
        color: #006600;
        font-size: 2.6em;
        margin-bottom: 15px;
        border-bottom: 2px dashed #cccccc;
        padding-bottom: 12px;
        text-align: center;
    }

    .donation-main-content {
        margin: 20px 0;
        font-size: 1.4em;
        line-height: 2.2;
        color: #333;
    }

    .donate-button {
        background-color: #006600;
        color: white;
        font-size: 1.2em;
        padding: 14px 30px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s;
        display: block;
        margin: 30px auto 0;
        font-family: 'Noto Nastaliq Urdu', serif;
    }

    .donate-button:hover {
        background-color: #004d00;
        transform: scale(1.05);
    }

    .donation-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .donation-modal-content {
        background: #ffffff;
        padding: 40px;
        border-radius: 18px;
        max-width: 600px;
        width: 90%;
        text-align: right;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal-close {
        position: absolute;
        left: 20px;
        top: 15px;
        cursor: pointer;
        font-size: 28px;
        color: #666;
    }

    .modal-close:hover {
        color: #000;
    }

    .bank-details {
        font-size: 1.2em;
        line-height: 2.2;
        color: #222;
    }

    .bank-details h3 {
        color: #006600;
        font-size: 1.9em;
        margin-bottom: 15px;
    }

    .bank-details h4 {
        margin-bottom: 20px;
        font-size: 1.4em;
        color: #444;
    }

    .bank-details strong {
        color: #006600;
        margin-left: 10px;
    }
</style>


<!-- HTML Section -->
<div class="donation-main-card">
    <h2 class="donation-main-title">بیت المال جماعت اسلامی</h2>
    <div class="donation-main-content">
    <p>آپ کے عطیات اقامت دین، مساجد، مدارس، دعوت و تبلیغ، اسلامی تعلیم و تربیت اور دیگر دینی امور کے لیے استعمال کیے جائیں گے۔ آپ کا ہر روپیہ اللہ کی راہ میں صرف ہوگا۔ آئیے! اس نیک کام میں شریک ہوں۔</p>
        </div>
    <button class="donate-button">عطیہ دیں</button>
</div>

<div class="donation-modal">
    <div class="donation-modal-content">
        <span class="modal-close">&times;</span>
        <div class="bank-details">
            <h3>تفصیلات برائے عطیات</h3>
            <h4>بینک ٹرانسفر کی معلومات</h4>
            <p><strong>اکاؤنٹ ٹائیٹل:</strong> Jamaat-e-Islami Pakistan</p>
            <p><strong>اکاؤنٹ نمبر:</strong> 0102535857</p>
            <p><strong>IBAN:</strong> PK38MEZN0002010102535857</p>
            <p><strong>برانچ کوڈ:</strong> 0201</p>
            <p><strong>بینک:</strong> میزان بینک، گلبرگ برانچ لاہور</p>
            <p><strong>رابطہ:</strong> مرکزی شعبہ مالیات، منصورہ ملتان روڈ لاہور</p>
            <p><strong>فون:</strong> +92 42 35419520-4</p>
        </div>
    </div>
</div>

<script>
    const donateBtn = document.querySelector('.donate-button');
    const modal = document.querySelector('.donation-modal');
    const closeBtn = document.querySelector('.modal-close');

    donateBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
