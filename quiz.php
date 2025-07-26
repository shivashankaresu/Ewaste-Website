<?php
include 'includes/header.php';
include 'includes/db_connect.php';

$questions = $pdo->query("SELECT * FROM quiz_questions")->fetchAll();

// Debug: Uncomment to check $questions (remove in production)
// echo '<pre>'; var_dump($questions); echo '</pre>'; die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRecycle - E-Waste Quiz</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        html, body {
            min-height: 100vh;
            background-color: #f0f2f5;
            color: var(--dark);
            overflow: auto;
        }

        .quiz-container {
            max-width: 800px;
            margin: 80px auto 2rem;
            padding: 2rem;
            max-height: calc(100vh - 80px - 120px);
            overflow-y: auto;
            scrollbar-width: none;
        }

        .quiz-container::-webkit-scrollbar {
            display: none;
        }

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

        .quiz-box {
            background: linear-gradient(145deg, #ffffff, #f9f9f9);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease forwards;
        }

        .quiz-title {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .quiz-title::after {
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

        #question {
            font-size: 1.5rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        #options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .option {
            background-color: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            color: var(--dark);
        }

        .option:hover {
            background-color: rgba(76, 102, 99, 0.1);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .option.selected {
            background-color: rgba(255, 200, 92, 0.2);
            border-color: var(--accent);
            font-weight: 500;
        }

        /* Alternative: Radio input styles (uncomment if needed) */
        /*
        .option {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .option input {
            margin-right: 0.5rem;
        }
        .option:hover {
            background-color: rgba(76, 102, 99, 0.1);
            border-color: var(--primary);
        }
        .option input:checked + span {
            background-color: rgba(255, 200, 92, 0.2);
            border-color: var(--accent);
            font-weight: 500;
        }
        */

        #next {
            display: none;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: block;
        }

        #next:hover {
            background: linear-gradient(to right, #3a4f4d, #556b67);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #next:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #result {
            display: none;
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--primary);
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background-color: rgba(76, 102, 99, 0.1);
            animation: fadeIn 0.5s ease forwards;
        }

        .error-message {
            font-size: 1.25rem;
            color: var(--danger);
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background-color: rgba(220, 53, 69, 0.1);
        }

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

        @media (max-width: 768px) {
            .quiz-container {
                padding: 1rem;
                margin: 80px 1rem 2rem;
            }

            .quiz-title {
                font-size: 1.5rem;
            }

            #question {
                font-size: 1.25rem;
            }

            .option {
                font-size: 0.9rem;
                padding: 0.75rem;
            }

            #next {
                padding: 0.5rem 1.5rem;
                font-size: 0.9rem;
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

    <div class="quiz-container">
        <div class="breadcrumbs">
            <a href="index.php">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="user_dashboard.php">Dashboard</a>
            <i class="fas fa-chevron-right"></i>
            <span>Quiz</span>
        </div>

        <div class="quiz-box">
            <h2 class="quiz-title">E-Waste Quiz</h2>
            <?php if (empty($questions)): ?>
                <div class="error-message">
                    No questions available. Please check the database or contact support.
                </div>
            <?php else: ?>
                <div id="quiz">
                    <div id="question"></div>
                    <div id="options" class="options"></div>
                    <button id="next">Next</button>
                    <div id="result"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <?php if (!empty($questions)): ?>
        <script src="js/quiz.js?<?php echo time(); ?>"></script>
    <?php endif; ?>
</body>
</html>