<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Define areas
// Update the areas array
$areas = [
    'sohrab_goth' => 'Sohrab Goth',  // Updated key and display name
    'lassi_goth' => 'Lassi Goth',
    'gulshan_maymar' => 'Gulshan Maymar',
    'jhanjar_goth' => 'Jhanjar Goth',
    'gadap' => 'Gadap',
    'bahria' => 'Bahria',
    'ahsan_abad' => 'Ahsan Abad'
];

// Get count of karkunan for each area
$area_counts = [];
foreach ($areas as $key => $name) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM karkunan WHERE area = ? OR area = ?");
    $stmt->bind_param("ss", $key, $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $area_counts[$key] = $result->fetch_assoc()['count'];
}

// Handle form submission
// Update the form tag to include enctype
echo '<form method="POST" enctype="multipart/form-data">';

// Add this to your PHP section after the existing POST handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['size'] > 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $conn->begin_transaction();
                try {
                    // Insert into karkunan table with all required fields
                    $stmt = $conn->prepare("INSERT INTO karkunan (name, father_name, name_relation, gender, age, marital_status, address, cnic, education, source_of_income, responsibility, area) VALUES (?, ?, 'father', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssississs", 
                        $data[0],  // name
                        $data[1],  // father_name
                        $data[2],  // gender
                        $data[3],  // age
                        $data[4],  // marital_status
                        $data[5],  // address
                        $data[6],  // cnic
                        $data[7],  // education
                        $data[8],  // source_of_income
                        $data[9],  // responsibility
                        $_POST['area']
                    );
                    $stmt->execute();
                    $karkun_id = $conn->insert_id;

                    // Handle member status from CSV
                    $member_status = strtolower(trim($data[10]));
                    if ($member_status === 'arkan') {
                        $stmt = $conn->prepare("INSERT INTO arkan (karkun_id, joining_date) VALUES (?, ?)");
                        $stmt->bind_param("is", $karkun_id, $data[11]);
                        $stmt->execute();
                    } elseif ($member_status === 'umedwar') {
                        $stmt = $conn->prepare("INSERT INTO umedwar (karkun_id, application_date) VALUES (?, CURRENT_DATE())");
                        $stmt->bind_param("i", $karkun_id);
                        $stmt->execute();
                    }

                    $conn->commit();
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<script>alert('Error occurred while importing row: " . addslashes($data[0]) . " - " . $e->getMessage() . "');</script>";
                }
            }
            fclose($handle);
            echo "<script>alert('CSV data imported successfully!');</script>";
        }
    } elseif (isset($_POST['add_karkun'])) {
        $conn->begin_transaction();
        try {
            // Insert into karkunan table
            $stmt = $conn->prepare("INSERT INTO karkunan (
                name, 
                father_name, 
                name_relation, 
                gender, 
                age, 
                marital_status, 
                address, 
                cnic, 
                education, 
                mobile_number,
                source_of_income, 
                responsibility, 
                area
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("ssssissssssss", 
                $_POST['name'],
                $_POST['father_name'],
                $_POST['name_relation'],
                $_POST['gender'],
                $_POST['age'],
                $_POST['marital_status'],
                $_POST['address'],
                $_POST['cnic'],
                $_POST['education'],
                $_POST['mobile'],  // Added mobile number
                $_POST['source_of_income'],
                $_POST['responsibility'],
                $_POST['area']
            );
            $stmt->execute();
            $karkun_id = $conn->insert_id;
        
            // Insert into respective status table
            if ($_POST['member_status'] === 'arkan') {
                $stmt = $conn->prepare("INSERT INTO arkan (karkun_id, joining_date) VALUES (?, ?)");
                $stmt->bind_param("is", $karkun_id, $_POST['joining_date']);
                $stmt->execute();
            } elseif ($_POST['member_status'] === 'umedwar') {
                $stmt = $conn->prepare("INSERT INTO umedwar (karkun_id, application_date) VALUES (?, CURRENT_DATE())");
                $stmt->bind_param("i", $karkun_id);
                $stmt->execute();
            }
        
            $conn->commit();
            echo "<script>alert('Karkun added successfully!');</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error occurred while adding karkun.');</script>";
        }
    }
}

