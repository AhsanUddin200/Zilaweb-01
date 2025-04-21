<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$father_name = isset($_GET['name']) ? $_GET['name'] : '';
$address = isset($_GET['address']) ? $_GET['address'] : '';

// Get all family members by address and relationships
$query = "SELECT DISTINCT k.* FROM karkunan k 
         WHERE k.address = ? 
         OR k.name IN (
             SELECT DISTINCT 
                 CASE 
                     WHEN k2.gender = 'Male' THEN k2.name 
                     WHEN k2.gender = 'Female' AND k2.marital_status = 'Married' THEN k2.father_name
                 END
             FROM karkunan k2 
             WHERE k2.address = ?
         )
         OR k.father_name IN (
             SELECT DISTINCT name 
             FROM karkunan 
             WHERE address = ?
         )
         OR k.name IN (
             SELECT DISTINCT father_name 
             FROM karkunan 
             WHERE address = ?
         )";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $address, $address, $address, $address);
$stmt->execute();
$result = $stmt->get_result();
$family_members = $result->fetch_all(MYSQLI_ASSOC);

// Find the family head (prioritize married male)
$family_head = null;
foreach ($family_members as $member) {
    if ($member['gender'] === 'Male' && $member['marital_status'] === 'Married') {
        foreach ($family_members as $potential_spouse) {
            if ($potential_spouse['gender'] === 'Female' 
                && $potential_spouse['marital_status'] === 'Married'
                && $potential_spouse['father_name'] === $member['name']) {
                $family_head = $member;
                break 2;
            }
        }
    }
}

// If head not found by name, find by relationships
if (!$family_head) {
    foreach ($family_members as $member) {
        foreach ($family_members as $potential_child) {
            if ($member['name'] === $potential_child['father_name'] && $member['gender'] === 'Male') {
                $family_head = $member;
                break 2;
            }
        }
    }
}

// If still no head found, take oldest male
if (!$family_head) {
    foreach ($family_members as $member) {
        if ($member['gender'] === 'Male' && (!$family_head || $member['age'] > $family_head['age'])) {
            $family_head = $member;
        }
    }
}

// Sort family members
usort($family_members, function($a, $b) use ($family_head) {
    if ($a === $family_head) return -1;
    if ($b === $family_head) return 1;
    if ($a['gender'] !== $b['gender']) return $a['gender'] === 'Male' ? -1 : 1;
    return $b['age'] - $a['age'];
});

