<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'collector') {
    header("Location: login.php");
    exit;
}

$collector_id = $pdo->query("SELECT collector_id FROM collectors WHERE user_id = " . $_SESSION['user_id'])->fetch()['collector_id'];

// Handle accept/reject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $status = $action == 'accept' ? 'accepted' : 'rejected';

    $stmt = $pdo->prepare("UPDATE ewaste_requests SET status = ? WHERE request_id = ?");
    $stmt->execute([$status, $request_id]);

    $stmt = $pdo->prepare("INSERT INTO pickup_status (request_id, status) VALUES (?, ?)");
    $stmt->execute([$request_id, $status]);
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE ewaste_requests SET status = ? WHERE request_id = ?");
    $stmt->execute([$status, $request_id]);

    $stmt = $pdo->prepare("INSERT INTO pickup_status (request_id, status) VALUES (?, ?)");
    $stmt->execute([$request_id, $status]);
}

// Fetch requests
$requests = $pdo->prepare("SELECT r.*, u.name AS user_name FROM ewaste_requests r JOIN users u ON r.user_id = u.user_id WHERE r.collector_id = ?");
$requests->execute([$collector_id]);
$requests = $requests->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Dashboard</title>
    <style>
        :root {
            --primary: #4C6663;
            --secondary: #667D79;
            --accent: #FFC85C;
            --light: #f5f5f5;
            --dark: #333;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            color: var(--dark);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            margin-top:50px;        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--accent);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background-color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .logo::before {
            content: "";
            position: absolute;
            width: 30px;
            height: 30px;
            background-color: var(--accent);
            border-radius: 50%;
            transform: translateY(5px);
        }
        
        .logo::after {
            content: "♻";
            position: relative;
            z-index: 1;
            font-size: 20px;
        }
        
        .brand-name {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .user-info span {
            display: block;
        }
        
        .user-name {
            font-weight: bold;
        }
        
        .user-role {
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary {
            border-top: 4px solid var(--primary);
        }
        
        .stat-card.accent {
            border-top: 4px solid var(--accent);
        }
        
        .stat-card.secondary {
            border-top: 4px solid var(--secondary);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-icon.primary {
            background-color: var(--primary);
        }
        
        .stat-icon.accent {
            background-color: var(--accent);
        }
        
        .stat-icon.secondary {
            background-color: var(--secondary);
        }
        
        .stat-title {
            font-size: 1rem;
            color: var(--secondary);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-description {
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        .section {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--primary);
            position: relative;
            padding-left: 1rem;
        }
        
        .section-title::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--accent);
            border-radius: 2px;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a4f4d;
            transform: translateY(-2px);
        }
        
        .btn-accent {
            background-color: var(--accent);
            color: var(--dark);
        }
        
        .btn-accent:hover {
            background-color: #e6b44f;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .custom-table th,
        .custom-table td {
            padding: 1rem;
            text-align: left;
        }
        
        .custom-table th {
            background-color: rgba(76, 102, 99, 0.1);
            font-weight: 600;
            color: var(--primary);
            position: sticky;
            top: 0;
        }
        
        .custom-table tr {
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }
        
        .custom-table tr:hover {
            background-color: rgba(255, 200, 92, 0.1);
        }
        
        .custom-table tr:last-child {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }
        
        .status-accepted {
            background-color: rgba(40, 167, 69, 0.2);
            color: #155724;
        }
        
        .status-rejected {
            background-color: rgba(220, 53, 69, 0.2);
            color: #721c24;
        }
        
        .status-picked_up {
            background-color: rgba(76, 102, 99, 0.2);
            color: var(--primary);
        }
        
        .status-recycled {
            background-color: rgba(139, 92, 246, 0.2);
            color: #5a2dbb;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        /* Animation effects */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .footer {
            text-align: center;
            padding: 2rem 0;
            color: var(--secondary);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="logo-container">
                <div class="logo"></div>
                <div class="brand-name">EcoRecycle Collector</div>
            </div>
            <div class="user-profile">
                <div class="user-avatar">C</div>
                <div class="user-info">
                    <span class="user-name">Collector</span>
                    <span class="user-role">E-Waste Collector</span>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card primary fade-in" style="animation-delay: 0.1s">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Pending Requests</h3>
                    </div>
                    <div class="stat-icon primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">
                    <?php 
                    $count = 0;
                    foreach($requests as $request) {
                        if($request['status'] == 'pending') $count++;
                    }
                    echo $count;
                    ?>
                </div>
                <div class="stat-description">Requests waiting for your response</div>
            </div>
            
            <div class="stat-card accent fade-in" style="animation-delay: 0.2s">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Accepted Requests</h3>
                    </div>
                    <div class="stat-icon accent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">
                    <?php 
                    $count = 0;
                    foreach($requests as $request) {
                        if($request['status'] == 'accepted') $count++;
                    }
                    echo $count;
                    ?>
                </div>
                <div class="stat-description">Requests you've accepted</div>
            </div>
            
            <div class="stat-card secondary fade-in" style="animation-delay: 0.3s">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Completed</h3>
                    </div>
                    <div class="stat-icon secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.07V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="23 3 12 14 9 11"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">
                    <?php 
                    $count = 0;
                    foreach($requests as $request) {
                        if($request['status'] == 'recycled') $count++;
                    }
                    echo $count;
                    ?>
                </div>
                <div class="stat-description">Requests successfully recycled</div>
            </div>
        </div>
        
        <!-- Requests Table -->
        <div class="section fade-in" style="animation-delay: 0.4s">
            <div class="section-header">
                <h2 class="section-title">Pickup Requests</h2>
            </div>
            
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Preferred Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No pickup requests found</td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 40px; height: 40px; background-color: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 1rem;">
                                            <?php echo strtoupper(substr($request['user_name'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($request['user_name']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($request['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['item_type']); ?></td>
                                <td><?php echo date('M j, Y g:i A', strtotime($request['preferred_time'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($request['status']); ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($request['status'] == 'pending'): ?>
                                        <form method="POST" style="display: flex; gap: 0.5rem;">
                                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="btn btn-primary">Accept</button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    <?php elseif ($request['status'] == 'accepted'): ?>
                                        <form method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                                            <select name="status" class="form-control" style="padding: 0.5rem;">
                                                <option value="">Update Status</option>
                                                <option value="picked_up">Picked Up</option>
                                                <option value="recycled">Recycled</option>
                                            </select>
                                            <button type="submit" class="btn btn-accent">Update</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>© 2025 EcoRecycle. Making the world greener, one device at a time.</p>
    </div>
    
    <script>
        // Simple script to handle row hover effects
        document.querySelectorAll('.custom-table tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>