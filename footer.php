<style>
    .footer {
        background: linear-gradient(135deg, #004d00 0%, #003300 100%);
        color: white;
        padding: 80px 0 20px;
        font-family: 'Noto Nastaliq Urdu', serif;
        position: relative;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #90EE90, #004d00, #90EE90);
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 40px;
    }

    .footer-section {
        text-align: right;
    }

    .footer-about {
        padding-left: 30px;
    }

    .footer-logo {
        width: 180px;
        margin-bottom: 20px;
    }

    .footer-about p {
        line-height: 1.8;
        margin-bottom: 20px;
        color: #e0e0e0;
    }

    .footer-title {
        font-size: 1.5em;
        margin-bottom: 25px;
        color: #fff;
        position: relative;
        padding-bottom: 15px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        right: 0;
        bottom: 0;
        width: 60px;
        height: 3px;
        background: #90EE90;
        border-radius: 2px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }

    .footer-links li:hover {
        transform: translateX(-8px);
    }

    .footer-links a {
        color: #ffffff;
        text-decoration: none;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .footer-links a:hover {
        color: #90EE90;
    }

    .footer-links a i {
        font-size: 0.8em;
    }

    .footer-contact p {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .footer-contact i {
        background: rgba(255,255,255,0.1);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .footer-social {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 25px;
    }

    .footer-social a {
        color: white;
        background: rgba(255,255,255,0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .footer-social a::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #90EE90;
        transform: translateY(100%);
        transition: transform 0.3s ease;
        z-index: 0;
    }

    .footer-social a:hover::before {
        transform: translateY(0);
    }

    .footer-social a i {
        position: relative;
        z-index: 1;
    }

    .footer-social a:hover {
        transform: translateY(-5px);
    }

    .footer-social a:hover i {
        color: #004d00;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 40px;
        margin-top: 60px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .footer-bottom p {
        color: #e0e0e0;
    }

    @media (max-width: 1024px) {
        .footer-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
        }
        .footer-about {
            padding-left: 0;
        }
    }
</style>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section footer-about">
            <img src="assets/images/logo-white.png" alt="Logo" class="footer-logo">
            <p>جماعت اسلامی پاکستان ایک جامع تنظیم ہے جو معاشرے کی فلاح و بہبود کے لیے کام کرتی ہے۔ ہمارا مقصد اسلامی اقدار کو فروغ دینا اور معاشرے میں مثبت تبدیلی لانا ہے۔</p>
        </div>

        <div class="footer-section">
            <h3 class="footer-title">فوری روابط</h3>
            <ul class="footer-links">
                <li><a href="#"><i class="fas fa-chevron-left"></i>ہوم</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>تعارف</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>خدمات</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>میڈیا</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>رابطہ</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3 class="footer-title">اہم لنکس</h3>
            <ul class="footer-links">
                <li><a href="#"><i class="fas fa-chevron-left"></i>بیت المال</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>تعلیمی خدمات</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>فلاحی منصوبے</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>رضاکار پروگرام</a></li>
                <li><a href="#"><i class="fas fa-chevron-left"></i>شعبہ خواتین</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3 class="footer-title">رابطہ کریں</h3>
            <div class="footer-contact">
                <p><i class="fas fa-map-marker-alt"></i>منصورہ، ملتان روڈ، لاہور</p>
                <p><i class="fas fa-phone"></i>042-35330333</p>
                <p><i class="fas fa-envelope"></i>info@jamaat.org</p>
                <p><i class="fas fa-clock"></i>پیر تا جمعہ: صبح 9 بجے تا شام 5 بجے</p>
            </div>
            <div class="footer-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-telegram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2024 جماعت اسلامی پاکستان - جملہ حقوق محفوظ ہیں | ویب سائٹ ڈیزائن: ٹیم جماعت اسلامی</p>
    </div>
</footer>