// Remove this duplicate query
// $query = "SELECT * FROM karkunan WHERE father_name = ? OR address = ? ORDER BY gender DESC, age DESC";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("ss", $family_head_name, $address);
// $stmt->execute();
// $result = $stmt->get_result();
// $family_members = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Members - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Copy the same styles from karkundetail.php */
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .family-info {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        th {
            background: #006600;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: opacity 0.3s ease;
        }

        /* Add hover effects for specific columns */
        tr td:not(:hover) {
            opacity: 0.6;
        }

        tr:hover td {
            opacity: 1;
        }

        tr td:hover {
            opacity: 1;
            background-color: rgba(0, 102, 0, 0.05);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9f8;
        }

        .female-row {
            background-color: #FFF0F5;
        }

        /* Add new styles for action buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            color: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .print-btn {
            background-color: #555555;
        }

        .download-btn {
            background-color: #006600;
        }

        @media print {
            .action-buttons, .navbar {
                display: none;
            }
            body {
                background: none;
            }
            .container {
                padding: 0;
            }
            .family-info, table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Family Members</h1>
        <div class="navbar-right">
            <a href="karkundetail.php" class="back-btn">Back to All Karkunan</a>
        </div>
    </div>

    <div class="container">
        <div class="family-info">
            <h2>Family Details</h2>
            <div class="head-info">
                <h3>Family Head</h3>
                <p><strong>Name:</strong> <?php echo $family_head ? htmlspecialchars($family_head['name']) : 'N/A'; ?></p>
                <p><strong>Age:</strong> <?php echo $family_head ? htmlspecialchars($family_head['age']) : 'N/A'; ?></p>
                <p><strong>Responsibility:</strong> <?php echo $family_head ? htmlspecialchars($family_head['responsibility']) : 'N/A'; ?></p>
            </div>
            <div class="family-stats">
                <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                <p><strong>Total Family Members:</strong> <?php echo count($family_members); ?></p>
                <p><strong>Area:</strong> <?php echo $family_head ? htmlspecialchars($family_head['area']) : ($family_members ? htmlspecialchars($family_members[0]['area']) : 'N/A'); ?></p>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <?php
            // Calculate statistics
            $total_members = count($family_members);
            $total_age = 0;
            $education_counts = [];
            $male_count = 0;
            $employed_count = 0;

            foreach ($family_members as $member) {
                // Age calculation
                $total_age += $member['age'];
                
                // Education distribution
                $edu = !empty($member['education']) ? $member['education'] : 'Not Specified';
                if ($edu === 'no' || $edu === 'No' || $edu === 'NO') {
                    $edu = 'No Education';
                }
                $education_counts[$edu] = ($education_counts[$edu] ?? 0) + 1;
                
                // Gender count
                if ($member['gender'] === 'Male') {
                    $male_count++;
                }
                
                // Employment count based on source_of_income
                if (!empty($member['source_of_income']) && 
                    $member['source_of_income'] !== 'No' && 
                    $member['source_of_income'] !== 'Housewife' && 
                    $member['source_of_income'] !== 'Student') {
                    $employed_count++;
                }
            }

            $avg_age = round($total_age / $total_members, 1);
            $female_count = $total_members - $male_count;
            ?>

            <style>
                .stats-section {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                    margin-bottom: 30px;
                }

                .stat-card {
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                    transition: all 0.3s ease;
                    cursor: pointer;
                }

                .stat-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 25px rgba(0,102,0,0.15);
                }

                .stat-card:hover h3 {
                    color: #008800;
                }

                .stat-card:hover .stat-value {
                    color: #006600;
                }
                .stat-card h3 {
                    color: #006600;
                    margin: 0 0 15px 0;
                    font-size: 16px;
                }

                .stat-value {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 10px;
                }

                .stat-detail {
                    font-size: 14px;
                    color: #666;
                }

                .education-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .education-list li {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 5px;
                    font-size: 14px;
                }
            </style>

            <!-- <div class="stat-card">
                <h3>Age Statistics</h3>
                <div class="stat-value"><?php echo $avg_age; ?></div>
                <div class="stat-detail">Average Age</div>
            </div> -->

            <style>
                .stats-section {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                    margin-bottom: 30px;
                }

                .stat-card {
                    position: relative;
                    overflow: hidden;
                }

                .icon-hover {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 64px;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    color: rgba(0, 102, 0, 0.15);
                }

                .stat-card:hover .icon-hover {
                    opacity: 1;
                }

                .stat-card:hover .stat-value,
                .stat-card:hover .stat-detail,
                .stat-card:hover .education-list {
                    position: relative;
                    z-index: 2;
                }
            </style>

            <!-- Update all stat cards -->
            <div class="stat-card">
                <h3>Age Statistics</h3>
                <div class="stat-value"><?php echo $avg_age; ?></div>
                <div class="stat-detail">Average Age</div>
                <div class="icon-hover">
                    <i class="fas fa-clock"></i>
                </div>
            </div>

            <div class="stat-card">
                <h3>Gender Distribution</h3>
                <div class="stat-value"><?php echo round(($male_count/$total_members) * 100); ?>%</div>
                <div class="stat-detail">Male: <?php echo $male_count; ?> | Female: <?php echo $female_count; ?></div>
                <div class="icon-hover">
                    <i class="fas fa-venus-mars"></i>
                </div>
            </div>

            <div class="stat-card">
                <h3>Education Distribution</h3>
                <ul class="education-list">
                    <?php foreach ($education_counts as $edu => $count): ?>
                    <li><span><?php echo htmlspecialchars($edu); ?>:</span><span><?php echo $count; ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <div class="icon-hover">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>

            <div class="stat-card">
                <h3>Employment Status</h3>
                <div class="stat-value"><?php echo $employed_count; ?></div>
                <div class="stat-detail">Employed Members</div>
                <div class="icon-hover">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>

        <!-- Action Buttons Section -->
        <div class="action-buttons" style="margin-bottom: 20px;">
            <button onclick="window.print()" class="action-btn print-btn">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="download_family.php?name=<?php echo urlencode($father_name); ?>&address=<?php echo urlencode($address); ?>" 
               class="action-btn download-btn">
                <i class="fas fa-download"></i> Download
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Father's/Husband's Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Area</th>
                    <th>Marital Status</th>
                    <th>Education</th>
                    <th>Relationship</th>
                    <th>Responsibility</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($family_members as $index => $member): 
                    // Determine relationship
                    $relationship = '';
                    if ($member === $family_head) {
                        $relationship = 'Self';
                    } elseif ($member['gender'] === 'Female' && $member['marital_status'] === 'Married' 
                              && $member['father_name'] === $family_head['name']) {
                        $relationship = 'Wife';
                    } elseif ($member['father_name'] === $family_head['name']) {
                        $relationship = 'Child';
                    } elseif ($member['name'] === $family_head['father_name']) {
                        $relationship = 'Father';
                    } elseif ($member['gender'] === 'Female' && $member['marital_status'] === 'Married' 
                              && $family_head['father_name'] === $member['name']) {
                        $relationship = 'Mother';
                    } elseif ($member['gender'] === 'Female' && $member['marital_status'] === 'Married') {
                        $relationship = 'Wife';
                    }

                    // Set responsibility based on relationship
                    $responsibility = $member['responsibility'];
                    if (empty($responsibility)) {
                        $responsibility = $relationship;
                    }
                ?>
                <tr class="<?php echo $member['gender'] === 'Female' ? 'female-row' : ''; ?>">
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                    <td><?php echo htmlspecialchars($member['father_name']); ?></td>
                    <td><?php echo htmlspecialchars($member['gender']); ?></td>
                    <td><?php echo htmlspecialchars($member['age']); ?></td>
                   
                    <td><?php echo htmlspecialchars(str_replace('_', ' ', ucwords($member['area']))); ?></td>
                    <td><?php echo htmlspecialchars($member['marital_status']); ?></td>
                    <td><?php echo htmlspecialchars($member['education']); ?></td>
                    <td><?php echo htmlspecialchars($relationship); ?></td>
                    <td><?php echo htmlspecialchars($responsibility); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>