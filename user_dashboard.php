<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user = $pdo->prepare("SELECT name, email FROM users WHERE user_id = ?");
$user->execute([$user_id]);
$user = $user->fetch();

// Fetch collectors
$collectors = $pdo->query("SELECT c.*, u.name FROM collectors c JOIN users u ON c.user_id = u.user_id WHERE c.verified = 1")->fetchAll();

// Handle e-waste submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_name'])) {
    $item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
    $item_type = filter_input(INPUT_POST, 'item_type', FILTER_SANITIZE_STRING);
    $preferred_time = $_POST['preferred_time'];
    $collector_id = $_POST['collector_id'];

    $stmt = $pdo->prepare("INSERT INTO ewaste_requests (user_id, collector_id, item_name, item_type, preferred_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $collector_id, $item_name, $item_type, $preferred_time]);

    // Award points
    $stmt = $pdo->prepare("INSERT INTO rewards (user_id, points) VALUES (?, 10) ON DUPLICATE KEY UPDATE points = points + 10");
    $stmt->execute([$user_id]);

    header("Location: pickup_confirm.php?request_id=" . $pdo->lastInsertId());
    exit;
}

// Fetch past requests
$requests = $pdo->prepare("SELECT r.*, c.organization FROM ewaste_requests r JOIN collectors c ON r.collector_id = c.collector_id WHERE r.user_id = ? ORDER BY created_at DESC");
$requests->execute([$user_id]);
$requests = $requests->fetchAll();

// Count total items recycled
$total_items = $pdo->prepare("SELECT COUNT(*) as count FROM ewaste_requests WHERE user_id = ? AND status = 'completed'");
$total_items->execute([$user_id]);
$total_items = $total_items->fetch()['count'];

// Count pending pickups
$pending_pickups = $pdo->prepare("SELECT COUNT(*) as count FROM ewaste_requests WHERE user_id = ? AND status IN ('pending', 'approved')");
$pending_pickups->execute([$user_id]);
$pending_pickups = $pending_pickups->fetch()['count'];

// Calculate environmental impact (simplified - 5kg CO2 per item)
$environmental_impact = $total_items * 5;

