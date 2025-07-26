<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $phone]);
        $user_id = $pdo->lastInsertId();

        if ($role == 'collector') {
            header("Location: collector_details.php?user_id=$user_id");
        } else {
            header("Location: login.php");
        }
        exit;
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EcoRecycle</title>
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
            overflow: auto; /* Allow page-level scrolling */
        }

        /* Navbar (handled by header.php, but override for distinction) */
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
            margin-top: 80px;
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
            margin-bottom: 1.5rem;
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

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus {
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

        .login-link {
            text-align: center;
            font-size: 0.875rem;
            color: var(--secondary);
            margin-top: 1rem;
        }

        .login-link a {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .login-link a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        /* Animations */
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

        @keyframes logoFade {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .branding-column {
                padding: 1.5rem;
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

            .form-column {
                padding: 1.5rem;
            }

            .form-container {
                padding: 1.5rem;
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
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <a href="index.php">Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Register</span>
        </div>

        <!-- Split layout -->
        <div class="split-container">
            <!-- Branding column -->
            <div class="branding-column">
                <!-- New SVG Logo -->
                <svg class="eco-logo" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="150" cy="150" r="130" fill="none" stroke="white" stroke-width="3" opacity="0.4"/>
                    <path d="M100,180 Q150,100 200,180 T250,180" fill="none" stroke="#FFC85C" stroke-width="8" stroke-linecap="round"/>
                    <path d="M120,200 Q150,140 180,200 T220,200" fill="none" stroke="white" stroke-width="6" stroke-linecap="round" opacity="0.8"/>
                    <circle cx="150" cy="150" r="40" fill="#4C6663"/>
                    <path d="M135,145 A20,20 0 0,1 165,145 A20,20 0 0,1 135,145 Z" fill="#FFC85C"/>
                </svg>
                
                <h1 class="brand-title">EcoRecycle</h1>
                <p class="tagline">♻️ Empowering Green Futures – One Pickup at a Time.</p>
            </div>
            
            <!-- Form column -->
            <div class="form-column">
                <div class="form-container">
                    <h2 class="form-title">Register</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="error-message">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" required class="form-input" placeholder="Enter your full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" required class="form-input" placeholder="your@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" required class="form-input" placeholder="Create a strong password">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-input" placeholder="Your phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" required class="form-select">
                                <option value="user">User</option>
                                <option value="collector">Collector</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="submit-button">Register</button>
                        
                        <p class="login-link">
                            Already have an account? <a href="login.php">Login here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

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