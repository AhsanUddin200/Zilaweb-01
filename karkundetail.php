<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get search parameters
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$search_area = isset($_GET['search_area']) ? $_GET['search_area'] : '';

// Initialize query variables first
$query = "SELECT * FROM karkunan WHERE 1=1";
$params = [];
$types = "";

// Get all areas for dropdown
$areas_query = "SELECT DISTINCT REPLACE(LOWER(area), ' ', '_') as area FROM karkunan";
$areas_result = $conn->query($areas_query);
$areas = [];
while ($row = $areas_result->fetch_assoc()) {
    $areas[] = $row['area'];
}

// Get area with maximum karkunan
$max_area_query = "SELECT 
    CASE 
        WHEN area LIKE '%sohrab%goth%' THEN 'sohrab_goth'
        WHEN area LIKE '%Sohrab%Goth%' THEN 'sohrab_goth'
        ELSE LOWER(REPLACE(area, ' ', '_'))
    END as standardized_area,
    COUNT(*) as count 
    FROM karkunan 
    GROUP BY standardized_area 
    ORDER BY count DESC 
    LIMIT 1";
$max_area_result = $conn->query($max_area_query);
$max_area = $max_area_result->fetch_assoc();

// Add search conditions
if (!empty($search_area)) {
    $query .= " AND (REPLACE(LOWER(area), ' ', '_') = LOWER(?) OR area = ?)";
    $params[] = $search_area;
    $params[] = $search_area;
    $types .= "ss";
}

if (!empty($search_name)) {
    $query .= " AND (name LIKE ? OR father_name LIKE ?)";
    $search_term = "%$search_name%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss";
}

if (!empty($search_area)) {
    $query .= " AND area = ?";
    $params[] = $search_area;
    $types .= "s";
}

$query .= " ORDER BY created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$karkunan = $result->fetch_all(MYSQLI_ASSOC);

// Get total count
$total_count = count($karkunan);

// Get gender-wise counts - Modified to respect search conditions
$base_condition = "1=1";
if (!empty($search_area)) {
    $base_condition .= " AND (REPLACE(LOWER(area), ' ', '_') = LOWER('$search_area') OR area = '$search_area')";
}
if (!empty($search_name)) {
    $search_term = "%$search_name%";
    $base_condition .= " AND (name LIKE '$search_term' OR father_name LIKE '$search_term')";
}

$male_count_query = "SELECT COUNT(*) as count FROM karkunan WHERE $base_condition AND gender = 'Male'";
$female_count_query = "SELECT COUNT(*) as count FROM karkunan WHERE $base_condition AND gender = 'Female'";

$male_result = $conn->query($male_count_query);
$female_result = $conn->query($female_count_query);

