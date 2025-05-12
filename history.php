<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تاریخ - Digital Jamat</title>
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
            direction: rtl;
        }
        .history-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .page-title {
            text-align: center;
            color: var(--primary);
            font-size: 3em;
            margin-bottom: 50px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .timeline {
            position: relative;
            padding: 40px 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            right: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient);
            border-radius: 2px;
        }
        .timeline-item {
            margin-bottom: 60px;
            position: relative;
            width: 100%;
        }
        .timeline-content {
            position: relative;
            width: calc(50% - 30px);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .timeline-item:nth-child(odd) .timeline-content {
            margin-right: auto;
        }
        .timeline-date {
            position: absolute;
            top: 0;
            right: -90px;
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1.1em;
        }
        .timeline-item:nth-child(odd) .timeline-date {
            right: auto;
            left: -90px;
        }
        .timeline-title {
            font-size: 1.8em;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        .timeline-text {
            font-size: 1.1em;
            line-height: 1.8;
            color: #444;
        }
        .timeline-icon {
            position: absolute;
            right: calc(50% - 25px);
            width: 50px;
            height: 50px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5em;
            box-shadow: 0 0 0 4px white;
        }
        .milestone-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 50px 0;
        }
        .milestone-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .milestone-card:hover {
            transform: translateY(-5px);
        }
        .milestone-number {
            font-size: 2.5em;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .milestone-text {
            font-size: 1.2em;
            color: #444;
        }
        @media (max-width: 768px) {
            .timeline::before {
                right: 30px;
            }
            .timeline-content {
                width: calc(100% - 60px);
                margin-right: 60px !important;
            }
            .timeline-date {
                right: -150px;
                top: -40px;
            }
            .timeline-item:nth-child(odd) .timeline-date {
                right: -150px;
                left: auto;
            }
            .timeline-icon {
                right: 5px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="history-container">
        <h1 class="page-title">تاریخی سفر</h1>

        <div class="milestone-grid">
            <div class="milestone-card">
                <div class="milestone-number">80+</div>
                <div class="milestone-text">سال خدمت</div>
            </div>
            <div class="milestone-card">
                <div class="milestone-number">5</div>
                <div class="milestone-text">امیر جماعت</div>
            </div>
            <div class="milestone-card">
                <div class="milestone-number">70+</div>
                <div class="milestone-text">ممالک میں موجودگی</div>
            </div>
            <div class="milestone-card">
                <div class="milestone-number">1000+</div>
                <div class="milestone-text">کتب و رسائل</div>
            </div>
        </div>

        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">1941</div>
                    <h3 class="timeline-title">قیام جماعت اسلامی</h3>
                    <p class="timeline-text">مولانا سید ابوالاعلیٰ مودودیؒ نے لاہور میں جماعت اسلامی کی بنیاد رکھی۔</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-flag"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">1947</div>
                    <h3 class="timeline-title">قیام پاکستان</h3>
                    <p class="timeline-text">پاکستان کے قیام کے بعد جماعت اسلامی نے اسلامی نظام کے نفاذ کے لیے جدوجہد شروع کی۔</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">1950</div>
                    <h3 class="timeline-title">تعلیمی خدمات کا آغاز</h3>
                    <p class="timeline-text">پہلے اسلامی تعلیمی ادارے کا قیام عمل میں آیا۔</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">1960</div>
                    <h3 class="timeline-title">فلاحی خدمات کا آغاز</h3>
                    <p class="timeline-text">الخدمت فاؤنڈیشن کے ذریعے سماجی خدمات کا آغاز۔</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">1970</div>
                    <h3 class="timeline-title">طبی خدمات کا آغاز</h3>
                    <p class="timeline-text">پہلے اسلامی طبی مرکز کا قیام۔</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">2000</div>
                    <h3 class="timeline-title">عالمی توسیع</h3>
                    <p class="timeline-text">دنیا بھر میں جماعت اسلامی کے مراکز کا قیام۔</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-digital-tachograph"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-date">2023</div>
                    <h3 class="timeline-title">ڈیجیٹل دور میں قدم</h3>
                    <p class="timeline-text">جدید ٹیکنالوجی کے ذریعے اسلامی پیغام کی ترویج۔</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>