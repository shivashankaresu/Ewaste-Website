<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['request_id'])) {
    header("Location: login.php");
    exit;
}

$request_id = $_GET['request_id'];
$stmt = $pdo->prepare("SELECT r.*, c.organization, u.name AS user_name FROM ewaste_requests r 
                       JOIN collectors c ON r.collector_id = c.collector_id 
                       JOIN users u ON r.user_id = u.user_id 
                       WHERE r.request_id = ?");
$stmt->execute([$request_id]);
$request = $stmt->fetch();

if (!$request) {
    header("Location: user_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-6 max-w-lg">
        <div class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-300 hover:shadow-xl">
            <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-8">Pickup Confirmation</h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="w-1/3 font-semibold text-gray-600">User:</span>
                    <span class="w-2/3 text-gray-800"><?php echo htmlspecialchars($request['user_name']); ?></span>
                </div>
                <div class="flex items-center">
                    <span class="w-1/3 font-semibold text-gray-600">Collector:</span>
                    <span class="w-2/3 text-gray-800"><?php echo htmlspecialchars($request['organization']); ?></span>
                </div>
                <div class="flex items-center">
                    <span class="w-1/3 font-semibold text-gray-600">Item:</span>
                    <span class="w-2/3 text-gray-800"><?php echo htmlspecialchars($request['item_name']); ?></span>
                </div>
                <div class="flex items-center">
                    <span class="w-1/3 font-semibold text-gray-600">Type:</span>
                    <span class="w-2/3 text-gray-800"><?php echo htmlspecialchars($request['item_type']); ?></span>
                </div>
                <div class="flex items-center">
                    <span class="w-1/3 font-semibold text-gray-600">Preferred Time:</span>
                    <span class="w-2/3 text-gray-800"><?php echo htmlspecialchars($request['preferred_time']); ?></span>
                </div>
                <div class="flex items-center">
                    <span class="w-1/3 font-semibold text-gray-600">Status:</span>
                    <span class="w-2/3 text-gray-800">
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                            <?php echo $request['status'] == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                      ($request['status'] == 'Confirmed' ? 'bg-green-100 text-green-800' : 
                                      'bg-red-100 text-red-800'); ?>">
                            <?php echo htmlspecialchars($request['status']); ?>
                        </span>
                    </span>
                </div>
            </div>
            <a href="user_dashboard.php" 
               class="block mt-8 w-full bg-green-600 text-white text-center py-3 rounded-lg font-semibold 
                      hover:bg-green-700 transition-colors duration-200">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
