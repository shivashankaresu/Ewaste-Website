<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if admin
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['admin_id'];
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check users
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] == 'user') {
            header("Location: user_dashboard.php");
        } elseif ($user['role'] == 'collector') {
            header("Location: collector_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoRecycle</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            background: linear-gradient(135deg, #4C6663, #667D79);
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background-color: #4C6663;
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
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-brand i {
            margin-right: 0.5rem;
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
            color: #FFC85C;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                height: auto;
                padding: 1rem;
            }
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 20px;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            max-height: calc(100vh - 100px);
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
        }

        /* Decorative Side */
        .decoration-side {
            flex: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            min-height: 400px;
        }

        .decoration-side::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 200, 92, 0.2), rgba(255, 184, 76, 0.1));
            z-index: -1;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 1.5rem;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .tagline {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            text-align: center;
        }

        .stats {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .stat-item {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #FFC85C;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        /* Form Side */
        .form-side {
            flex: 1;
            background-color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            max-height: calc(100vh - 100px);
        }

        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: #4C6663;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .error-message {
            background-color: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4C6663;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 102, 99, 0.2);
            border-color: #4C6663;
            background-color: white;
        }

        .login-btn {
            width: 100%;
            background-color: #FFC85C;
            color: #4C6663;
            font-weight: bold;
            font-size: 1rem;
            padding: 0.875rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .login-btn:hover {
            background-color: #FFB84C;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background-color: #e5e7eb;
        }

        .divider-text {
            padding: 0 1rem;
            color: #9ca3af;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .form-footer {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .form-footer a {
            color: #4C6663;
            font-weight: 500;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-recycle"></i>
            EcoRecycle
        </div>
        <div class="navbar-links">
            <a href="index.php">Home</a>
            <a href="awareness.php">Learn About E-Waste</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="login-container">
            <!-- Decorative Side -->
            <div class="decoration-side">
                <svg class="logo" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="90" fill="none" stroke="white" stroke-width="2" opacity="0.4"/>
                    <path d="M100,30 L70,80 L130,80 z" fill="#FFC85C"/>
                    <path d="M60,100 L30,150 L90,150 z" fill="white" opacity="0.8"/>
                    <path d="M140,100 L110,150 L170,150 z" fill="#FFC85C"/>
                    <circle cx="100" cy="100" r="25" fill="white" opacity="0.6"/>
                </svg>
                
                <h1 class="brand-title">EcoRecycle</h1>
                <p class="tagline">♻️ Making e-waste collection simple and efficient</p>
                
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Collectors</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">2 Tons</div>
                        <div class="stat-label">Recycled</div>
                    </div>
                </div>
            </div>
            
            <!-- Form Side -->
            <div class="form-side">
                <h2 class="form-title">Welcome Back</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" required class="form-input" placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" required class="form-input" placeholder="Enter your password">
                    </div>
                    
                    <button type="submit" class="login-btn">Sign In</button>
                    
                    <div class="divider">
                        <div class="divider-line"></div>
                        <span class="divider-text">or</span>
                        <div class="divider-line"></div>
                    </div>
                    
                    <div class="form-footer">
                        Don't have an account? <a href="register.php">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>