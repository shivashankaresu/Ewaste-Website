<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_GET['user_id'])) {
    header("Location: register.php");
    exit;
}

$user_id = $_GET['user_id'];

// Verify user is a collector
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user || $user['role'] != 'collector') {
    header("Location: register.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $organization = filter_input(INPUT_POST, 'organization', FILTER_SANITIZE_STRING);
    $pickup_radius = filter_input(INPUT_POST, 'pickup_radius', FILTER_SANITIZE_NUMBER_INT);

    try {
        $stmt = $pdo->prepare("INSERT INTO collectors (user_id, location, organization, pickup_radius) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $location, $organization, $pickup_radius]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Failed to save details: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Details - EcoRecycle</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4C6663;
            --secondary: #667D79;
            --accent: #FFC85C;
            --light: #f5f5f5;
            --dark: #333;
            --danger: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        html, body {
            min-height: 100vh;
            background-color: #f0f2f5;
            overflow: auto;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(to right, #4C6663, #3a4f4d);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 80px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .navbar-brand i {
            margin-right: 0.5rem;
            font-size: 1.5rem;
        }

        .navbar-links {
            display: flex;
            gap: 1.5rem;
        }

        .navbar-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .navbar-links a:hover {
            color: var(--accent);
        }

        /* Main container */
        .container {
            margin-top: 20px;
            padding: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Breadcrumbs */
        .breadcrumbs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: var(--secondary);
        }

        .breadcrumbs a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumbs a:hover {
            color: var(--accent);
        }

        .breadcrumbs i {
            color: var(--secondary);
            font-size: 0.8rem;
        }

        /* Split layout */
        .split-container {
            display: flex;
            flex-direction: column;
            background-color: #f9fafb;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .split-container {
                flex-direction: row;
            }
        }

        /* Branding column */
        .branding-column {
            width: 100%;
            background: linear-gradient(135deg, #667D79, #829796);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            color: white;
            text-align: center;
            animation: fadeIn 0.8s ease forwards;
        }

        @media (min-width: 768px) {
            .branding-column {
                width: 50%;
                min-height: calc(100vh - 80px);
            }
        }

        .eco-logo {
            width: 100%;
            max-width: 250px;
            margin-bottom: 2rem;
            animation: logoFade 1s ease forwards;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .tagline {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .collector-info {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            padding: 1.5rem;
            max-width: 90%;
            margin-top: 1rem;
        }

        .collector-info-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .collector-benefit {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .benefit-icon {
            background-color: rgba(255, 200, 92, 0.3);
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        /* Form column */
        .form-column {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background-color: white;
        }

        @media (min-width: 768px) {
            .form-column {
                width: 50%;
            }
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .form-container.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .form-title::after {
            content: "";
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background-color: var(--accent);
            border-radius: 2px;
        }

        .form-subtitle {
            text-align: center;
            color: var(--secondary);
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .error-message {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            border: 1px solid var(--danger);
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .form-group.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.375rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(76, 102, 99, 0.2);
        }

        .submit-button {
            display: block;
            width: 100%;
            background: linear-gradient(to right, var(--accent), #FFB84C);
            color: var(--primary);
            font-weight: bold;
            font-size: 1rem;
            padding: 0.75rem;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .submit-button:hover {
            background: linear-gradient(to right, #FFB84C, #FFA63C);
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .help-text {
            font-size: 0.75rem;
            color: var(--secondary);
            margin-top: 0.25rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes logoFade {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                height: auto;
                padding: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .branding-column {
                padding: 1.5rem;
                min-height: auto;
            }

            .eco-logo {
                max-width: 200px;
            }

            .brand-title {
                font-size: 2rem;
            }

            .tagline {
                font-size: 1rem;
            }

            .collector-info {
                padding: 1rem;
            }

            .form-column {
                padding: 1.5rem;
            }

            .form-container {
                padding: 1.5rem;
                min-width: 300px;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .breadcrumbs {
                font-size: 0.8rem;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <a href="index.php">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="register.php">Register</a>
            <i class="fas fa-chevron-right"></i>
            <span>Collector Details</span>
        </div>

        <!-- Split layout -->
        <div class="split-container">
            <!-- Branding column -->
            <div class="branding-column">
                <svg class="eco-logo" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="150" cy="150" r="130" fill="none" stroke="white" stroke-width="3" opacity="0.4"/>
                    <path d="M90,190 L150,90 L210,190" fill="none" stroke="#FFC85C" stroke-width="8" stroke-linecap="round"/>
                    <path d="M210,190 L150,90 L90,190" fill="none" stroke="white" stroke-width="8" stroke-linecap="round" opacity="0.8"/>
                    <path d="M90,190 L210,190 L150,90 Z" fill="none" stroke="#4C6663" stroke-width="8" stroke-linecap="round"/>
                    <circle cx="150" cy="150" r="30" fill="#4C6663" opacity="0.6"/>
                    <path d="M140,145 L160,145 L150,165 Z" fill="#FFC85C"/>
                </svg>
                
                <h1 class="brand-title">EcoRecycle</h1>
                <p class="tagline">♻️ Empowering Green Futures – One Pickup at a Time.</p>
                
                <div class="collector-info">
                    <h3 class="collector-info-title">Collector Benefits</h3>
                    <div class="collector-benefit">
                        <div class="benefit-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                        </div>
                        <span>Access to our network of eco-conscious users</span>
                    </div>
                    <div class="collector-benefit">
                        <div class="benefit-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                        </div>
                        <span>Manage pick-up requests and schedules</span>
                    </div>
                    <div class="collector-benefit">
                        <div class="benefit-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                        </div>
                        <span>Track your environmental impact metrics</span>
                    </div>
                </div>
            </div>
            
            <!-- Form column -->
            <div class="form-column">
                <div class="form-container">
                    <h2 class="form-title">Collector Details</h2>
                    <p class="form-subtitle">Complete your profile to start collecting e-waste</p>
                    
                    <?php if (isset($error)): ?>
                        <div class="error-message">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="organization" class="form-label">Organization Name</label>
                            <input type="text" id="organization" name="organization" required class="form-input" placeholder="Your company or organization name">
                        </div>
                        
                        <div class="form-group">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" required class="form-input" placeholder="Your operating location">
                            <p class="help-text">Enter your primary city or area of operation</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="pickup_radius" class="form-label">Pickup Radius (km)</label>
                            <input type="number" id="pickup_radius" name="pickup_radius" required class="form-input" placeholder="e.g., 10">
                            <p class="help-text">Maximum distance you can travel for pickups</p>
                        </div>
                        
                        <button type="submit" class="submit-button">Complete Registration</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form and input animations
        document.addEventListener('DOMContentLoaded', function() {
            const formContainer = document.querySelector('.form-container');
            const formGroups = document.querySelectorAll('.form-group');

            setTimeout(() => {
                formContainer.classList.add('visible');
            }, 100);

            formGroups.forEach((group, index) => {
                setTimeout(() => {
                    group.classList.add('visible');
                }, 200 + index * 100);
            });
        });
    </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>