$male_count = $male_result->fetch_assoc()['count'];
$female_count = $female_result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Karkunan Details - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            padding: 12px 40px;
            box-shadow: 0 4px 20px rgba(0,102,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 500;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        /* Filters Styling */
        .filters {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .filters form {
            display: flex;
            gap: 20px;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group label {
            display: block;
            margin-bottom: 10px;
            color: #444;
            font-weight: 500;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-btn {
            background: #006600;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            height: 45px;
        }

        .search-btn:hover {
            background: #008800;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,102,0,0.2);
        }

        /* Stats Cards Styling */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,102,0,0.15);
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #006600;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .stat-card h3 i {
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover h3 i {
            transform: scale(1.2) rotate(10deg);
            color: #008800;
        }

        .stat-card p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #444;
            position: relative;
            z-index: 1;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(0,102,0,0.05) 0%, rgba(0,102,0,0) 70%);
            transition: all 0.3s ease;
            opacity: 0;
            border-radius: 50%;
        }

        .stat-card:hover::after {
            opacity: 1;
            transform: scale(2);
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #006600;
            font-size: 16px;
            font-weight: 500;
        }

        .stat-card p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #444;
        }

        /* Add to existing media queries */
        @media (max-width: 768px) {
            .filters form {
                flex-direction: column;
                gap: 15px;
            }

            .stats {
                flex-direction: column;
            }

            .search-btn {
                width: 100%;
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        th {
            background: #006600;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9f8;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <h1>All Karkunan Details</h1>
        <div class="navbar-right">
            <a href="karkunan.php" class="back-btn">Back to Areas</a>
        </div>
    </div>

    <div class="container">
        <!-- Search Filters -->
        <div class="filters">
            <form method="GET">
                <div class="filter-group">
                    <label>Search by Name/Father's Name</label>
                    <input type="text" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="Enter name...">
                </div>
                <div class="filter-group">
                    <label>Filter by Area</label>
                    <select name="search_area">
                        <option value="">All Areas</option>
                        <?php foreach ($areas as $area): ?>
                        <option value="<?php echo htmlspecialchars($area); ?>" <?php echo $search_area === $area ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($area); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <h3><i class="fas fa-users"></i> Total Karkunan</h3>
                <p><?php echo $total_count; ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-male"></i> Male Karkunan</h3>
                <p style="color: #006600;"><?php echo $male_count; ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-female"></i> Female Karkunan</h3>
                <p style="color: #FF69B4;"><?php echo $female_count; ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-map-marker-alt"></i> Most Active Area</h3>
                <p><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $max_area['standardized_area']))) . 
                    ' (' . $max_area['count'] . ' karkunan)'; ?></p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-user-check"></i>  Status Karkun Member </h3>
                <?php
                // First, get IDs of members who are in arkan or umedwar tables
                $exclude_query = "SELECT karkun_id FROM arkan UNION SELECT karkun_id FROM umedwar";
                $exclude_result = $conn->query($exclude_query);
                $exclude_ids = [];
                while($row = $exclude_result->fetch_assoc()) {
                    $exclude_ids[] = $row['karkun_id'];
                }
                
                // Now count karkunan who are not in those tables
                $exclude_condition = empty($exclude_ids) ? "" : " AND id NOT IN (" . implode(',', $exclude_ids) . ")";
                $karkun_query = "SELECT COUNT(*) as count FROM karkunan WHERE $base_condition" . $exclude_condition;
                $karkun_result = $conn->query($karkun_query);
                $karkun_count = $karkun_result->fetch_assoc()['count'];
                ?>
                <p style="color: #006600;"><?php echo $karkun_count; ?></p>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Father's Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Area</th>
                    <th>Marital Status</th>
                    <th>CNIC</th>
                    <th>Education</th>
                    <th>Mobile</th>  <!-- Added mobile column -->
                    <th>Responsibility</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($karkunan as $index => $karkun): ?>
                <tr style="<?php echo $karkun['gender'] === 'Female' ? 'background-color: #FFF0F5;' : ''; ?>">
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($karkun['name']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['father_name']); ?></td>
                    <td style="color: <?php echo $karkun['gender'] === 'Female' ? '#FF69B4' : 'inherit'; ?>">
                        <?php echo htmlspecialchars($karkun['gender']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($karkun['age']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['area']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['marital_status']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['cnic']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['education']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['mobile_number']); ?></td>  <!-- Added mobile number -->
                    <td><?php echo htmlspecialchars($karkun['responsibility']); ?></td>
                    <style>
                        .action-buttons {
                            display: flex;
                            gap: 4px;
                            white-space: nowrap;
                        }

                        .action-btn {
                            padding: 5px 10px;
                            border-radius: 4px;
                            text-decoration: none;
                            font-size: 12px;
                            display: inline-flex;
                            align-items: center;
                            gap: 4px;
                            transition: all 0.2s ease;
                            color: white;
                        }

                        .action-btn:hover {
                            transform: translateY(-1px);
                            opacity: 0.9;
                        }

                        .view-btn { background: #0066cc; }
                        .edit-btn { background: #006600; }
                        .print-btn { background: #555555; }
                        .delete-btn { background: #dc3545; }

                        td .action-buttons {
                            min-width: auto;
                        }
                    </style>
                
                    <td>
                        <div class="action-buttons">
                            <a href="view_karkun.php?id=<?php echo $karkun['id']; ?>" class="action-btn view-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="karkunfamily.php?name=<?php echo urlencode($karkun['father_name']); ?>&address=<?php echo urlencode($karkun['address']); ?>" class="action-btn" style="background: #9933CC;" title="Family View">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="edit_karkun.php?id=<?php echo $karkun['id']; ?>" class="action-btn edit-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="download_karkun.php?id=<?php echo $karkun['id']; ?>" class="action-btn print-btn" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="#" onclick="deleteKarkun(<?php echo $karkun['id']; ?>)" class="action-btn delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<script>
        function deleteKarkun(id) {
            if (confirm('Are you sure you want to delete this karkun?')) {
                window.location.href = 'delete_karkun.php?id=' + id;
            }
        }
    </script>

    <!-- After the existing stats div -->
        <style>
            .enhanced-stats {
                margin: 8px 0;
                padding: 8px;
                border-radius: 6px;
            }

            .stat-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 6px;
            }

            .stat-box {
                background: white;
                padding: 6px;
                border-radius: 4px;
                border: 1px solid #e8f5e8;
            }

            .stat-box h3 {
                color: #006600;
                margin: 0 0 4px 0;
                font-size: 11px;
                gap: 4px;
                padding-bottom: 4px;
            }

            .stat-box h3 i {
                padding: 2px;
                font-size: 10px;
            }

            .stat-item {
                gap: 3px;
                margin-bottom: 2px;
                padding: 1px;
                font-size: 10px;
            }

            .stat-item span:first-child {
                min-width: 50px;
                font-size: 10px;
            }

            .progress-bar {
                height: 3px;
            }

            .count {
                min-width: 14px;
                padding: 0px 2px;
                font-size: 9px;
            }

            @media (max-width: 768px) {
                .stat-row {
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                }
                .stat-box {
                    padding: 4px;
                }
            }
        </style>
        <div class="enhanced-stats">
            <div class="stat-row">
                <!-- Education Stats -->
                <div class="stat-box">
                    <h3><i class="fas fa-graduation-cap"></i> Education Distribution</h3>
                    <?php
                    $edu_query = "SELECT education, COUNT(*) as count FROM karkunan WHERE $base_condition GROUP BY education ORDER BY count DESC";
                    $edu_result = $conn->query($edu_query);
                    while($row = $edu_result->fetch_assoc()):
                        $percentage = round(($row['count'] / $total_count) * 100);
                    ?>
                    <div class="stat-item">
                        <span><?php echo htmlspecialchars($row['education'] ?: 'Not Specified'); ?></span>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <span class="count"><?php echo $row['count']; ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Age Distribution -->
                <div class="stat-box">
                    <h3><i class="fas fa-users"></i> Age Groups</h3>
                    <?php
                    $age_ranges = [
                        '18-25' => "age BETWEEN 18 AND 25",
                        '26-35' => "age BETWEEN 26 AND 35",
                        '36-50' => "age BETWEEN 36 AND 50",
                        '50+' => "age > 50"
                    ];
                    foreach($age_ranges as $label => $condition):
                        $age_query = "SELECT COUNT(*) as count FROM karkunan WHERE $base_condition AND $condition";
                        $age_result = $conn->query($age_query);
                        $count = $age_result->fetch_assoc()['count'];
                        $percentage = round(($count / $total_count) * 100);
                    ?>
                    <div class="stat-item">
                        <span><?php echo $label; ?></span>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <span class="count"><?php echo $count; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="stat-row">
                <!-- Responsibility Distribution -->
                <div class="stat-box">
                    <h3><i class="fas fa-tasks"></i> Responsibilities</h3>
                    <?php
                    $resp_query = "SELECT responsibility, COUNT(*) as count FROM karkunan WHERE $base_condition GROUP BY responsibility ORDER BY count DESC";
                    $resp_result = $conn->query($resp_query);
                    while($row = $resp_result->fetch_assoc()):
                        $percentage = round(($row['count'] / $total_count) * 100);
                    ?>
                    <div class="stat-item">
                        <span><?php echo htmlspecialchars($row['responsibility'] ?: 'Not Assigned'); ?></span>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <span class="count"><?php echo $row['count']; ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Monthly Registration Trends -->
                <div class="stat-box">
                    <h3><i class="fas fa-chart-line"></i> Monthly Registrations</h3>
                    <?php
                    $months_query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                               FROM karkunan WHERE $base_condition 
                               GROUP BY month ORDER BY month DESC LIMIT 6";
                    $months_result = $conn->query($months_query);
                    while($row = $months_result->fetch_assoc()):
                        $percentage = round(($row['count'] / $total_count) * 100);
                    ?>
                    <div class="stat-item">
                        <span><?php echo date('M Y', strtotime($row['month'])); ?></span>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <span class="count"><?php echo $row['count']; ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <style>
            .enhanced-stats {
                margin: 30px 0;
                background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            }

            .stat-row {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
                gap: 30px;
                margin-bottom: 30px;
            }

            .stat-box {
                background: white;
                padding: 25px;
                border-radius: 18px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.05);
                transition: all 0.4s ease;
                border: 1px solid rgba(0,102,0,0.1);
            }

            .stat-box:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 35px rgba(0,102,0,0.1);
                border-color: #006600;
            }

            .stat-box h3 {
                color: #006600;
                margin: 0 0 25px 0;
                font-size: 22px;
                display: flex;
                align-items: center;
                gap: 15px;
                padding-bottom: 15px;
                border-bottom: 2px solid #e8f5e8;
                position: relative;
            }

            .stat-box h3::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 60px;
                height: 2px;
                background: #006600;
            }

            .stat-box h3 i {
                background: linear-gradient(135deg, #e8f5e8 0%, #d1e7d1 100%);
                padding: 12px;
                border-radius: 12px;
                color: #006600;
                font-size: 20px;
                box-shadow: 0 4px 15px rgba(0,102,0,0.1);
            }

            .stat-item {
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 18px;
                padding: 12px 15px;
                border-radius: 10px;
                transition: all 0.3s ease;
                border: 1px solid transparent;
            }

            .stat-item:hover {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                border-color: #e8f5e8;
                transform: translateX(5px);
            }

            .stat-item span:first-child {
                min-width: 140px;
                font-weight: 500;
                color: #333;
                font-size: 15px;
            }

            .progress-bar {
                flex: 1;
                height: 12px;
                background: #f0f4f1;
                border-radius: 6px;
                overflow: hidden;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
                position: relative;
            }

            .progress {
                height: 100%;
                background: linear-gradient(90deg, #006600 0%, #008800 100%);
                border-radius: 6px;
                transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }

            .progress::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(90deg, 
                    rgba(255,255,255,0.1) 0%, 
                    rgba(255,255,255,0.2) 50%, 
                    rgba(255,255,255,0.1) 100%);
            }

            .count {
                min-width: 60px;
                text-align: center;
                font-weight: 600;
                color: #006600;
                background: linear-gradient(135deg, #e8f5e8 0%, #d1e7d1 100%);
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 14px;
                box-shadow: 0 2px 8px rgba(0,102,0,0.1);
            }

            @media (max-width: 768px) {
                .stat-row {
                    grid-template-columns: 1fr;
                }
                .enhanced-stats {
                    padding: 20px;
                    margin: 20px 0;
                }
                .stat-box {
                    padding: 20px;
                }
                .stat-item {
                    padding: 10px;
                    gap: 12px;
                }
                .stat-item span:first-child {
                    min-width: 110px;
                    font-size: 14px;
                }
                .count {
                    min-width: 50px;
                    font-size: 13px;
                }
            }

            /* Animation for progress bars */
            @keyframes progressAnimation {
                0% { opacity: 0; transform: scaleX(0); }
                100% { opacity: 1; transform: scaleX(1); }
            }

            .progress {
                animation: progressAnimation 1s ease-out forwards;
                transform-origin: left;
            }
        </style>

        <!-- Data Visualization Section -->
        <div class="visualization-section">
            <div class="chart-grid">
                <div class="chart-card">
                    <h3><i class="fas fa-chart-bar"></i> Age Distribution</h3>
                    <canvas id="ageChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-chart-pie"></i> Education Levels</h3>
                    <canvas id="educationChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Area Distribution</h3>
                    <canvas id="areaChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-venus-mars"></i> Gender Ratio</h3>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <style>
            .visualization-section {
                margin: 20px auto;
                max-width: 1200px;
                padding: 0 15px;
            }

            .chart-grid {
                display: flex;
                gap: 15px;
                justify-content: center;
                overflow-x: auto;
                padding: 10px 0;
            }

            .chart-card {
                background: white;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                transition: all 0.3s ease;
                min-width: 250px;
                width: 250px;
                height: 250px;
                flex-shrink: 0;
            }

            .chart-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 4px 12px rgba(0,102,0,0.12);
            }

            .chart-card h3 {
                color: #006600;
                margin: 0 0 10px 0;
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .chart-card h3 i {
                background: #e8f5e8;
                padding: 6px;
                border-radius: 6px;
                font-size: 12px;
            }

            @media (max-width: 768px) {
                .chart-grid {
                    flex-wrap: wrap;
                    justify-content: center;
                }
                
                .chart-card {
                    width: calc(50% - 10px);
                    min-width: 200px;
                }
            }
        </style>

        <!-- Add Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Age Distribution Chart
            const ageData = {
                labels: ['18-25', '26-35', '36-50', '50+'],
                datasets: [{
                    label: 'Age Groups',
                    data: [
                        <?php
                        $age_ranges = [
                            "age BETWEEN 18 AND 25",
                            "age BETWEEN 26 AND 35",
                            "age BETWEEN 36 AND 50",
                            "age > 50"
                        ];
                        foreach($age_ranges as $range) {
                            $query = "SELECT COUNT(*) as count FROM karkunan WHERE $base_condition AND $range";
                            $result = $conn->query($query);
                            echo $result->fetch_assoc()['count'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: ['#006600', '#008800', '#00aa00', '#00cc00'],
                }]
            };

            new Chart('ageChart', {
                type: 'bar',
                data: ageData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: false }
                    }
                }
            });

            // Education Level Chart
            const eduData = {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ['#006600', '#008800', '#00aa00', '#00cc00', '#00ee00']
                }]
            };

            <?php
            $edu_query = "SELECT education, COUNT(*) as count FROM karkunan WHERE $base_condition GROUP BY education ORDER BY count DESC LIMIT 5";
            $edu_result = $conn->query($edu_query);
            while($row = $edu_result->fetch_assoc()) {
                echo "eduData.labels.push('" . addslashes($row['education']) . "');";
                echo "eduData.datasets[0].data.push(" . $row['count'] . ");";
            }
            ?>

            new Chart('educationChart', {
                type: 'doughnut',
                data: eduData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });

            // Area Distribution Chart
            const areaData = {
                labels: [],
                datasets: [{
                    label: 'Karkunan per Area',
                    data: [],
                    backgroundColor: '#006600'
                }]
            };

            <?php
            $area_query = "SELECT area, COUNT(*) as count FROM karkunan WHERE $base_condition GROUP BY area ORDER BY count DESC LIMIT 8";
            $area_result = $conn->query($area_query);
            while($row = $area_result->fetch_assoc()) {
                echo "areaData.labels.push('" . addslashes($row['area']) . "');";
                echo "areaData.datasets[0].data.push(" . $row['count'] . ");";
            }
            ?>

            new Chart('areaChart', {
                type: 'bar',
                data: areaData,
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Gender Ratio Chart
            const genderData = {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [<?php echo $male_count; ?>, <?php echo $female_count; ?>],
                    backgroundColor: ['#006600', '#FF69B4']
                }]
            };

            new Chart('genderChart', {
                type: 'pie',
                data: genderData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        </script>
</body>
</html>