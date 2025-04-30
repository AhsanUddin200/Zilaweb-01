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
</body>
</html>