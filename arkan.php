<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get total Arkan count
$total_count = 0;
$query = "SELECT COUNT(*) as total FROM arkan";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_count = $row['total'];
}

// Get area counts with gender split
$area_query = "SELECT 
                CASE 
                    WHEN LOWER(k.address) LIKE '%sohrab%goth%' THEN 'Sohrab Goth'
                    WHEN LOWER(k.address) LIKE '%lassi%goth%' THEN 'Lassi Goth'
                    WHEN LOWER(k.address) LIKE '%gulshan%maymar%' THEN 'Gulshan Maymar'
                    WHEN LOWER(k.address) LIKE '%jhanjar%goth%' THEN 'Jhanjar Goth'
                    WHEN LOWER(k.address) LIKE '%gadap%' THEN 'Gadap'
                    WHEN LOWER(k.address) LIKE '%bahria%' THEN 'Bahria'
                    WHEN LOWER(k.address) LIKE '%ahsan%abad%' THEN 'Ahsan Abad'
                END as standardized_address,
                SUM(CASE WHEN LOWER(k.gender) = 'male' THEN 1 ELSE 0 END) as male_count,
                SUM(CASE WHEN LOWER(k.gender) = 'female' THEN 1 ELSE 0 END) as female_count,
                COUNT(*) as total_count
               FROM arkan a 
               LEFT JOIN karkunan k ON a.karkun_id = k.id 
               WHERE k.address IS NOT NULL
               GROUP BY standardized_address
               HAVING standardized_address IS NOT NULL
               ORDER BY standardized_address";
$area_result = mysqli_query($conn, $area_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arkan Management - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 60px 0 0 0;
            background-color: #f0f2f5;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            color: white;
            padding: 0.3rem 2rem;  /* Reduced from 1rem to 0.3rem */
            position: fixed;
            top: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-logo {
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-button {
            background: #006600;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .add-button:hover {
            background: #008800;
        }

        .search-box {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }

        .arkan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .arkan-card {
            background: white;
            border-radius: 10px;
            padding: 15px;  /* Reduced padding */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .arkan-info {
            margin: 3px 0;  /* Reduced margin */
            color: #666;
            font-size: 13px;  /* Slightly smaller font */
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .arkan-card h3 {
            margin: 0 0 8px 0;  /* Reduced margin */
            color: #006600;
            font-size: 18px;  /* Slightly smaller heading */
        }

        .card-actions {
            margin-top: 10px;  /* Reduced margin */
            display: flex;
            gap: 10px;
        }

        .action-button {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn {
            background: #004d00;
            color: white;
        }

        .delete-btn {
            background: #cc0000;
            color: white;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .page-link {
            padding: 8px 12px;
            border: 1px solid #006600;
            border-radius: 3px;
            color: #006600;
            text-decoration: none;
        }

        .page-link.active {
            background: #006600;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div style="display: flex; align-items: center;">
            <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Logo" class="navbar-logo">
            <h1 style="margin: 0; font-size: 18px;">Digital Jamat - Arkan Management</h1>
        </div>
        <a href="admin.php" class="action-button" style="color: white;">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>

    <div class="container">
        <div class="header">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <input type="text" class="search-box" placeholder="Search Arkan...">
                    <span style="color: #006600;">
                        <i class="fas fa-users"></i> Total Arkan: <?php echo $total_count; ?>
                    </span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 13px;">
                    <?php 
                    if ($area_result) {
                        while ($row = mysqli_fetch_assoc($area_result)) {
                            echo '<span style="background: #f0f8f0; padding: 5px 10px; border-radius: 5px; border: 1px solid #dde8dd;">
                                <i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($row['standardized_address']) . 
                                ': <strong>' . $row['total_count'] . '</strong> ' .
                                '<span style="color: #006600;">(<i class="fas fa-male"></i> ' . $row['male_count'] . 
                                ' <i class="fas fa-female"></i> ' . $row['female_count'] . ')</span>
                            </span>';
                        }
                    }
                    ?>
                </div>
            </div>
            <a href="add_arkan.php" class="add-button">
                <i class="fas fa-plus"></i> Add New Arkan
            </a>
        </div>

        <div class="arkan-grid">
            <?php
            $query = "SELECT a.*, k.name, k.father_name, k.age, k.marital_status, k.address, k.cnic, 
                            k.education, k.source_of_income, k.responsibility, k.gender
                     FROM arkan a 
                     LEFT JOIN karkunan k ON a.karkun_id = k.id 
                     ORDER BY k.name ASC LIMIT 10";
            $result = mysqli_query($conn, $query);
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $isFemale = strtolower($row['gender']) === 'female';
                    $cardColor = $isFemale ? '#FF69B4' : '#006600';
                    $cardBg = $isFemale ? '#FFF0F5' : 'white';
                    
                    echo '<div class="arkan-card" style="background: ' . $cardBg . ';">
                        <h3 style="color: ' . $cardColor . ';">' . htmlspecialchars($row['name']) . '</h3>
                        <div class="arkan-info"><i class="fas fa-user"></i> S/O ' . htmlspecialchars($row['father_name']) . '</div>
                        <div class="arkan-info"><i class="fas fa-calendar"></i> Age: ' . htmlspecialchars($row['age']) . '</div>
                        <div class="arkan-info"><i class="fas fa-ring"></i> ' . htmlspecialchars($row['marital_status']) . '</div>
                        <div class="arkan-info"><i class="fas fa-id-card"></i> ' . htmlspecialchars($row['cnic']) . '</div>
                        <div class="arkan-info"><i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($row['address']) . '</div>
                        <div class="arkan-info"><i class="fas fa-graduation-cap"></i> ' . htmlspecialchars($row['education']) . '</div>
                        <div class="arkan-info"><i class="fas fa-briefcase"></i> ' . htmlspecialchars($row['source_of_income']) . '</div>
                        <div class="arkan-info"><i class="fas fa-tasks"></i> ' . htmlspecialchars($row['responsibility']) . '</div>
                        <div class="arkan-info"><i class="fas fa-venus-mars" style="color: ' . $cardColor . ';"></i> ' . htmlspecialchars($row['gender']) . '</div>
                        <div class="arkan-info"><i class="fas fa-calendar-alt"></i> Joined: ' . htmlspecialchars($row['joining_date']) . '</div>
                        <div class="card-actions">
                            <a href="edit_arkan.php?id=' . $row['id'] . '" class="action-button edit-btn" style="background: ' . $cardColor . ';">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_arkan.php?id=' . $row['id'] . '" class="action-button delete-btn" onclick="return confirm(\'Are you sure you want to delete this record?\')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>';
                }
            }
            ?>
        </div>

        <div class="pagination">
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <a href="#" class="page-link"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <script>
        // Search functionality
        document.querySelector('.search-box').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            document.querySelectorAll('.arkan-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>
</body>
</html>