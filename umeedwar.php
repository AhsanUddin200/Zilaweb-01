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
// Update the area query to count both karkunan and umeedwar
// Update the area query to count actual Umeedwar per area
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
                COUNT(u.id) as total_umeedwar,
                SUM(CASE WHEN LOWER(k.gender) = 'male' THEN 1 ELSE 0 END) as male_count,
                SUM(CASE WHEN LOWER(k.gender) = 'female' THEN 1 ELSE 0 END) as female_count
               FROM karkunan k
               LEFT JOIN umedwar u ON k.id = u.karkun_id
               WHERE k.address IS NOT NULL
               GROUP BY standardized_address
               HAVING standardized_address IS NOT NULL
               ORDER BY standardized_address";

// Then update the display section:
$area_result = mysqli_query($conn, $area_query);

// Get all umeedwar with karkun details
// After the total count query, add these lines
$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Get total pages
$total_pages_query = "SELECT COUNT(*) as count FROM umedwar";
$total_pages_result = mysqli_query($conn, $total_pages_query);
$total_pages = ceil(mysqli_fetch_assoc($total_pages_result)['count'] / $items_per_page);

// Modify the umeedwar query to include pagination
$umeedwar_query = "SELECT u.*, k.name, k.father_name, k.gender, k.address, k.cnic, k.education 
                   FROM umedwar u 
                   LEFT JOIN karkunan k ON u.karkun_id = k.id 
                   ORDER BY k.name ASC
                   LIMIT $items_per_page OFFSET $offset";
$umeedwar_result = mysqli_query($conn, $umeedwar_query);
// Monthly statistics
$monthly_stats_query = "SELECT DATE_FORMAT(application_date, '%Y-%m') as month,
                              COUNT(*) as total
                       FROM umedwar 
                       GROUP BY month 
                       ORDER BY month DESC 
                       LIMIT 6";
$monthly_stats = mysqli_query($conn, $monthly_stats_query);

// Gender statistics
$gender_stats_query = "SELECT k.gender, COUNT(*) as total 
                      FROM umedwar u
                      JOIN karkunan k ON u.karkun_id = k.id
                      GROUP BY k.gender";