// Fetch reward points
$points = $pdo->prepare("SELECT points FROM rewards WHERE user_id = ?");
$points->execute([$user_id]);
$points = $points->fetch()['points'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRecycle - User Dashboard</title>
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
            margin-top:50px;
        }
        
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
            content: "♻️";
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
        
        .user-points {
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        .points-value {
            color: var(--accent);
            font-weight: bold;
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
        
        .dashboard-main {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 2rem;
        }
        
        @media (max-width: 1024px) {
            .dashboard-main {
                grid-template-columns: 1fr;
            }
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
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(76, 102, 99, 0.2);
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
        
        .status-approved {
            background-color: rgba(40, 167, 69, 0.2);
            color: #155724;
        }
        
        .status-completed {
            background-color: rgba(76, 102, 99, 0.2);
            color: var(--primary);
        }
        
        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.2);
            color: #721c24;
        }
        
        .collector-card {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .collector-card:hover,
        .collector-card.selected {
            background-color: rgba(76, 102, 99, 0.1);
            border-color: var(--primary);
        }
        
        .collector-logo {
            width: 50px;
            height: 50px;
            background-color: var(--secondary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .collector-info {
            flex: 1;
        }
        
        .collector-name {
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        
        .collector-org {
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        .collector-rating {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .rating-stars {
            color: var(--accent);
            margin-right: 0.5rem;
        }
        
        .eco-tip {
            background-color: rgba(76, 102, 99, 0.1);
            border-left: 4px solid var(--primary);
            padding: 1rem;
            border-radius: 0 8px 8px 0;
            margin-bottom: 1.5rem;
        }
        
        .eco-tip-title {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        
        .eco-tip-content {
            font-size: 0.9rem;
        }
        
        .reward-progress {
            background-color: #eee;
            height: 10px;
            border-radius: 5px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .reward-progress-bar {
            height: 100%;
            background: linear-gradient(to right, var(--primary), var(--accent));
            border-radius: 5px;
            width: <?php echo min(100, ($points / 500) * 100); ?>%;
        }
        
        .reward-milestone {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--secondary);
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
        
        .dashboard-stats .stat-card {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .dashboard-stats .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }
        
        .dashboard-stats .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .dashboard-stats .stat-card:nth-child(3) {
            animation-delay: 0.3s;
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
                <div class="brand-name">EcoRecycle</div>
            </div>
            <div class="user-profile">
                <div class="user-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                    <span class="user-points">Reward Points: <span class="points-value"><?php echo $points; ?></span></span>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card primary">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Total Items Recycled</h3>
                    </div>
                    <div class="stat-icon primary">♻️</div>
                </div>
                <div class="stat-value"><?php echo $total_items; ?></div>
                <div class="stat-description">You've helped reduce e-waste by recycling <?php echo $total_items; ?> items</div>
            </div>
            
            <div class="stat-card accent">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Pending Pickups</h3>
                    </div>
                    <div class="stat-icon accent">🚚</div>
                </div>
                <div class="stat-value"><?php echo $pending_pickups; ?></div>
                <div class="stat-description">You have <?php echo $pending_pickups; ?> pickup requests waiting to be processed</div>
            </div>
            
            <div class="stat-card secondary">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Environmental Impact</h3>
                    </div>
                    <div class="stat-icon secondary">🌱</div>
                </div>
                <div class="stat-value"><?php echo $environmental_impact; ?> kg</div>
                <div class="stat-description">CO₂ emissions saved through your recycling efforts</div>
            </div>
        </div>
        
        <!-- Dashboard Main Content -->
        <div class="dashboard-main">
            <!-- Left Column -->
            <div>
                <!-- Add E-Waste Form -->
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Add E-Waste Item</h2>
                    </div>
                    
                    <div class="eco-tip">
                        <div class="eco-tip-title">Eco Tip of the Day</div>
                        <div class="eco-tip-content">Keep batteries separate from other e-waste. They can be recycled but need special handling due to their chemical composition.</div>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="item_name">Item Name</label>
                            <input type="text" id="item_name" name="item_name" required class="form-control" placeholder="E.g., Old Smartphone, Broken Laptop">
                        </div>
                        
                        <div class="form-group">
                            <label for="item_type">Item Type</label>
                            <select id="item_type" name="item_type" required class="form-control">
                                <option value="">Select item type</option>
                                <option value="smartphone">Smartphone</option>
                                <option value="laptop">Laptop</option>
                                <option value="tablet">Tablet</option>
                                <option value="monitor">Monitor</option>
                                <option value="printer">Printer</option>
                                <option value="battery">Battery</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="preferred_time">Preferred Pickup Time</label>
                            <input type="datetime-local" id="preferred_time" name="preferred_time" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label>Select Collector</label>
                            <?php foreach ($collectors as $index => $collector): ?>
                                <div class="collector-card <?php echo $index === 0 ? 'selected' : ''; ?>" data-collector-id="<?php echo $collector['collector_id']; ?>">
                                    <div class="collector-logo"><?php echo strtoupper(substr($collector['name'], 0, 1)); ?></div>
                                    <div class="collector-info">
                                        <div class="collector-name"><?php echo htmlspecialchars($collector['name']); ?></div>
                                        <div class="collector-org"><?php echo htmlspecialchars($collector['organization']); ?></div>
                                        <div class="collector-rating">
                                            <div class="rating-stars">★★★★★</div>
                                            <span>5.0 (120 reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <input type="hidden" id="collector_id" name="collector_id" value="<?php echo !empty($collectors) ? $collectors[0]['collector_id'] : ''; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Request Pickup</button>
                    </form>
                </div>
                
                <!-- Past Pickups -->
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Past Pickups</h2>
                    </div>
                    
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Collector</th>
                                    <th>Status</th>
                                    <th>Preferred Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($request['organization']); ?></td>
                                        <td>
                                            <?php 
                                            $status_class = '';
                                            switch(strtolower($request['status'])) {
                                                case 'pending':
                                                    $status_class = 'status-pending';
                                                    break;
                                                case 'approved':
                                                    $status_class = 'status-approved';
                                                    break;
                                                case 'completed':
                                                    $status_class = 'status-completed';
                                                    break;
                                                case 'cancelled':
                                                    $status_class = 'status-cancelled';
                                                    break;
                                                default:
                                                    $status_class = 'status-pending';
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($request['status']); ?></span>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($request['preferred_time'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($requests)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center;">No past pickups found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div>
                <!-- Rewards Section -->
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Rewards Program</h2>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;">Your Progress</h3>
                        <p style="color: var(--secondary); margin-bottom: 0.5rem;"><?php echo $points; ?> points collected</p>
                        
                        <div class="reward-progress">
                            <div class="reward-progress-bar"></div>
                        </div>
                        
                        <div class="reward-milestone">
                            <span>0 points</span>
                            <span>Next reward: 250 points</span>
                            <span>500 points</span>
                        </div>
                        
                        <div style="margin-top: 2rem;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Available Rewards</h3>
                            
                            <div style="background-color: #f9f9f9; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="font-weight: bold;">10% Discount Coupon</span>
                                    <span style="color: var(--accent); font-weight: bold;">150 points</span>
                                </div>
                                <p style="font-size: 0.9rem; color: var(--secondary);">Get 10% off your next purchase at EcoStore</p>
                            </div>
                            
                            <div style="background-color: #f9f9f9; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="font-weight: bold;">Plant a Tree</span>
                                    <span style="color: var(--accent); font-weight: bold;">250 points</span>
                                </div>
                                <p style="font-size: 0.9rem; color: var(--secondary);">We'll plant a tree in your name</p>
                            </div>
                            
                            <div style="background-color: #f9f9f9; border-radius: 8px; padding: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="font-weight: bold;">$25 Gift Card</span>
                                    <span style="color: var(--accent); font-weight: bold;">500 points</span>
                                </div>
                                <p style="font-size: 0.9rem; color: var(--secondary);">Redeem for a $25 gift card to select retailers</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events -->
                <div class="section">
                    <div class="section-header">
                        <h2 class="section-title">Upcoming Events</h2>
                    </div>
                    
                    <div style="background-color: rgba(255, 200, 92, 0.1); border-radius: 8px; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid var(--accent);">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">Community Cleanup Day</div>
                        <div style="font-size: 0.9rem; margin-bottom: 0.5rem;">Date: May 1, 2025</div>
                        <div style="font-size: 0.9rem; color: var(--secondary);">Join us for a day of community cleanup and earn double reward points!</div>
                    </div>
                    
                    <div style="background-color: rgba(76, 102, 99, 0.1); border-radius: 8px; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid var(--primary);">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">E-Waste Education Workshop</div>
                        <div style="font-size: 0.9rem; margin-bottom: 0.5rem;">Date: May 15, 2025</div>
                        <div style="font-size: 0.9rem; color: var(--secondary);">Learn about the importance of proper e-waste disposal and recycling.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>© 2025 EcoRecycle. Making the world greener, one device at a time.</p>
    </div>
    
    <script>
        // Handle collector selection
        document.querySelectorAll('.collector-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.collector-card').forEach(c => {
                    c.classList.remove('selected');
                });
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Update hidden input with collector ID
                const collectorId = this.getAttribute('data-collector-id');
                document.getElementById('collector_id').value = collectorId;
            });
        });

        // Set default datetime for pickup time (next day at 10am)
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(now.getDate() + 1);
            tomorrow.setHours(10, 0, 0, 0);
            
            // Format for datetime-local input
            const formattedDate = tomorrow.toISOString().slice(0, 16);
            document.getElementById('preferred_time').value = formattedDate;
        });
    </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>
hey in this i dindt like the colors they used and all but nav bar is good make ui more geretaful and a premium look i want and also add breadcrumbs