// Add this JavaScript at the bottom of your file before </body>
// Remove this duplicate code block
// if (isset($_POST['add_karkun'])) {
//     $stmt = $conn->prepare("INSERT INTO karkunan...");
//     $stmt->bind_param("ssisssssss", ...);
//     $stmt->execute();
// }

// Get selected area
$selected_area = isset($_GET['area']) ? $_GET['area'] : '';

// Fetch karkunan data if area is selected
$karkunan = [];
if ($selected_area) {
    $stmt = $conn->prepare("SELECT * FROM karkunan WHERE area = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $selected_area);
    $stmt->execute();
    $result = $stmt->get_result();
    $karkunan = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karkunan Management - Digital Jamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Global Styles Update */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Enhanced Navbar */
        .navbar {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            padding: 4px 20px;
            box-shadow: 0 4px 20px rgba(0,102,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 24px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: white;
            margin: 0;
        }
        

        .detail-btn {
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

        .detail-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.15);
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
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

        /* Improved Area Cards Grid */
        .areas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
            padding: 40px;
            max-width: 1400px;
            margin: 40px auto;
        }

        .area-card {
            background: white;
            border-radius: 24px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,102,0,0.08);
            position: relative;
            overflow: hidden;
            text-decoration: none; /* Added this */
        }

        .area-card::before {
            content: '+ Add Karkun';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,102,0,0.9);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            opacity: 0;
            transition: all 0.3s ease;
            font-weight: 500;
            z-index: 2;
        }

        .area-card:hover::before {
            opacity: 1;
        }

        .area-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,102,0,0.12);
            border-color: #006600;
        }

        .area-card:hover .count-badge,
        .area-card:hover i,
        .area-card:hover h3 {
            opacity: 0.3;
        }

        .area-card i {
            font-size: 64px;
            background: linear-gradient(135deg, #006600 0%, #008800 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 25px;
            transition: all 0.4s ease;
        }

        .area-card:hover i {
            transform: scale(1.15) translateY(-5px);
        }

        .count-badge {
            background: linear-gradient(135deg, #e8f5e8 0%, #d1e7d1 100%);
            color: #006600;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 25px;
            display: inline-block;
            border: 1px solid rgba(0,102,0,0.1);
            box-shadow: 0 4px 15px rgba(0,102,0,0.05);
            transition: all 0.3s ease;
        }

        .area-card:hover .count-badge {
            background: linear-gradient(135deg, #006600 0%, #008800 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 6px 20px rgba(0,102,0,0.15);
        }

        .area-card h3 {
            color: #006600;
            font-size: 26px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .area-card:hover h3 {
            transform: scale(1.05);
        }

        /* Back Button Enhancement */
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 30px;
            background: linear-gradient(135deg, #006600 0%, #008800 100%);
            color: white;
            font-weight: 500;
            border-radius: 50px;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(0,102,0,0.15);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,102,0,0.2);
        }

        /* Container Enhancement */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
        }

        /* Responsive Improvements */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .areas-grid {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 20px;
            }
            
            .area-card {
                padding: 30px 25px;
            }
            
            .container {
                padding: 15px;
            }
        }

        /* Form Styling */
        .form-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .form-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }

        .tab-btn {
            padding: 12px 25px;
            background: #f5f5f5;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            color: #666;
        }

        .tab-btn.active {
            background: #006600;
            color: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #006600;
            box-shadow: 0 0 0 3px rgba(0,102,0,0.1);
            outline: none;
        }

        /* CSV Upload Section */
        .csv-container {
            background: #f8f9f8;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-top: 20px;
        }

        .csv-info {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .csv-info h3 {
            color: #006600;
            margin-bottom: 15px;
        }

        .csv-info ul {
            text-align: left;
            margin: 15px 0;
            padding-left: 20px;
            color: #555;
        }

        .file-upload {
            border: 2px dashed #006600;
            padding: 40px;
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
            margin: 20px 0;
        }

        .file-upload.highlight {
            background: #f0f4f0;
            border-color: #008800;
            transform: scale(1.02);
        }

        .file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            cursor: pointer;
        }

        .file-label i {
            font-size: 48px;
            color: #006600;
            transition: transform 0.3s ease;
        }

        .file-label:hover i {
            transform: translateY(-5px);
        }

        /* Submit Button */
        .submit-btn {
            background: #006600;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 20px;
            width: 100%;
        }

        .submit-btn:hover {
            background: #008800;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,102,0,0.2);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 30px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        /* View Details Button */
        .view-details-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 15px 35px;
            background: linear-gradient(135deg, #006600 0%, #008800 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,102,0,0.15);
            margin: 20px auto;
        }

        .view-details-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,102,0,0.2);
            background: linear-gradient(135deg, #008800 0%, #009900 100%);
        }

        .view-details-btn i {
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .view-details-btn {
                padding: 12px 25px;
                font-size: 14px;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .areas-grid {
                grid-template-columns: 1fr;
            }

            .form-container {
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
        <h1>Karkunan Management</h1>
        <div class="navbar-right">
            <a href="karkundetail.php" class="detail-btn">All Karkunan</a>
            <a href="admin.php" class="back-btn">Back to Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- Remove the back button from here since it's now in navbar -->
        <?php if (!$selected_area): ?>
        <!-- Show area cards if no area is selected -->
        <div class="areas-grid">
            <?php foreach ($areas as $key => $name): ?>
            <a href="?area=<?php echo $key; ?>" class="area-card">
                <div class="count-badge">
                    <?php echo $area_counts[$key]; ?> Karkunan
                </div>
                <i class="fas fa-map-marker-alt"></i>
                <h3><?php echo $name; ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php else: ?>
        <!-- Show form and table if area is selected -->
        <h2><?php echo $areas[$selected_area]; ?> - Add New Karkun</h2>
        <div class="form-container">
            <form method="POST">
                <input type="hidden" name="area" value="<?php echo $selected_area; ?>">
                
               
                <div class="form-tabs">
                    <button type="button" class="tab-btn active" onclick="showTab('manual')">Manual Entry</button>
                    <button type="button" class="tab-btn" onclick="showTab('csv')">CSV Upload</button>
                </div>

                <div id="manual-form" class="tab-content active">
                    <!-- Existing form fields with additions -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" required placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label>Father's/Husband's Name:</label>
                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <label style="display: inline-flex; align-items: center; gap: 5px;">
                                    <input type="radio" name="name_relation" value="father" checked>
                                    Father's Name
                                </label>
                                <label style="display: inline-flex; align-items: center; gap: 5px;">
                                    <input type="radio" name="name_relation" value="husband">
                                    Husband's Name
                                </label>
                            </div>
                            <input type="text" name="father_name" required placeholder="Enter father's/husband's name">
                        </div>
                        <div class="form-group">
                            <label>Gender:</label>
                            <select name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Age:</label>
                            <input type="number" name="age" required min="18" max="100">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth:</label>
                            <input type="date" name="dob" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Marital Status:</label>
                            <select name="marital_status" required>
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Blood Group:</label>
                            <select name="blood_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address:</label>
                        <textarea name="address" required rows="3" placeholder="Enter complete address"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>CNIC:</label>
                            <input type="text" name="cnic" required pattern="\d{5}-\d{7}-\d" placeholder="00000-0000000-0">
                        </div>
                        <div class="form-group">
                            <label>Mobile Number:</label>
                            <input type="tel" name="mobile" required pattern="03\d{2}-\d{7}" placeholder="0300-0000000">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Education:</label>
                            <input type="text" name="education" required placeholder="Latest education">
                        </div>
                        <div class="form-group">
                            <label>Profession:</label>
                            <input type="text" name="profession" required placeholder="Current profession">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Source of Income:</label>
                        <input type="text" name="source_of_income" required placeholder="Primary source of income">
                    </div>

                    <div class="form-group">
                        <label>Responsibility:</label>
                        <select name="responsibility" required>
                            <option value="">Select Responsibility</option>
                            <option value="Nazim">Nazim</option>
                            <option value="Naib Nazim">Naib Nazim</option>
                            <option value="Member">Member</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Member Status:</label>
                        <select name="member_status" required>
                            <option value="">Select Status</option>
                            <option value="karkun">Karkun</option>
                            <option value="arkan">Arkan</option>
                            <option value="umedwar">Umedwar</option>
                        </select>
                    </div>

                    <div class="form-group joining-date" style="display: none;">
                        <label>Joining Date:</label>
                        <input type="date" name="joining_date">
                    </div>
                </div>

                <div id="csv-upload" class="tab-content">
                    <div class="csv-container">
                        <div class="csv-info">
                            <h3>CSV Upload Instructions</h3>
                            <p>Please ensure your CSV file has the following columns:</p>
                            <ul>
                                <li>Name, Father's/Husband's Name, Gender, Age, Marital Status, Address, CNIC, Education, Source of Income, Responsibility, Member Status (arkan/umedwar/karkun), Joining Date (for arkan only)</li>
                            </ul>
                            <a href="templates/karkunan_template.csv" download class="template-btn">Download Template</a>
                        </div>
                        <div class="file-upload">
                            <input type="file" name="csv_file" id="csv_file" accept=".csv" class="file-input">
                            <label for="csv_file" class="file-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose CSV file or drag it here</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" name="add_karkun" class="submit-btn">Add Karkun</button>
            </form>
        </div>

       
            <div style="text-align: center; margin-top: 30px; margin-bottom: 30px;">
                <a href="karkundetail.php" class="view-details-btn">
                    <i class="fas fa-list"></i>
                    View All Karkunan List
                </a>
            </div>
            <?php endif; ?>

    </div>
</body>
</html>


<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabName + '-form').classList.add('active');
    event.currentTarget.classList.add('active');
}

// Add drag and drop functionality
const fileUpload = document.querySelector('.file-upload');
const fileInput = document.querySelector('.file-input');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    fileUpload.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    fileUpload.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    fileUpload.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    fileUpload.classList.add('highlight');
}

