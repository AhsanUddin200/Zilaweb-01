<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header("Location: karkundetail.php");
    exit();
}

// Update the query to check member status from different tables
$query = "SELECT k.*, 
    CASE 
        WHEN a.id IS NOT NULL THEN 'Arkan'
        WHEN u.id IS NOT NULL THEN 'Umedwar'
        ELSE 'Karkun'
    END as member_status
    FROM karkunan k
    LEFT JOIN arkan a ON k.id = a.karkun_id
    LEFT JOIN umedwar u ON k.id = u.karkun_id
    WHERE k.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$karkun = $result->fetch_assoc();

if (!$karkun) {
    header("Location: karkundetail.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Karkun - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Copy the basic styles from edit_karkun.php */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            padding: 8px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: white;
            margin: 0;
            font-size: 20px;
        }

        .container {
            max-width: 1000px;  /* Increased width */
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 20px;
        }

        .detail-group {
            margin-bottom: 0;  /* Reset margin */
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .detail-group:hover {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .detail-label {
            font-weight: 600;
            color: #006600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            padding: 8px 0;
            border-bottom: 2px solid #e9ecef;
        }

        /* Make certain groups full width */
        .detail-group.full-width {
            grid-column: 1 / -1;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>

    <div class="navbar">
        <h1><i class="fas fa-user"></i> Karkun Details</h1>
        <a href="karkundetail.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <!-- Add this after navbar -->
    <div class="profile-header" style="margin-top: 30px;">
        <div class="profile-cover"></div>
        <div class="profile-info">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="profile-details">
                <h1><?php echo htmlspecialchars($karkun['name']); ?></h1>
                <span class="member-badge" style="background: <?php 
                    echo $karkun['member_status'] === 'Arkan' ? '#0066cc' : 
                        ($karkun['member_status'] === 'Umedwar' ? '#9933cc' : '#006600'); 
                ?>">
                    <?php echo htmlspecialchars($karkun['member_status']); ?>
                </span>
                <p class="profile-meta">
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($karkun['area']); ?></span>
                    <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($karkun['mobile_number']); ?></span>
                </p>
            </div>
            <div class="profile-qr" id="qrcode"></div>
        </div>
    </div>

    <!-- Add these styles in the head section -->
    <style>
        .profile-header {
            max-width: 1400px;
            margin: 0 auto 40px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .profile-cover {
            height: 200px;
            background: linear-gradient(135deg, #006600 0%, #008800 100%);
        }

        .profile-info {
            padding: 30px;
            margin-top: -60px; /* Changed from -80px to -60px */
            display: flex;
            align-items: flex-end;
            gap: 30px;
        }

        .profile-details {
            flex: 1;
            padding-top: 20px; /* Added padding-top */
        }

        .profile-details h1 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 32px;
            font-weight: 700;
            text-shadow: 1px 1px 0 rgba(255,255,255,0.8); /* Added text shadow */
        }

        .profile-cover {
            height: 200px;
            background: linear-gradient(135deg, #006600 0%, #008800 100%);
            position: relative; /* Added position */
        }

        .profile-avatar {
            width: 160px;
            height: 160px;
            background: white;
            border-radius: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 6px solid white;
        }

        .profile-avatar i {
            font-size: 80px;
            color: #006600;
        }

        .profile-details {
            flex: 1;
        }

        .profile-details h1 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 32px;
            font-weight: 700;
        }

        .member-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            color: white;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .profile-meta {
            color: #666;
            display: flex;
            align-items: center;
            gap: 30px;
            margin: 0;
            font-size: 16px;
        }

        .profile-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-meta i {
            color: #006600;
        }

        .profile-qr {
            padding: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        @media (max-width: 768px) {
            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 20px;
            }

            .profile-meta {
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    </style>

    <!-- Add QR Code script before closing body tag -->
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: window.location.href,
            width: 128,
            height: 128,
            colorDark : "#006600",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

    <style>
        /* Add these action button styles */
        .action-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .action-btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-edit {
            background: #006600;
        }

        .btn-print {
            background: #555555;
        }

        .btn-delete {
            background: #dc3545;
        }

        .action-btn i {
            font-size: 16px;
        }
    </style>

    <!-- Then in the HTML, keep only the buttons -->
    <div class="container">
        <!-- Replace the existing print button with this dropdown -->
        <div class="action-bar">
            <a href="edit_karkun.php?id=<?php echo $karkun['id']; ?>" class="action-btn btn-edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <div class="print-dropdown">
                <button class="action-btn btn-print" onclick="togglePrintMenu()">
                    <i class="fas fa-print"></i> Print Options
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="print-menu" id="printMenu">
                    <a href="download_karkun.php?id=<?php echo $karkun['id']; ?>&template=detailed" class="print-option">
                        <i class="fas fa-file-alt"></i> Detailed Profile
                    </a>
                    <a href="download_karkun.php?id=<?php echo $karkun['id']; ?>&template=summary" class="print-option">
                        <i class="fas fa-file-text"></i> Summary View
                    </a>
                    <a href="generate_id_card.php?id=<?php echo $karkun['id']; ?>" class="print-option">
                        <i class="fas fa-id-card"></i> ID Card
                    </a>
                </div>
            </div>
            <a href="#" onclick="confirmDelete(<?php echo $karkun['id']; ?>)" class="action-btn btn-delete">
                <i class="fas fa-trash"></i> Delete
            </a>
        </div>

        <!-- Add these styles -->
        <style>
            .print-dropdown {
                position: relative;
                display: inline-block;
            }

            .print-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                min-width: 200px;
                z-index: 1000;
                margin-top: 5px;
                border: 1px solid #eee;
            }

            .print-option {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 12px 15px;
                color: #333;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .print-option:hover {
                background: #f8f9fa;
            }

            .print-option:not(:last-child) {
                border-bottom: 1px solid #eee;
            }

            .print-option i {
                color: #006600;
                width: 20px;
                text-align: center;
            }

            .btn-print i.fa-chevron-down {
                font-size: 12px;
                margin-left: 5px;
            }
        </style>

        <!-- Add this script -->
        <script>
            function togglePrintMenu() {
                const menu = document.getElementById('printMenu');
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.querySelector('.print-dropdown');
                const menu = document.getElementById('printMenu');
                
                if (!dropdown.contains(event.target)) {
                    menu.style.display = 'none';
                }
            });
        </script>
        <div class="details-grid">
            <div class="detail-group">
                <div class="detail-label">Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['name']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Father's Name</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['father_name']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Age</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['age']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Gender</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['gender']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Area</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['area']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Marital Status</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['marital_status']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Member Status</div>
                <div class="detail-value" style="color: <?php echo $status_color; ?>; font-weight: 500;">
                    <?php echo htmlspecialchars($karkun['member_status']); ?>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-label">CNIC</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['cnic']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Education</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['education']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Phone Number</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['mobile_number']); ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Responsibility</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['responsibility']); ?></div>
            </div>

            <div class="detail-group full-width">
                <div class="detail-label">Address</div>
                <div class="detail-value"><?php echo htmlspecialchars($karkun['address']); ?></div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this karkun?')) {
                window.location.href = 'delete_karkun.php?id=' + id;
            }
        }
    </script>

    <!-- Add this after the profile info section and before the container -->
    <div class="family-tree-section">
        <div class="section-header">
            <h2><i class="fas fa-sitemap"></i> Family Tree</h2>
        </div>
        <div class="family-tree">
            <div class="tree-node parent">
                <div class="member-card">
                    <i class="fas fa-user"></i>
                    <div class="member-info">
                        <h3><?php echo htmlspecialchars($karkun['father_name']); ?></h3>
                        <span><?php 
                            // Check if person is child or spouse
                            if ($karkun['marital_status'] === 'Married' && $karkun['gender'] === 'Female') {
                                echo 'Husband';
                            } else {
                                echo 'Father';
                            }
                        ?></span>
                    </div>
                </div>
            </div>
            <div class="spouse-connector"></div>
            <div class="tree-level">
                <div class="tree-node current">
                    <div class="member-card active">
                        <i class="fas <?php echo ($karkun['gender'] === 'Female') ? 'fa-user-alt' : 'fa-user'; ?>"></i>
                        <div class="member-info">
                            <h3><?php echo htmlspecialchars($karkun['name']); ?></h3>
                            <span><?php echo htmlspecialchars($karkun['member_status']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="related-members">
                <h3>Related Members</h3>
                <div class="member-links">
                    <?php
                    // Add this query to fetch related members (siblings, spouse, children)
                    $related_query = "SELECT k.id, k.name, k.father_name, 
                        CASE 
                            WHEN a.id IS NOT NULL THEN 'Arkan'
                            WHEN u.id IS NOT NULL THEN 'Umedwar'
                            ELSE 'Karkun'
                        END as status
                        FROM karkunan k
                        LEFT JOIN arkan a ON k.id = a.karkun_id
                        LEFT JOIN umedwar u ON k.id = u.karkun_id
                        WHERE k.father_name = ? AND k.id != ?";
                    
                    $stmt = $conn->prepare($related_query);
                    $stmt->bind_param("si", $karkun['father_name'], $karkun['id']);
                    $stmt->execute();
                    $related_result = $stmt->get_result();
                    
                    while($related = $related_result->fetch_assoc()): ?>
                        <a href="view_karkun.php?id=<?php echo $related['id']; ?>" class="member-link">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($related['name']); ?></span>
                            <small><?php echo htmlspecialchars($related['status']); ?></small>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .family-tree-section {
            max-width: 1400px;
            margin: 0 auto 40px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .section-header {
            margin-bottom: 30px;
        }

        .section-header h2 {
            color: #333;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .family-tree {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .tree-node {
            position: relative;
            text-align: center;
        }

        .tree-node::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            height: 20px;
            width: 2px;
            background: #006600;
        }

        .member-card {
            padding: 15px 25px;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .member-card.active {
            background: #e8f5e9;
            border-color: #006600;
        }

        .member-card i {
            font-size: 24px;
            color: #006600;
        }

        .member-info h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .member-info span {
            font-size: 14px;
            color: #666;
        }

        .related-members {
            width: 100%;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .related-members h3 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 18px;
        }

        .member-links {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .member-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .member-link:hover {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .member-link i {
            font-size: 20px;
            color: #006600;
        }

        .member-link small {
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .family-tree-section {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</body>
</html>