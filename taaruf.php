<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعارف - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&display=swap" rel="stylesheet">
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
        }
        body {
            background: #f0f2f5;
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .taaruf-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .taaruf-section {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            text-align: right;
            position: relative;
            overflow: hidden;
        }
        .taaruf-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
        }
        .section-title {
            color: var(--primary);
            font-size: 2.4em;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
            position: relative;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            right: 0;
            width: 100px;
            height: 2px;
            background: var(--primary);
        }
        .section-content {
            line-height: 2;
            font-size: 1.2em;
            color: #444;
        }
        .section-content p {
            margin-bottom: 20px;
        }
        .highlight-box {
            background: linear-gradient(to left, #f8f9fa, #ffffff);
            border-right: 4px solid var(--primary);
            padding: 25px;
            margin: 30px 0;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        .objectives-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .objectives-list li {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            position: relative;
            padding-right: 35px;
            transition: all 0.3s ease;
        }
        .objectives-list li:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .objectives-list li::before {
            content: '•';
            color: var(--primary);
            position: absolute;
            right: 15px;
            font-size: 1.5em;
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }
        .service-card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .service-icon {
            font-size: 2.5em;
            color: var(--primary);
            margin-bottom: 15px;
        }
        .service-title {
            font-size: 1.3em;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }
        .founder-section {
            display: flex;
            gap: 30px;
            align-items: center;
            margin: 40px 0;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
        }
        .founder-image {
            flex: 0 0 200px;
        }
        .founder-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .founder-content {
            flex: 1;
        }
        .founder-name {
            font-size: 1.8em;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .founder-title {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .founder-section {
                flex-direction: column;
                text-align: center;
            }
            .founder-image {
                flex: 0 0 auto;
                max-width: 200px;
                margin: 0 auto;
            }
            .section-title {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="animate-text">جماعت اسلامی پاکستان</h1>
            <p class="animate-text-delay">اسلامی پاکستان کی تعمیر کے لیے کوشاں</p>
            <div class="hero-buttons">
                <a href="#about" class="hero-btn">مزید جانیں</a>
                <a href="#contact" class="hero-btn outline">رابطہ کریں</a>
            </div>
        </div>
    </div>

    <!-- Add these styles -->
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1564769625905-50e93615e769?q=80&w=1744&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 600px;
            position: relative;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0,102,0,0.8), rgba(0,0,0,0.8));
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-size: 4.5em;
            margin-bottom: 30px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease forwards;
        }

        .hero-content p {
            font-size: 2em;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease 0.3s forwards;
        }

        .hero-buttons {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            justify-content: center;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease 0.6s forwards;
        }

        .hero-btn {
            padding: 15px 35px;
            font-size: 1.2em;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .hero-btn.outline {
            background: transparent;
            border: 2px solid white;
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 500px;
            }
            .hero-content h1 {
                font-size: 3em;
            }
            .hero-content p {
                font-size: 1.5em;
            }
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            .hero-btn {
                width: 80%;
            }
        }
    </style>

    <div class="taaruf-container">
        <!-- Introduction Section -->
        <div class="taaruf-section">
            <h2 class="section-title">تعارف جماعت اسلامی</h2>
            <div class="section-content">
                <div class="intro-grid">
                    <div class="intro-text">
                        <p>جماعت اسلامی پاکستان ایک اسلامی تحریک ہے جو اسلامی نظام کے نفاذ اور معاشرے کی اصلاح کے لیے کوشاں ہے۔ اس کی بنیاد 1941ء میں مولانا سید ابوالاعلیٰ مودودیؒ نے رکھی۔</p>
                        
                        <div class="highlight-box primary">
                            <i class="fas fa-quote-right quote-icon"></i>
                            <p>ہمارا مقصد اسلامی تعلیمات کو عملی طور پر نافذ کرنا اور معاشرے میں اسلامی اقدار کو فروغ دینا ہے۔</p>
                        </div>

                        <div class="key-points">
                            <div class="point-card">
                                <i class="fas fa-history"></i>
                                <h4>تاریخی جدوجہد</h4>
                                <p>1941 سے مسلسل اسلامی نظام کے لیے جدوجہد جاری</p>
                            </div>
                            <div class="point-card">
                                <i class="fas fa-globe-asia"></i>
                                <h4>عالمی نیٹ ورک</h4>
                                <p>پاکستان سمیت 70 سے زائد ممالک میں موجودگی</p>
                            </div>
                            <div class="point-card">
                                <i class="fas fa-book-reader"></i>
                                <h4>علمی خدمات</h4>
                                <p>ہزاروں کتب و رسائل کی اشاعت</p>
                            </div>
                        </div>

                        <div class="highlight-box secondary">
                            <i class="fas fa-lightbulb quote-icon"></i>
                            <p>جماعت اسلامی کا نصب العین ایک ایسے معاشرے کی تشکیل ہے جہاں عدل و انصاف، امن و آشتی اور اسلامی اقدار کی حکمرانی ہو۔</p>
                        </div>

                        <div class="achievements-grid">
                            <div class="achievement-item">
                                <span class="achievement-number">75+</span>
                                <span class="achievement-text">سال خدمت</span>
                            </div>
                            <div class="achievement-item">
                                <span class="achievement-number">1000+</span>
                                <span class="achievement-text">تعلیمی ادارے</span>
                            </div>
                            <div class="achievement-item">
                                <span class="achievement-number">100+</span>
                                <span class="achievement-text">ہسپتال</span>
                            </div>
                            <div class="achievement-item">
                                <span class="achievement-number">5000+</span>
                                <span class="achievement-text">مساجد</span>
                            </div>
                        </div>
                    </div>

                    <style>
                        .key-points {
                            display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                            gap: 20px;
                            margin: 30px 0;
                        }

                        .point-card {
                            background: #fff;
                            padding: 20px;
                            border-radius: 12px;
                            text-align: center;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                            transition: all 0.3s ease;
                        }

                        .point-card:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                        }

                        .point-card i {
                            font-size: 2em;
                            color: var(--primary);
                            margin-bottom: 15px;
                        }

                        .point-card h4 {
                            color: var(--primary-dark);
                            margin-bottom: 10px;
                            font-size: 1.3em;
                        }

                        .highlight-box {
                            position: relative;
                            padding: 30px;
                            margin: 40px 0;
                        }

                        .highlight-box.primary {
                            background: linear-gradient(to left, #f8f9fa, #ffffff);
                            border-right: 4px solid var(--primary);
                        }

                        .highlight-box.secondary {
                            background: linear-gradient(to right, #f8f9fa, #ffffff);
                            border-left: 4px solid #004d00;
                        }

                        .quote-icon {
                            position: absolute;
                            top: -15px;
                            right: 20px;
                            font-size: 2em;
                            color: var(--primary);
                            background: white;
                            padding: 10px;
                            border-radius: 50%;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        }

                        .achievements-grid {
                            display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                            gap: 20px;
                            margin: 40px 0;
                        }

                        .achievement-item {
                            background: var(--gradient);
                            padding: 20px;
                            border-radius: 12px;
                            text-align: center;
                            color: white;
                            transition: all 0.3s ease;
                        }

                        .achievement-item:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                        }

                        .achievement-number {
                            display: block;
                            font-size: 2em;
                            font-weight: bold;
                            margin-bottom: 5px;
                        }

                        .achievement-text {
                            font-size: 1.1em;
                        }

                        @media (max-width: 768px) {
                            .key-points {
                                grid-template-columns: 1fr;
                            }
                            .achievements-grid {
                                grid-template-columns: repeat(2, 1fr);
                            }
                        }
                    </style>
                    <div class="intro-stats">
                        <div class="stat-card">
                            <i class="fas fa-mosque"></i>
                            <h3>10,000+</h3>
                            <p>مراکز</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <h3>1 ملین+</h3>
                            <p>ارکان</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-hand-holding-heart"></i>
                            <h3>500+</h3>
                            <p>فلاحی منصوبے</p>
                        </div>
                    </div>
                </div>

                <!-- Existing founder section with improvements -->
                <div class="founder-section">
                    <div class="founder-image">
                        <img src="assets/images/maududi.jpg" alt="مولانا مودودی">
                        <div class="founder-overlay">
                            <span>1941 - 1979</span>
                        </div>
                    </div>
                    <div class="founder-content">
                        <h3 class="founder-name">مولانا سید ابوالاعلیٰ مودودیؒ</h3>
                        <h4 class="founder-title">بانی جماعت اسلامی</h4>
                        <p>مولانا مودودی نے 1941ء میں جماعت اسلامی کی بنیاد رکھی۔ آپ نے اسلامی نظریات کی تشریح و توضیح کے لیے بے شمار کتب تصنیف کیں اور اسلامی تحریک کو ایک نئی سمت دی۔</p>
                        <div class="founder-achievements">
                            <div class="achievement">
                                <i class="fas fa-book"></i>
                                <span>120+ کتب</span>
                            </div>
                            <div class="achievement">
                                <i class="fas fa-globe"></i>
                                <span>32 زبانوں میں ترجمہ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing objectives section with improvements -->
                <div class="objectives-section">
                    <h3 class="section-title">ہمارے مقاصد</h3>
                    <ul class="objectives-list">
                        <li>
                            <div class="objective-icon"><i class="fas fa-scale-balanced"></i></div>
                            <div class="objective-content">
                                <h4>اسلامی نظام کا نفاذ</h4>
                                <p>معاشرے میں اسلامی قوانین کا نفاذ</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-hands-holding-child"></i></div>
                            <div class="objective-content">
                                <h4>معاشرتی اصلاح و فلاح</h4>
                                <p>معاشرے کی بہتری کے لیے کوشاں</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="objective-content">
                                <h4>تعلیم و تربیت کا فروغ</h4>
                                <p>اعلیٰ معیار کی اسلامی تعلیم کا نظام</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-gavel"></i></div>
                            <div class="objective-content">
                                <h4>سماجی انصاف کا قیام</h4>
                                <p>معاشرے میں انصاف کی فراہمی</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-heart"></i></div>
                            <div class="objective-content">
                                <h4>اخلاقی اقدار کی ترویج</h4>
                                <p>اسلامی اخلاقیات کو فروغ دینا</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-handshake"></i></div>
                            <div class="objective-content">
                                <h4>امت مسلمہ کی وحدت</h4>
                                <p>مسلمانوں کے درمیان اتحاد کا فروغ</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-book-quran"></i></div>
                            <div class="objective-content">
                                <h4>دینی تعلیم کا فروغ</h4>
                                <p>قرآن و سنت کی تعلیمات کا نفاذ</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-hand-holding-medical"></i></div>
                            <div class="objective-content">
                                <h4>صحت و تعلیم</h4>
                                <p>صحت اور تعلیم کی سہولیات کی فراہمی</p>
                            </div>
                        </li>
                        <li>
                            <div class="objective-icon"><i class="fas fa-people-carry-box"></i></div>
                            <div class="objective-content">
                                <h4>غریب پروری</h4>
                                <p>غریبوں کی امداد اور بحالی</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <style>
                    .objectives-list {
                        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                        gap: 25px;
                    }
                    
                    .objectives-list li {
                        display: flex;
                        align-items: flex-start;
                        gap: 15px;
                        background: #ffffff;
                        padding: 20px;
                        border-radius: 12px;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                        transition: all 0.3s ease;
                    }

                    .objective-icon {
                        background: var(--gradient);
                        width: 50px;
                        height: 50px;
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 1.5em;
                        flex-shrink: 0;
                    }

                    .objective-content {
                        flex: 1;
                    }

                    .objective-content h4 {
                        color: var(--primary-dark);
                        margin-bottom: 8px;
                        font-size: 1.2em;
                    }

                    .objective-content p {
                        color: #666;
                        font-size: 0.9em;
                        margin: 0;
                    }

                    .objectives-list li:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                    }

                    .objectives-list li:hover .objective-icon {
                        transform: scale(1.1);
                    }

                    @media (max-width: 768px) {
                        .objectives-list {
                            grid-template-columns: 1fr;
                        }
                    }
                </style>

                <!-- Enhanced services section -->
                <h3 class="section-title">ہماری خدمات</h3>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4 class="service-title">تعلیمی خدمات</h4>
                        <p>اسکول، کالج اور جامعات کا قیام</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h4 class="service-title">طبی مراکز</h4>
                        <p>ہسپتال اور کلینک</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4 class="service-title">فلاحی کام</h4>
                        <p>یتیم خانے اور امدادی منصوبے</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add new styles -->
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .hero-content h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-content p {
            font-size: 1.8em;
            opacity: 0.9;
        }

        .intro-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stat-card i {
            font-size: 2em;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 1.8em;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .founder-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px;
            text-align: center;
        }

        .founder-achievements {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .achievement {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(0,102,0,0.1);
            padding: 10px 15px;
            border-radius: 20px;
        }

        .timeline {
            position: relative;
            margin: 40px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            right: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary);
        }

        .timeline-item {
            display: flex;
            justify-content: flex-end;
            padding-right: 30px;
            margin-bottom: 30px;
            position: relative;
        }

        .timeline-date {
            position: absolute;
            right: -20px;
            background: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
        }

        .timeline-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            width: calc(50% - 50px);
        }

        @media (max-width: 768px) {
            .intro-grid {
                grid-template-columns: 1fr;
            }
            
            .timeline::before {
                right: 20px;
            }

            .timeline-item {
                padding-right: 45px;
            }

            .timeline-content {
                width: calc(100% - 50px);
            }
        }
    </style>

    <?php include 'footer.php'; ?>
</body>
</html>