<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: umeedwar.php");
    exit();
}

$id = $_GET['id'];

// Get umeedwar details
$query = "SELECT u.*, k.id as karkun_id, k.name, k.father_name, k.gender, k.address, k.cnic, k.education 
          FROM umedwar u 
          LEFT JOIN karkunan k ON u.karkun_id = k.id 
          WHERE u.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$umeedwar = mysqli_fetch_assoc($result);

if (!$umeedwar) {
    header("Location: umeedwar.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $karkun_id = $_POST['karkun_id'];
    $application_date = $_POST['application_date'];

    $update_query = "UPDATE umedwar SET karkun_id = ?, application_date = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "isi", $karkun_id, $application_date, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: umeedwar.php");
        exit();
    }
}

// Get all karkunan for dropdown, excluding those who are already umeedwar
$karkunan_query = "SELECT k.* FROM karkunan k 
                   WHERE k.id NOT IN (
                       SELECT karkun_id 
                       FROM umedwar 
                       WHERE karkun_id IS NOT NULL 
                       AND id != ?
                   )
                   AND k.id = ? 
                   ORDER BY k.name ASC";
$stmt = mysqli_prepare($conn, $karkunan_query);
mysqli_stmt_bind_param($stmt, "ii", $id, $umeedwar['karkun_id']);
mysqli_stmt_execute($stmt);
$karkunan_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Umeedwar - Digital Jamat</title>
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #006600;
            font-weight: bold;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            background: #006600;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #008800;
        }

        .btn-secondary {
            background: #666;
        }

        .btn-secondary:hover {
            background: #888;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="https://yt3.googleusercontent.com/zd9vDCi7ROOdiFxkGydYjmryIN7QEr14NWRVpoxTUctjnzXsHI17Z3peIyAGwIjb-Bpilc8_eQ=s900-c-k-c0x00ffffff-no-rj" alt="Logo" style="width: 24px; height: 24px;">
            <h1 style="margin: 0; font-size: 18px;">Digital Jamat - Edit Umeedwar</h1>
        </div>
        <a href="umeedwar.php" style="color: white; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="container">
        <form method="POST">
            <div class="form-group">
                <label>Select Karkun</label>
                <select name="karkun_id" required>
                    <?php while ($karkun = mysqli_fetch_assoc($karkunan_result)): ?>
                        <option value="<?php echo $karkun['id']; ?>" 
                                <?php echo $karkun['id'] == $umeedwar['karkun_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($karkun['name'] . ' - ' . $karkun['father_name'] . ' (' . $karkun['cnic'] . ')'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Application Date</label>
                <input type="date" name="application_date" value="<?php echo $umeedwar['application_date']; ?>" required>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="umeedwar.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>