function unhighlight(e) {
    fileUpload.classList.remove('highlight');
}

fileUpload.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
    
    // Auto submit when file is dropped
    if (files.length > 0 && files[0].type === 'text/csv') {
        document.querySelector('form').submit();
    }
}

// Handle file input change
fileInput.addEventListener('change', function(e) {
    if (this.files.length > 0) {
        document.querySelector('form').submit();
    }
});
// Add this to your existing script section
document.querySelector('select[name="member_status"]').addEventListener('change', function() {
    const joiningDateField = document.querySelector('.joining-date');
    if (this.value === 'arkan') {
        joiningDateField.style.display = 'block';
        joiningDateField.querySelector('input').required = true;
    } else {
        joiningDateField.style.display = 'none';
        joiningDateField.querySelector('input').required = false;
    }
});
</script>


<style>
    .stats-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        padding: 40px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .stats-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .stats-header h3 {
        color: #333;
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .total-count {
        background: #e8f5e9;
        color: #006600;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 500;
    }

    .stats-bars {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .stat-bar {
        display: grid;
        grid-template-columns: 80px 1fr 50px;
        align-items: center;
        gap: 10px;
    }

    .bar-label {
        font-size: 14px;
        color: #666;
    }

    .bar-container {
        height: 8px;
        background: #f0f0f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .bar.arkan { background: #0066cc; }
    .bar.umedwar { background: #9933cc; }
    .bar.karkun { background: #006600; }

    .bar-count {
        font-size: 14px;
        color: #333;
        text-align: right;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .stats-dashboard {
            padding: 20px;
            grid-template-columns: 1fr;
        }
    }
</style>
