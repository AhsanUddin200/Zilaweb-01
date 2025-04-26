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
            padding: 1rem 2rem;
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
            height: 40px;
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
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .arkan-card h3 {
            margin: 0 0 10px 0;
            color: #006600;
        }

        .arkan-info {
            margin: 5px 0;
            color: #666;
        }

        .card-actions {
            margin-top: 15px;
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
            <h1 style="margin: 0;">Digital Jamat - Arkan Management</h1>
        </div>
        <a href="admin.php" class="action-button" style="color: white;">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>

    <div class="container">
        <div class="header">
            <input type="text" class="search-box" placeholder="Search Arkan...">
            <a href="add_arkan.php" class="add-button">
                <i class="fas fa-plus"></i> Add New Arkan
            </a>
        </div>

        <div class="arkan-grid">
            <?php
            // Simplified query without ORDER BY
            $query = "SELECT * FROM arkan LIMIT 10";
            $result = mysqli_query($conn, $query);
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="arkan-card">';
                    // Debug output to see available columns
                    foreach ($row as $key => $value) {
                        echo '<div class="arkan-info"><strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value) . '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo "Error: " . mysqli_error($conn);
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