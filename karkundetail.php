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

// Get all areas for dropdown
$areas_query = "SELECT DISTINCT area FROM karkunan";
$areas_result = $conn->query($areas_query);
$areas = [];
while ($row = $areas_result->fetch_assoc()) {
    $areas[] = $row['area'];
}

// Get area with maximum karkunan
$max_area_query = "SELECT area, COUNT(*) as count FROM karkunan GROUP BY area ORDER BY count DESC LIMIT 1";
$max_area_result = $conn->query($max_area_query);
$max_area = $max_area_result->fetch_assoc();

// Build the query with filters
$query = "SELECT * FROM karkunan WHERE 1=1";
$params = [];
$types = "";

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
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            flex: 1;
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
                <h3>Total Karkunan</h3>
                <p><?php echo $total_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Most Active Area</h3>
                <p><?php echo htmlspecialchars($max_area['area']) . ' (' . $max_area['count'] . ' karkunan)'; ?></p>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Father's Name</th>
                    <th>Age</th>
                    <th>Area</th>
                    <th>Marital Status</th>
                    <th>CNIC</th>
                    <th>Education</th>
                    <th>Responsibility</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($karkunan as $index => $karkun): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($karkun['name']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['father_name']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['age']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['area']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['marital_status']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['cnic']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['education']); ?></td>
                    <td><?php echo htmlspecialchars($karkun['responsibility']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>