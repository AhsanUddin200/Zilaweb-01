<nav class="navbar">
    <div class="logo-section">
        <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Digital Jamat">
        <h1>Digital Jamat</h1>
    </div>
    <div class="nav-menu">
        <a href="dashboard.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>ہوم</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-bullhorn"></i>
            <span>دستور</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-file-alt"></i>
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
        <a href="taaruf.php" class="nav-item">
            <i class="fas fa-kaaba"></i>
            <span>تعارف</span>
        </a>
    </div>
</nav>

<style>
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
        gap: 20px;
        align-items: center;
    }
    .nav-item {
        color: white;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
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

    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            padding: 15px;
        }
        .nav-menu {
            margin-top: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .nav-item {
            min-width: 80px;
        }
    }
</style>