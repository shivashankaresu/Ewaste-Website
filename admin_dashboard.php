<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Static data for collectors
$collectors_data = [
    ['collector_id' => 1, 'name' => 'John Doe', 'location' => 'New York, NY', 'organization' => 'EcoCycle', 'verified' => 0],
    ['collector_id' => 2, 'name' => 'Jane Smith', 'location' => 'San Francisco, CA', 'organization' => 'GreenTech', 'verified' => 0],
    ['collector_id' => 3, 'name' => 'Alice Johnson', 'location' => 'Chicago, IL', 'organization' => 'RecycleNow', 'verified' => 1],
    ['collector_id' => 4, 'name' => 'Bob Wilson', 'location' => 'Austin, TX', 'organization' => 'EcoWorks', 'verified' => 0],
    ['collector_id' => 5, 'name' => 'Emma Brown', 'location' => 'Seattle, WA', 'organization' => 'CleanEarth', 'verified' => 0],
    ['collector_id' => 6, 'name' => 'Michael Lee', 'location' => 'Boston, MA', 'organization' => 'GreenFuture', 'verified' => 0],
    ['collector_id' => 7, 'name' => 'Sarah Davis', 'location' => 'Miami, FL', 'organization' => 'EcoCycle', 'verified' => 1],
    ['collector_id' => 8, 'name' => 'David Clark', 'location' => 'Denver, CO', 'organization' => 'RecycleNow', 'verified' => 0],
];

// Debug log
$debug_log = [];
$debug_log[] = "Admin dashboard script loaded";
$debug_log[] = "Static collectors loaded: " . count($collectors_data);

// Pagination settings
$items_per_page = 6;
$page = isset($_GET['page']) ? max(1, filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT)) : 1;
$offset = ($page - 1) * $items_per_page;

// Search and filter
$search_query = isset($_GET['search']) ? trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING)) : '';
$filter_status = isset($_GET['status']) ? filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) : 'unverified';

// Filter collectors
$filtered_collectors = array_filter($collectors_data, function ($collector) use ($search_query, $filter_status) {
    $matches_search = empty($search_query) || 
        stripos($collector['name'], $search_query) !== false || 
        stripos($collector['location'], $search_query) !== false || 
        stripos($collector['organization'], $search_query) !== false;
    $matches_status = $filter_status === 'all' || 
        ($filter_status === 'unverified' && $collector['verified'] == 0) || 
        ($filter_status === 'verified' && $collector['verified'] == 1);
    return $matches_search && $matches_status;
});

// Paginate filtered collectors
$total_collectors = count($filtered_collectors);
$total_pages = ceil($total_collectors / $items_per_page);
$collectors = array_slice($filtered_collectors, $offset, $items_per_page);
$debug_log[] = "Filtered collectors: $total_collectors, Page: $page";