$gender_stats = mysqli_query($conn, $gender_stats_query);
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

        .stats-dashboard {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .stats-dashboard h2 {
            color: #006600;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            height: 250px;
        }

        .stat-card h3 {
            color: #006600;
            margin-bottom: 10px;
            text-align: center;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .umeedwar-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            min-width: 0;
        }

        .umeedwar-info {
            margin: 8px 0;
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; gap: 10px;">
                <input type="text" class="search-box" placeholder="Search Umeedwar...">
                <a href="export_excel.php" class="action-button" style="background: #217346;">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="export_pdf.php" class="action-button" style="background: #ff0000;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <button onclick="window.print();" class="action-button" style="background: #666;">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <span style="color: #006600; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-users"></i> Total Umeedwar: <?php echo $total_count; ?>
            </span>
            <a href="add_umeedwar.php" class="action-button">
                <i class="fas fa-plus"></i> Add New Umeedwar
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <?php 
            if ($area_result) {
                while ($row = mysqli_fetch_assoc($area_result)) {
                    $total_area = $row['male_count'] + $row['female_count'];
                    echo '<div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;">
                            <div style="background: #e3f2fd; padding: 5px 10px; border-radius: 15px; display: inline-block; margin-bottom: 10px;">
                            <i class="fas fa-user-graduate"></i> ' . $total_area . ' Umeedwar
                        </div>
                        <div style="position: relative; text-align: center;">
                            <i class="fas fa-map-marker-alt" style="color: #006600; font-size: 24px; position: absolute; right: 20px;"></i>
                            <h3 style="color: #006600; margin: 10px 0;">' . htmlspecialchars($row['standardized_address']) . '</h3>
                            <div style="display: flex; justify-content: center; gap: 15px; margin-top: 10px;">
                                <span style="color: #006600;"><i class="fas fa-male"></i> ' . ($row['male_count'] ? $row['male_count'] : '0') . '</span>
                                <span style="color: #ff69b4;"><i class="fas fa-female"></i> ' . ($row['female_count'] ? $row['female_count'] : '0') . '</span>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>
        </div>

        <div class="stats-dashboard">
            <h2>Statistics Dashboard</h2>
            <div class="stats-grid">
                <!-- Monthly Applications Chart -->
                <div class="stat-card">
                    <h3>Monthly Applications</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>

                <!-- Gender Distribution Chart -->
                <div class="stat-card">
                    <h3>Gender Distribution</h3>
                    <canvas id="genderChart"></canvas>
                </div>

                <!-- Area Distribution Chart -->
                <div class="stat-card">
                    <h3>Area Distribution</h3>
                    <canvas id="areaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Remove this div and keep everything else -->
      

        <div class="umeedwar-grid">
            <?php while ($umeedwar = mysqli_fetch_assoc($umeedwar_result)): ?>
                <div class="umeedwar-card" style="background: <?php echo strtolower($umeedwar['gender']) === 'female' ? '#fff0f5' : 'white'; ?>">
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
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                   style="padding: 8px 12px; border-radius: 5px; text-decoration: none;
                          background: <?php echo $i === $current_page ? '#006600' : 'white'; ?>;
                          color: <?php echo $i === $current_page ? 'white' : '#006600'; ?>;
                          box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Search functionality
            document.querySelector('.search-box').addEventListener('input', function(e) {
                const search = e.target.value.toLowerCase();
                document.querySelectorAll('.umeedwar-card').forEach(card => {
                    const text = card.textContent.toLowerCase();
                    card.style.display = text.includes(search) ? '' : 'none';
                });
            });
    
            // Monthly Applications Chart
            new Chart(document.getElementById('monthlyChart'), {
                type: 'bar',
                data: {
                    labels: [<?php 
                        $labels = [];
                        $data = [];
                        mysqli_data_seek($monthly_stats, 0);
                        while($row = mysqli_fetch_assoc($monthly_stats)) {
                            $labels[] = "'" . date('M Y', strtotime($row['month'] . '-01')) . "'";
                            $data[] = $row['total'];
                        }
                        echo implode(',', array_reverse($labels));
                    ?>],
                    datasets: [{
                        label: 'Applications',
                        data: [<?php echo implode(',', array_reverse($data)); ?>],
                        backgroundColor: '#006600'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
    
            // Gender Distribution Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'pie',
                data: {
                    labels: [<?php 
                        $labels = [];
                        $data = [];
                        mysqli_data_seek($gender_stats, 0);
                        while($row = mysqli_fetch_assoc($gender_stats)) {
                            $labels[] = "'" . ucfirst($row['gender']) . "'";
                            $data[] = $row['total'];
                        }
                        echo implode(',', $labels);
                    ?>],
                    datasets: [{
                        data: [<?php echo implode(',', $data); ?>],
                        backgroundColor: ['#006600', '#ff69b4']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
    
            // Area Distribution Chart
            new Chart(document.getElementById('areaChart'), {
                type: 'doughnut',
                data: {
                    labels: [<?php 
                        $labels = [];
                        $data = [];
                        mysqli_data_seek($area_result, 0);
                        while($row = mysqli_fetch_assoc($area_result)) {
                            $labels[] = "'" . $row['standardized_address'] . "'";
                            $data[] = $row['total_umeedwar'];
                        }
                        echo implode(',', $labels);
                    ?>],
                    datasets: [{
                        data: [<?php echo implode(',', $data); ?>],
                        backgroundColor: [
                            '#006600', '#008800', '#00aa00', '#00cc00',
                            '#00ee00', '#00ff00', '#66ff66', '#99ff99'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        </script>
</body>
</html>
