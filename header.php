<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Waste Pickup & Recycling Assistant</title>
    <link href="../css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="p-4 text-green-700 shadow-md">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-3 md:mb-0">
                <i class="fas fa-recycle text-green-600 text-2xl mr-2"></i>
                <a href="index.php" class="text-xl font-bold text-green-600 hover:text-green-700 transition duration-300">E-Waste Assistant</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="index.php" class="hover:text-green-600 transition duration-300">Home</a>
                <a href="awareness.php" class="hover:text-green-600 transition duration-300">Learn About E-Waste</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-green-600 border border-green-600 px-4 py-2 rounded-lg hover:bg-green-600 hover:text-white transition duration-300">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <a href="register.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- Main content will go here -->