// Handle collector verification (simulate)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_collector'])) {
    $collector_id = filter_input(INPUT_POST, 'collector_id', FILTER_SANITIZE_NUMBER_INT);
    $debug_log[] = "Collector ID $collector_id verification simulated";
    header("Location: admin_dashboard.php?page=$page" . 
           (!empty($search_query) ? "&search=" . urlencode($search_query) : "") . 
           "&status=$filter_status");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EcoRecycle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #2E7D32; /* Dark teal from the image */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <!-- Fixed Header -->
    <header class="bg-white shadow-md flex justify-between items-center px-6 py-4 fixed w-full z-10">
        <div class="flex items-center space-x-2">
            <i class="fas fa-recycle text-2xl text-teal-500"></i>
            <h1 class="text-xl font-bold text-teal-600">EcoRecycle</h1>
        </div>
        <nav class="space-x-4">
            <a href="index.php" class="text-gray-600 hover:text-teal-600">Home</a>
            <a href="#" class="text-gray-600 hover:text-teal-600">About</a>
            <a href="#" class="text-gray-600 hover:text-teal-600">Services</a>
        </nav>
    </header>

    <div class="flex pt-16"> <!-- Added padding-top to avoid overlap with fixed header -->
        <!-- Sidebar -->
        <div id="sidebar" class="bg-teal-800 text-white w-64 min-h-screen p-4 lg:block hidden transition-all">
            <div class="flex items-center mb-8">
                <i class="fas fa-recycle text-2xl mr-2"></i>
                <h2 class="text-xl font-bold">E-Waste Admin</h2>
            </div>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center p-2 bg-teal-900 rounded-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 hover:bg-teal-700 rounded-lg">
                            <i class="fas fa-users mr-2"></i> Collectors
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 hover:bg-teal-700 rounded-lg">
                            <i class="fas fa-chart-bar mr-2"></i> Reports
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 hover:bg-teal-700 rounded-lg">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center space-x-3">
                    <button onclick="toggleSidebar()" class="lg:hidden text-teal-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Collector Verification</h1>
                        <p class="text-gray-600 mt-1">Manage e-waste collectors efficiently</p>
                    </div>
                </div>
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileDropdown()" class="flex items-center space-x-2 text-gray-700">
                        <i class="fas fa-user-circle text-2xl"></i>
                        <span class="hidden sm:inline">Admin</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                        <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-users text-3xl"></i>
                        <div>
                            <h3 class="text-lg font-semibold">Total Collectors</h3>
                            <p class="text-2xl font-bold"><?php echo count($collectors_data); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-3xl"></i>
                        <div>
                            <h3 class="text-lg font-semibold">Verified</h3>
                            <p class="text-2xl font-bold"><?php echo count(array_filter($collectors_data, fn($c) => $c['verified'] == 1)); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-exclamation-circle text-3xl"></i>
                        <div>
                            <h3 class="text-lg font-semibold">Unverified</h3>
                            <p class="text-2xl font-bold"><?php echo count(array_filter($collectors_data, fn($c) => $c['verified'] == 0)); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                <div class="relative flex-1 mb-4 sm:mb-0">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" 
                           placeholder="Search by name, location, or organization..." 
                           class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           oninput="this.form.submit()">
                </div>
                <select name="status" onchange="this.form.submit()" 
                        class="px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="unverified" <?php echo $filter_status === 'unverified' ? 'selected' : ''; ?>>Unverified</option>
                    <option value="verified" <?php echo $filter_status === 'verified' ? 'selected' : ''; ?>>Verified</option>
                </select>
                <?php if (!empty($search_query) || $filter_status !== 'unverified'): ?>
                    <a href="admin_dashboard.php" class="text-indigo-600 hover:underline ml-4">Clear</a>
                <?php endif; ?>
                <form id="searchForm" method="GET" action=""></form>
            </div>

            <!-- Collectors Grid with Skeleton Loader -->
            <div id="collectorsGrid" class="mb-8 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Collectors (<?php echo $total_collectors; ?>)</h2>
                <?php if (empty($collectors)): ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm text-center text-gray-600">
                        No collectors found.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($collectors as $collector): ?>
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition">
                                <div class="flex items-center space-x-3 mb-4">
                                    <i class="fas fa-user text-indigo-600 text-xl"></i>
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($collector['name']); ?></h3>
                                </div>
                                <div class="space-y-2 text-gray-600">
                                    <p><i class="fas fa-map-marker-alt mr-2"></i><?php echo htmlspecialchars($collector['location']); ?></p>
                                    <p><i class="fas fa-building mr-2"></i><?php echo htmlspecialchars($collector['organization']); ?></p>
                                    <p><i class="fas fa-check-circle mr-2"></i><?php echo $collector['verified'] ? 'Verified' : 'Unverified'; ?></p>
                                </div>
                                <?php if (!$collector['verified']): ?>
                                    <form method="POST" action="" class="mt-4">
                                        <input type="hidden" name="collector_id" value="<?php echo $collector['collector_id']; ?>">
                                        <button type="button" onclick="showConfirmModal(<?php echo $collector['collector_id']; ?>, '<?php echo htmlspecialchars($collector['name']); ?>')" 
                                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                                            <i class="fas fa-check mr-2"></i> Verify
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Skeleton Loader -->
            <div id="skeletonLoader" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="h-6 bg-gray-200 rounded w-3/4 mb-4 animate-pulse"></div>
                        <div class="space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-full animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6 animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3 animate-pulse"></div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="flex justify-center space-x-2 mt-6">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : ''; ?>&status=<?php echo $filter_status; ?>" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : ''; ?>&status=<?php echo $filter_status; ?>" 
                           class="px-4 py-2 <?php echo $i == $page ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'; ?> rounded-lg hover:bg-indigo-500 hover:text-white">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : ''; ?>&status=<?php echo $filter_status; ?>" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Debug Log -->
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-md">
                    <button onclick="toggleDebugLog()" class="w-full p-4 flex justify-between items-center text-lg font-semibold text-gray-900 hover:bg-gray-50">
                        Debug Log
                        <i id="debugToggleIcon" class="fas fa-chevron-down"></i>
                    </button>
                    <div id="debugLog" class="hidden p-4 bg-gray-50">
                        <?php if (!empty($debug_log)): ?>
                            <ul class="list-disc pl-6 space-y-2 text-gray-600">
                                <?php foreach ($debug_log as $log): ?>
                                    <li><?php echo htmlspecialchars($log); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-gray-600">No debug messages available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Verification</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to verify <span id="collectorName" class="font-medium"></span>?</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                <form id="verifyForm" method="POST" action="">
                    <input type="hidden" name="collector_id" id="collectorId">
                    <button type="submit" name="verify_collector" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Verify</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 hidden">
        <i class="fas fa-check-circle"></i>
        <span>Collector verified successfully!</span>
    </div>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }

        // Profile dropdown
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        }

        // Confirmation modal
        function showConfirmModal(collectorId, collectorName) {
            document.getElementById('collectorId').value = collectorId;
            document.getElementById('collectorName').innerText = collectorName;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Debug log toggle
        function toggleDebugLog() {
            const debugLog = document.getElementById('debugLog');
            const toggleIcon = document.getElementById('debugToggleIcon');
            debugLog.classList.toggle('hidden');
            toggleIcon.classList.toggle('fa-chevron-down');
            toggleIcon.classList.toggle('fa-chevron-up');
        }

        // Simulate loading
        window.onload = () => {
            setTimeout(() => {
                document.getElementById('skeletonLoader').classList.add('hidden');
                document.getElementById('collectorsGrid').classList.remove('hidden');
            }, 1000);
        };

        // Show toast if redirected after verification
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_collector'])): ?>
            const toast = document.getElementById('toast');
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        <?php endif; ?>

        // Auto-submit search form
        document.querySelector('input[name="search"]').form.id = 'searchForm';
        document.querySelector('select[name="status"]').form.id = 'searchForm';
    </script>

<?php ob_end_flush(); ?>
</body>
</html>