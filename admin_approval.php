<?php
session_start();
include 'db.php';

// Check if logged in and is main admin
if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != 5) {
    header("Location: login.php");
    exit();
}

// Handle approval action
if (isset($_POST['approve'])) {
    $admin_id = $_POST['admin_id'];
    $stmt = $conn->prepare("UPDATE users SET is_approved = 1 WHERE id = ? AND role = 'admin'");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    header("Location: admin_approval.php");
    exit();
}

// Handle revoke action
if (isset($_POST['revoke'])) {
    $admin_id = $_POST['admin_id'];
    $stmt = $conn->prepare("UPDATE users SET is_approved = 0 WHERE id = ? AND role = 'admin' AND id != 5");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    header("Location: admin_approval.php");
    exit();
}

// Get both pending and approved admins
$pending_result = $conn->query("SELECT id, name, email, password FROM users WHERE role = 'admin' AND is_approved = 0");
$approved_result = $conn->query("SELECT id, name, email, password FROM users WHERE role = 'admin' AND is_approved = 1 AND id != 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Approval</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .header {
            background: linear-gradient(90deg, #006600 0%, #008800 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .admin-request {
            background: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .admin-request:hover {
            transform: translateY(-5px);
        }
        .info-label {
            font-weight: bold;
            color: #333;
            min-width: 100px;
            display: inline-block;
        }
        .info-value {
            color: #666;
            margin-left: 10px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-approve {
            background: #006600;
            color: white;
        }
        .btn-approve:hover {
            background: #004d00;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #bb2d3b;
        }
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .empty-state i {
            font-size: 50px;
            color: #006600;
            margin-bottom: 20px;
        }
        .empty-state h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .empty-state p {
            color: #666;
            margin: 0;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #006600;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .back-btn:hover {
            color: #004d00;
        }
        .approved {
            border-left: 4px solid #006600;
        }
        .btn-revoke {
            background: #dc3545;
            color: white;
        }
        .btn-revoke:hover {
            background: #bb2d3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <!-- Pending Admins Section -->
        <div class="header">
            <h2><i class="fas fa-user-clock"></i> Pending Admin Approvals</h2>
        </div>

        <?php if ($pending_result->num_rows > 0): ?>
            <?php while ($row = $pending_result->fetch_assoc()): ?>
            <div class="admin-request">
                <p><span class="info-label"><i class="fas fa-user"></i> Name:</span><span class="info-value"><?php echo htmlspecialchars($row['name']); ?></span></p>
                <p><span class="info-label"><i class="fas fa-envelope"></i> Email:</span><span class="info-value"><?php echo htmlspecialchars($row['email']); ?></span></p>
                <p><span class="info-label"><i class="fas fa-key"></i> Password:</span><span class="info-value"><?php echo htmlspecialchars($row['password']); ?></span></p>
                <div class="button-group">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve" class="btn btn-approve">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete" class="btn btn-delete">
                            <i class="fas fa-times"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-check"></i>
                <h3>No Pending Approvals</h3>
                <p>There are no admin requests waiting for approval.</p>
            </div>
        <?php endif; ?>

        <!-- Approved Admins Section -->
        <div class="header" style="margin-top: 40px;">
            <h2><i class="fas fa-user-check"></i> Approved Admins</h2>
        </div>

        <?php if ($approved_result->num_rows > 0): ?>
            <?php while ($row = $approved_result->fetch_assoc()): ?>
            <div class="admin-request approved">
                <p><span class="info-label"><i class="fas fa-user"></i> Name:</span><span class="info-value"><?php echo htmlspecialchars($row['name']); ?></span></p>
                <p><span class="info-label"><i class="fas fa-envelope"></i> Email:</span><span class="info-value"><?php echo htmlspecialchars($row['email']); ?></span></p>
                <p><span class="info-label"><i class="fas fa-key"></i> Password:</span><span class="info-value"><?php echo htmlspecialchars($row['password']); ?></span></p>
                <div class="button-group">
                    <form method="POST">
                        <input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="revoke" class="btn btn-delete">
                            <i class="fas fa-user-times"></i> Revoke Access
                        </button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No Approved Admins</h3>
                <p>There are no additional approved admins at this time.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>