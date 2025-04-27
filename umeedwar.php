<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get total Umeedwar count
$total_count = 0;
$query = "SELECT COUNT(*) as total FROM umedwar";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_count = $row['total'];
}

// Get area counts with gender split
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
               FROM umedwar u 
               LEFT JOIN karkunan k ON u.karkun_id = k.id 
               WHERE k.address IS NOT NULL
               GROUP BY standardized_address
               HAVING standardized_address IS NOT NULL
               ORDER BY standardized_address";
$area_result = mysqli_query($conn, $area_query);

// Get all umeedwar with karkun details
$umeedwar_query = "SELECT u.*, k.name, k.father_name, k.gender, k.address, k.cnic, k.education 
                   FROM umedwar u 
                   LEFT JOIN karkunan k ON u.karkun_id = k.id 
                   ORDER BY k.name ASC";
$umeedwar_result = mysqli_query($conn, $umeedwar_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umeedwar Management - Digital Jamat</title>
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
            padding: 0.3rem 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .umeedwar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .umeedwar-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .umeedwar-info {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .action-button {
            background: #006600;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .action-button:hover {
            background: #008800;
        }

        .search-box {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Logo" style="width: 24px; height: 24px;">
            <h1 style="margin: 0; font-size: 18px;">Digital Jamat - Umeedwar Management</h1>
        </div>
        <a href="admin.php" class="back-btn" style="color: white; text-decoration: none;">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>

    <div class="container">
        <div class="header">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <input type="text" class="search-box" placeholder="Search Umeedwar...">
                    <span style="color: #006600; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-users"></i> Total Umeedwar: <strong><?php echo $total_count; ?></strong>
                    </span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 13px;">
                    <?php 
                    if ($area_result) {
                        while ($row = mysqli_fetch_assoc($area_result)) {
                            echo '<span style="background: #f0f8f0; padding: 5px 10px; border-radius: 5px; border: 1px solid #dde8dd;">
                                <i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($row['standardized_address']) . 
                                ': ' . $row['total_count'] . ' (<i class="fas fa-male" style="color: #006600;"></i> ' . 
                                $row['male_count'] . ' <i class="fas fa-female" style="color: #006600;"></i> ' . 
                                $row['female_count'] . ')
                            </span>';
                        }
                    }
                    ?>
                </div>
            </div>
            <a href="add_umeedwar.php" class="action-button" style="background: #006600; padding: 8px 15px;">
                <i class="fas fa-plus"></i> Add New Umeedwar
            </a>
        </div>

        <!-- Remove the old stats-grid section since we've moved it to the header -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Umeedwar</h3>
                <p style="font-size: 24px; margin: 10px 0;"><?php echo $total_count; ?></p>
            </div>
            <?php while ($area = mysqli_fetch_assoc($area_result)): ?>
                <div class="stat-card">
                    <h3><?php echo htmlspecialchars($area['standardized_address']); ?></h3>
                    <p>Male: <?php echo $area['male_count']; ?></p>
                    <p>Female: <?php echo $area['female_count']; ?></p>
                    <p>Total: <?php echo $area['total_count']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="umeedwar-grid">
            <?php while ($umeedwar = mysqli_fetch_assoc($umeedwar_result)): ?>
                <div class="umeedwar-card">
                    <h3 style="margin: 0 0 10px 0; color: #006600;">
                        <?php echo htmlspecialchars($umeedwar['name']); ?>
                    </h3>
                    <p class="umeedwar-info">
                        <i class="fas fa-user"></i> 
                        S/O <?php echo htmlspecialchars($umeedwar['father_name']); ?>
                    </p>
                    <p class="umeedwar-info">
                        <i class="fas fa-venus-mars"></i> 
                        <?php echo htmlspecialchars($umeedwar['gender']); ?>
                    </p>
                    <p class="umeedwar-info">
                        <i class="fas fa-map-marker-alt"></i> 
                        <?php echo htmlspecialchars($umeedwar['address']); ?>
                    </p>
                    <p class="umeedwar-info">
                        <i class="fas fa-id-card"></i> 
                        <?php echo htmlspecialchars($umeedwar['cnic']); ?>
                    </p>
                    <p class="umeedwar-info">
                        <i class="fas fa-graduation-cap"></i> 
                        <?php echo htmlspecialchars($umeedwar['education']); ?>
                    </p>
                    <p class="umeedwar-info">
                        <i class="fas fa-calendar"></i> 
                        Application Date: <?php echo htmlspecialchars($umeedwar['application_date']); ?>
                    </p>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <a href="edit_umeedwar.php?id=<?php echo $umeedwar['id']; ?>" class="action-button">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete_umeedwar.php?id=<?php echo $umeedwar['id']; ?>" 
                           class="action-button" style="background: #cc0000;"
                           onclick="return confirm('Are you sure you want to delete this Umeedwar?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        // Search functionality
        document.querySelector('.search-box').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            document.querySelectorAll('.umeedwar-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(search) ? '' : 'none';
            });
        });
    </script>
</body>
</html>