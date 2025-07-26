<?php
// index.php - Main homepage for e-waste collection platform
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRecycle - Responsible E-Waste Collection</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        :root {
            /* Original 3-color theme */
            --primary-color: #3FA796; /* Teal */
            --secondary-color: #FEC868; /* Amber */
            --accent-color: #FF7878; /* Coral */
            
            /* Supporting colors */
            --dark-color: #2D3E40;
            --light-color: #F8F9FA;
            --white-color: #FFFFFF;
            --gray-color: #6C757D;
            
            /* Derived colors */
            --primary-dark: #2C7A70;
            --primary-light: #70C0B1;
            --secondary-dark: #DBA74E;
            --secondary-light: #FFDD9E;
            --accent-dark: #E05757;
            --accent-light: #FFA6A6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: var(--light-color);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        ::selection {
            background: var(--primary-light);
            color: var(--white-color);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: var(--primary-color);
            color: var(--white-color);
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(63, 167, 150, 0.2);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background-color: var(--primary-dark);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: -1;
        }

        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(63, 167, 150, 0.3);
        }

        .btn:hover:after {
            height: 100%;
        }

        .btn-outline {
            background-color: rgba(255, 255, 255, 0.2);
            border: 2px solid var(--white-color);
            color: var(--white-color);
            box-shadow: none;
        }

        .btn-outline:after {
            background-color: var(--white-color);
        }

        .btn-outline:hover {
            color: var(--primary-dark);
        }

        .btn-secondary {
            background-color:市区 var(--secondary-color);
            box-shadow: 0 4px 12px rgba(254, 200, 104, 0.2);
        }

        .btn-secondary:after {
            background-color: var(--secondary-dark);
        }

        .btn-accent {
            background-color: var(--accent-color);
            box-shadow: 0 4px 12px rgba(255, 120, 120, 0.2);
        }

        .btn-accent:after {
            background-color: var(--accent-dark);
        }

        /* Header Styles */
        header {
            background-color: var(--white-color);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        header.scrolled {
            padding: 10px 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo img {
            height: 42px;
            transition: all 0.3s ease;
        }

        header.scrolled .logo img {
            height: 36px;
        }

        .logo h1 {
            margin-left: 12px;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        header.scrolled .logo h1 {
            font-size: 22px;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 35px;
            position: relative;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
            padding: 6px 0;
        }

        .nav-links a:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a:hover:before {
            width: 100%;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .hamburger:hover {
            color: var(--primary-dark);
        }

        /* Enhanced Hero Section with dynamic animations */
        .hero {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #2C3E50 0%, #1E5631 100%); /* Dark blue-green gradient */
            color: var(--white-color);
            text-align: center;
            margin-top: 0;
            padding-top: 70px;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, 100% 92%, 0 100%);
        }

        /* Animated background pattern */
        .hero:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path fill="%233FA796" fill-opacity="0.1" d="M14 16H9v-2h5V9.87a4 4 0 1 1 2 0V14h5v2h-5v15.95A10 10 0 0 0 23.66 27l-3.46-2 8.2-2.2-2.9 5a12 12 0 0 1-21 0l-2.89-5 8.2 2.2-3.47 2A10 10 0 0 0 14 31.95V16zm40 40h-5v-2h5v-4.13a4 4 0 1 1 2 0V54h5v2h-5v15.95A10 10 0 0 0 63.66 67l-3.47-2 8.2-2.2-2.88 5a12 12 0 0 1-21.02 0l-2.88-5 8.2 2.2-3.47 2A10 10 0 0 0 54 71.95V56zm-39 6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm40-40a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM15 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm40 40a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg>');
            opacity: 0.6;
        }

        /* Animated overlay */
        .hero:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at bottom right, rgba(63, 167, 150, 0.4) 0%, rgba(44, 62, 80, 0) 70%);
            animation: pulse 8s infinite alternate;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 5;
        }

        .hero h2 {
            font-size: 52px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease-out;
            color: var(--white-color);
            letter-spacing: 1px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 0.3s;
            animation-fill-mode: both;
            text-shadow: 0 1px 8px rgba(0, 0, 0, 0.3);
            color: var(--white-color);
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            animation: fadeInUp 1s ease-out 0.6s;
            animation-fill-mode: both;
        }

        /* Floating items animations */
        .floating-item {
            position: absolute;
            z-index: 2;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
            opacity: 0.85;
            animation: float 6s ease-in-out infinite;
        }

        .item1 {
            width: 60px;
            height: 60px;
            top: 15%;
            left: 10%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.82 15.42L19.32 19.75C18.83 20.61 17.92 21.06 17 21H15V23L12.5 18.5L15 14V16H17.82L15.6 12.15L19.93 9.65L21.73 12.77C22.25 13.54 22.32 14.57 21.82 15.42M9.21 3.06L14.17 7.1L12.93 8.28L10.5 6.31V11.96L5.16 16.28L7.05 6.83L4.63 4.81L5.64 3.5C6.34 2.7 7.54 2.57 8.35 3.26L9.21 3.06M6.15 18.64C5.7 18.53 5.28 18.36 4.89 18.16L6.15 18.64Z" fill="%23FEC868"/></svg>') no-repeat center;
            animation-delay: 0s;
        }

        .item2 {
            width: 48px;
            height: 48px;
            top: 25%;
            right: 15%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" fill="%233FA796"/></svg>') no-repeat center;
            animation-delay: 1s;
        }

        .item3 {
            width: 42px;
            height: 42px;
            bottom: 30%;
            left: 15%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M7,7H17V5H19V19H5V5H7V7Z" fill="%23FF7878"/></svg>') no-repeat center;
            animation-delay: 2s;
        }

        .item4 {
            width: 55px;
            height: 55px;
            bottom: 25%;
            right: 10%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,3L2,12H5V20H19V12H22L12,3M12,8.75A2.25,2.25 0 0,1 14.25,11A2.25,2.25 0 0,1 12,13.25A2.25,2.25 0 0,1 9.75,11A2.25,2.25 0 0,1 12,8.75Z" fill="%2370C0B1"/></svg>') no-repeat center;
            animation-delay: 1.5s;
        }

        /* Animated particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
            animation: rise 15s infinite linear;
        }

        /* Animated highlighting for important words */
        .highlight {
            position: relative;
            display: inline-block;
            color: var(--secondary-color);
            font-weight: 700;
            animation: pulse-highlight 4s infinite;
        }

        .highlight:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background-color: var(--accent-color);
            opacity: 0.3;
            border-radius: 4px;
            z-index: -1;
            animation: widen 1.5s ease-in-out 1s;
        }

        /* Mascot animation */
        .mascot {
            position: absolute;
            bottom: 20px;
            right: 5%;
            width: 100px;
            height: 100px;
            z-index: 6;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.3));
            animation: bounce 4s ease-in-out infinite;
        }

        /* Animated leaf styles */
        .leaf {
            position: absolute;
            z-index: 2;
            top: -30px;
            animation: leafFall linear forwards;
            filter: drop-shadow(0px 2px 3px rgba(0, 0, 0, 0.2));
            opacity: 0.8;
            pointer-events: none;
        }

        /* Wave divider */
        .wave-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave-divider svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .wave-divider .shape-fill {
            fill: var(--light-color);
        }

        /* How It Works Section */
        .how-it-works {
            padding: 100px 0;
            background-color: var(--white-color);
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 70px;
            position: relative;
        }

        .section-title h2 {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .section-title p {
            color: var(--gray-color);
            max-width: 700px;
            margin: 20px auto 0;
            font-size: 18px;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            flex-wrap: wrap;
            position: relative;
        }

        .steps:before {
            content: '';
            position: absolute;
            top: 100px;
            left: 5%;
            width: 90%;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--primary-light), transparent);
            z-index: 1;
        }

        .step {
            flex: 1;
            min-width: 230px;
            margin: 15px;
            text-align: center;
            padding: 40px 20px;
            background-color: var(--white-color);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.5s cubic-bezier(0.215, 0.61, 0.355, 1);
            position: relative;
            z-index: 2;
            border: 1px solid rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .step:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: linear-gradient(to bottom, rgba(63, 167, 150, 0.05), transparent);
            transition: height 0.5s ease;
            z-index: -1;
        }

        .step:hover {
            transform: translateY(-15px);
            box-shadow: 0 15px 35px rgba(63, 167, 150, 0.1);
        }

        .step:hover:before {
            height: 100%;
        }

        .step-icon {
            width: 90px;
            height: 90px;
            background-color: var(--primary-light);
            color: var(--white-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 34px;
            position: relative;
            transition: all 0.5s ease;
            box-shadow: 0 10px 20px rgba(63, 167, 150, 0.15);
        }

        .step:hover .step-icon {
            transform: rotateY(360deg);
            background-color: var(--primary-color);
        }

        .step h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        .step:hover h3 {
            color: var(--primary-color);
        }

        .step p {
            color: var(--gray-color);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background-color: var(--light-color);
            position: relative;
        }

        .features:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="%233FA796" opacity="0.08"/></svg>') repeat;
            z-index: 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 30px;
            margin-top: 50px;
            position: relative;
            z-index: 1;
        }

        .feature {
            background-color: var(--white-color);
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.5s cubic-bezier(0.215, 0.61, 0.355, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(63, 167, 150, 0.1);
        }

        .feature:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.5s ease;
            transform-origin: bottom;
        }

        .feature:hover:before {
            transform: scaleY(1);
        }

        .feature-icon {
            font-size: 42px;
            color: var(--primary-color);
            margin-bottom: 25px;
            transition: all 0.5s ease;
        }

        .feature:hover .feature-icon {
            transform: scale(1.1);
            color: var(--primary-dark);
        }

        .feature h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        .feature:hover h3 {
            transform: translateX(5px);
        }

        .feature p {
            color: var(--gray-color);
            font-size: 15px;
            margin-bottom: 20px;
        }

        .feature a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .feature a i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .feature a:hover {
            color: var(--primary-dark);
        }

        .feature a:hover i {
            transform: translateX(5px);
        }

        /* Why Recycle Section */
        .why-recycle {
            padding: 100px 0;
            background-color: var(--white-color);
            position: relative;
        }

        .benefits {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 50px;
        }

        .benefit {
            flex-basis: calc(33.33% - 25px);
            min-width: 300px;
            display: flex;
            align-items: flex-start;
            background-color: var(--light-color);
            padding: 30px;
            border-radius: 12px;
            transition: all 0.5s cubic-bezier(0.215, 0.61, 0.355, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        .benefit:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(63, 167, 150, 0.05) 0%, rgba(254, 200, 104, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .benefit:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        .benefit:hover:before {
            opacity: 1;
        }

        .benefit-icon {
            font-size: 26px;
            color: var(--primary-color);
            margin-right: 20px;
            padding: 16px;
            background-color: rgba(63, 167, 150, 0.1);
            border-radius: 50%;
            transition: all 0.5s ease;
            min-width: 60px;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .benefit:hover .benefit-icon {
            background-color: var(--primary-color);
            color: var(--white-color);
            transform: rotateY(360deg);
        }

        .benefit-content {
            flex: 1;
        }

        .benefit-content h3 {
            font-size: 20px;
            margin-bottom: 12px;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        .benefit:hover .benefit-content h3 {
            color: var(--primary-color);
        }

        .benefit-content p {
            color: var(--gray-color);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        /* Awareness Section */
        .awareness {
            padding: 100px 0;
            background-color: var(--light-color);
            position: relative;
        }

        .awareness:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 300px;
            background: linear-gradient(to bottom, rgba(63, 167, 150, 0.03), transparent);
            z-index: 0;
        }

        .awareness-cards {
            display: flex;
            overflow-x: auto;
            gap: 25px;
            padding: 20px 5px;
            margin-top: 50px;
            scroll-behavior: smooth;
            position: relative;
            z-index: 1;
            -webkit-overflow-scrolling: touch;
        }

        .awareness-cards::-webkit-scrollbar {
            height: 8px;
        }

        .awareness-cards::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .awareness-cards::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }

        .awareness-card {
            flex: 0 0 auto;
            width: 320px;
            background-color: var(--white-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.5s cubic-bezier(0.215, 0.61, 0.355, 1);
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .awareness-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(63, 167, 150, 0.1);
        }

        .awareness-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .awareness-card:hover img {
            transform: scale(1.05);
        }

        .awareness-card-content {
            padding: 25px;
            position: relative;
        }

        .awareness-card h3 {
            font-size: 20px;
            margin-bottom: 12px;
            color: var(--primary-dark);
            transition: all 0.3s ease;
        }

        .awareness-card:hover h3 {
            color: var(--primary-color);
        }

        .awareness-card p {
            color: var(--gray-color);
            font-size: 15px;
            margin-bottom: 20px;
        }

        .awareness-card a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .awareness-card a i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .awareness-card a:hover {
            color: var(--primary-dark);
        }

        .awareness-card a:hover i {
            transform: translateX(5px);
        }

        .quiz-cta {
            text-align: center;
            margin-top: 60px;
            padding: 40px;
            background-color: var(--white-color);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .quiz-cta:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(63, 167, 150, 0.03) 0%, rgba(254, 200, 104, 0.03) 100%);
            z-index: -1;
        }

        .quiz-cta h3 {
            font-size: 26px;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .quiz-cta p {
            color: var(--gray-color);
            font-size: 16px;
            margin-bottom: 25px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Testimonials Section */
        .testimonials {
            padding: 100px 0;
            background-color: var(--white-color);
            position: relative;
        }

        .testimonials:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: -100px;
            background: linear-gradient(to top, rgba(63, 167, 150, 0.02), transparent);
            z-index: 0;
        }

        .testimonials-slider {
            margin-top: 50px;
            position: relative;
            overflow: hidden;
            padding: 20px 0;
        }

        .testimonial-cards {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.215, 0.61, 0.355, 1);
        }

        .testimonial-card {
            flex: 0 0 100%;
            padding: 50px 40px;
            text-align: center;
            background-color: var(--light-color);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .testimonial-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(63, 167, 150, 0.03) 0%, rgba(254, 200, 104, 0.03) 100%);
            z-index: -1;
        }

        .quote-icon {
            font-size: 36px;
            color: var(--primary-light);
            margin-bottom: 25px;
            display: inline-block;
            opacity: 0.5;
        }

        .testimonial-text {
            font-size: 20px;
            font-style: italic;
            color: var(--dark-color);
            margin-bottom: 25px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
            position: relative;
        }

        .testimonial-author {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 18px;
        }

        .testimonial-position {
            font-size: 15px;
            color: var(--gray-color);
            margin-top: 5px;
        }

        .testimonial-nav {
            display: flex;
            justify-content: center;
            margin-top: 35px;
            gap: 10px;
        }

        .testimonial-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(63, 167, 150, 0.2);
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .testimonial-dot:before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            border: 1px solid var(--primary-color);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .testimonial-dot.active {
            background-color: var(--primary-color);
            transform: scale(1.2);
        }

        .testimonial-dot.active:before {
            opacity: 1;
        }

        /* Contact/CTA Section */
        .contact-cta {
            padding: 100px 0;
            background-color: var(--primary-dark);
            color: var(--white-color);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .contact-cta:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60"><circle cx="30" cy="30" r="1" fill="%23FFFFFF" opacity="0.1"/></svg>') repeat;
            z-index: 0;
        }

        .contact-cta h2 {
            font-size: 40px;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .contact-cta p {
            font-size: 20px;
            max-width: 700px;
            margin: 0 auto 35px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .contact-cta .btn {
            background-color: var(--white-color);
            color: var(--primary-dark);
            position: relative;
            z-index: 1;
        }

        .contact-cta .btn:after {
            background-color: var(--secondary-color);
        }

        .contact-cta .btn:hover {
            color: var(--white-color);
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: var(--white-color);
            padding: 80px 0 30px;
            position: relative;
        }

        .footer-wave {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .footer-wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .footer-wave .shape-fill {
            fill: var(--white-color);
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .footer-col {
            flex: 1;
            min-width: 240px;
        }

        .footer-col h3 {
            font-size: 22px;
            margin-bottom: 25px;
            color: var(--primary-light);
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .footer-col p, .footer-col a {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .footer-col a:hover {
            color: var(--primary-light);
            transform: translateX(5px);
        }

        .footer-col i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: var, sans-serif;
            color: var(--primary-light);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .social-links a {
            color: var(--white-color);
            background-color: rgba(255, 255, 255, 0.1);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .footer-bottom a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom a:hover {
            color: var(--primary-light);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { opacity: 0.3; }
            50% { opacity: 0.6; }
            100% { opacity: 0.3; }
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }

        @keyframes rise {
            0% { 
                transform: translateY(100vh) translateX(0); 
                opacity: 0;
            }
            20% { 
                opacity: 1;
            }
            80% { 
                opacity: 1;
            }
            100% { 
                transform: translateY(-10vh) translateX(20px); 
                opacity: 0;
            }
        }

        @keyframes widen {
            0% { width: 0; }
            100% { width: 100%; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes leafFall {
            0% {
                transform: translateY(0) rotate(0deg) scale(0.8);
                opacity: 0;
            }
            10% {
                opacity: 0.8;
            }
            90% {
                opacity: 0.8;
            }
            100% {
                transform: translateY(100vh) rotate(360deg) scale(1.2);
                opacity: 0;
            }
        }

        @keyframes pulse-highlight {
            0%, 100% { color: var(--secondary-color); }
            50% { color: var(--accent-color); }
        }

        /* Add subtle gradient animation to hero background */
        .hero {
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .hero h2 {
                font-size: 46px;
            }
            
            .hero p {
                font-size: 18px;
            }
        }

        @media (max-width: 992px) {
            .hero {
                height: auto;
                padding: 120px 0 80px;
            }
            
            .steps:before {
                display: none;
            }
            
            .step {
                flex-basis: calc(50% - 30px);
                margin-bottom: 30px;
            }
            
            .benefit {
                flex-basis: calc(50% - 25px);
            }
            
            .item1, .item2, .item3, .item4 {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 70px;
                right: 0;
                width: 70%;
                height: calc(100vh - 70px);
                background-color: var(--white-color);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 50px;
                transform: translateX(100%);
                transition: transform 0.4s ease-in-out, opacity 0.3s ease;
                opacity: 0;
                visibility: hidden;
                box-shadow: -5px 0px 15px rgba(0, 0, 0, 0.1);
                z-index: 999;
            }
            
            .nav-links.active {
                transform: translateX(0);
                opacity: 1;
                visibility: visible;
            }

            .nav-links li {
                margin: 15px 0;
            }

            .hamburger {
                display: block;
            }

            .hero h2 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .section-title h2 {
                font-size: 30px;
            }
            
            .section-title p {
                font-size: 16px;
            }
            
            .footer-col {
                flex-basis: calc(50% - 20px);
            }

            .mascot {
                width: 80px;
                height: 80px;
                right: 10px;
            }
        }

        @media (max-width: 576px) {
            .step, .feature, .benefit {
                flex-basis: 100%;
            }
            
            .awareness-card {
                width: 280px;
            }
            
            .footer-col {
                flex-basis: 100%;
            }
            
            .contact-cta h2 {
                font-size: 32px;
            }
            
            .contact-cta p {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Header/Navigation -->
    <header id="header">
        <div class="container">
            <nav class="navbar">
                <a href="#home" class="logo">
                    <img src="images/logo.png" alt="EcoRecycle Logo" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 24 24\'%3E%3Cpath fill=\'%233FA796\' d=\'M21.82 15.42L19.32 19.75C18.83 20.61 17.92 21.06 17 21H15V23L12.5 18.5L15 14V16H17.82L15.6 12.15L19.93 9.65L21.73 12.77C22.25 13.54 22.32 14.57 21.82 15.42M9.21 3.06L14.17 7.1L12.93 8.28L10.5 6.31V11.96L5.16 16.28L7.05 6.83L4.63 4.81L5.64 3.5C6.34 2.7 7.54 2.57 8.35 3.26L9.21 3.06M6.15 18.64C5.7 18.53 5.28 18.36 4.89 18.16L6.15 18.64Z\'/%3E%3C/svg%3E';">
                    <h1>EcoRecycle</h1>
                </a>
                <ul class="nav-links" id="navLinks">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="awareness.php">Awareness</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="admin_dashboard.php">Admin</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Enhanced Hero Section with dynamic animations -->
    <section class="hero" id="home">
        <!-- Particle background -->
        <div class="particles">
            <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
            <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
            <div class="particle" style="left: 50%; animation-delay: 3s;"></div>
            <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
            <div class="particle" style="left: 70%; animation-delay: 0.5s;"></div>
            <div class="particle" style="left: 80%; animation-delay: 2.5s;"></div>
            <div class="particle" style="left: 90%; animation-delay: 4.5s;"></div>
        </div>

        <!-- Floating decorative elements -->
        <div class="floating-item item1"></div>
        <div class="floating-item item2"></div>
        <div class="floating-item item3"></div>
        <div class="floating-item item4"></div>

        <div class="container">
            <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
                <h2><span class="highlight">Dispose</span> Smart. <span class="highlight">Recycle</span> Right.</h2>
                <p>Join our mission to make Earth cleaner through responsible e-waste disposal. Every device recycled is a step towards a sustainable future.</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-secondary">Schedule Pickup</a>
                    <a href="#how-it-works" class="btn btn-outline">Learn More</a>
                </div>
            </div>
        </div>

        <!-- Animated mascot (fox) -->
        <!-- <div class="mascot">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="#FF7878" d="M496,128c0,73.36-51.84,104-64,104c0,0-224,32-224,160c0,0-40,14.08-48-16c-16-61.92,16-128,16-128s-39.52-27.52-40.8-83.84
                C134.72,132.8,167.36,32,256,32C344.64,32,496,30.72,496,128z"/>
                <path fill="#FEC868" d="M416,240c0,0-73.36,32-155.84,32S96,272,96,272l67.84-145.92c8.96-17.28,26.88-28.08,46.4-28.08h77.44
                c19.52,0,37.44,10.8,46.4,28.08L416,240z"/>
                <path fill="#2D3E40" d="M320,144c0,8.8-7.2,16-16,16h0c-8.8,0-16-7.2-16-16v-16c0-8.8,7.2-16,16-16h0c8.8,0,16,7.2,16,16V144z"/>
                <path fill="#2D3E40" d="M224,144c0,8.8-7.2,16-16,16h0c-8.8,0-16-7.2-16-16v-16c0-8.8,7.2-16,16-16h0c8.8,0,16,7.2,16,16V144z"/>
                <path fill="#2D3E40" d="M288,176c0,14.4-28.8,32-32,32c-3.2,0-32-17.6-32-32"/>
            </svg>
        </div> -->

        <!-- Wave divider -->
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>How It Works</h2>
                <p>Our platform makes e-waste recycling easy, transparent, and rewarding for everyone involved.</p>
            </div>

            <div class="steps">
                <div class="step" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Book Pickup</h3>
                    <p>Schedule a convenient time for collection of your e-waste through our platform.</p>
                </div>

                <div class="step" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Collector Confirms</h3>
                    <p>Our verified recyclers accept your request and prepare for collection.</p>
                </div>

                <div class="step" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>E-waste is Picked</h3>
                    <p>Collectors arrive at your location and safely pick up your electronic waste.</p>
                </div>

                <div class="step" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h3>Tracked to Recycling</h3>
                    <p>Follow your e-waste journey to proper recycling facilities in real-time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="services">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Platform Features</h2>
                <p>Discover how our platform makes e-waste recycling convenient, transparent, and rewarding.</p>
            </div>

            <div class="features-grid">
                <div class="feature" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Schedule Pickup</h3>
                    <p>Easily schedule e-waste collection at your convenience with just a few clicks.</p>
                    <a href="#schedule">Schedule Now <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="feature" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Collector Dashboard</h3>
                    <p>Specialized dashboard for collectors to manage pickups and optimize routes.</p>
                    <a href="collector-login.php">Login as Collector <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="feature" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Track Requests</h3>
                    <p>Real-time tracking of your e-waste from pickup to recycling facility.</p>
                    <a href="user-login.php">Track Your Waste <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="feature" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Reward Points System</h3>
                    <p>Earn points for every recycling action and redeem them for eco-friendly rewards.</p>
                    <a href="rewards.php">View Rewards <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="feature" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Awareness Content</h3>
                    <p>Educational resources about the importance of proper e-waste disposal.</p>
                    <a href="#awareness">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="feature" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>Educational Quiz</h3>
                    <p>Test your knowledge about e-waste and learn while earning more reward points.</p>
                    <a href="quiz.php">Take Quiz <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Recycle Section -->
    <section class="why-recycle" id="about">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why Recycle E-Waste?</h2>
                <p>Electronic waste is the fastest-growing waste stream globally. Here's why responsible recycling matters.</p>
            </div>

            <div class="benefits">
                <div class="benefit" data-aos="fade-up" data-aos-delay="100">
                    <div class="benefit-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Reduce Pollution</h3>
                        <p>Prevents toxic substances like lead and mercury from contaminating soil and water.</p>
                    </div>
                </div>

                <div class="benefit" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Recover Rare Metals</h3>
                        <p>Reclaims valuable materials like gold, silver, and rare earth elements from discarded devices.</p>
                    </div>
                </div>

                <div class="benefit" data-aos="fade-up" data-aos-delay="300">
                    <div class="benefit-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Safe Disposal</h3>
                        <p>Ensures hazardous components are handled properly to protect both people and ecosystems.</p>
                    </div>
                </div>

                <div class="benefit" data-aos="fade-up" data-aos-delay="400">
                    <div class="benefit-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Energy Conservation</h3>
                        <p>Recycling materials uses significantly less energy than extracting and processing new materials.</p>
                    </div>
                </div>

                <div class="benefit" data-aos="fade-up" data-aos-delay="500">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Economic Benefits</h3>
                        <p>Creates green jobs and contributes to a circular economy with sustainable resource management.</p>
                    </div>
                </div>

                <div class="benefit" data-aos="fade-up" data-aos-delay="600">
                    <div class="benefit-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Data Security</h3>
                        <p>Proper recycling ensures your personal data is completely wiped from old devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Awareness Section -->
    <section class="awareness" id="awareness">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Awareness & Education</h2>
                <p>Expand your knowledge about e-waste management and environmental conservation.</p>
            </div>

            <div class="awareness-cards">
                <div class="awareness-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="images/ewaste-impact.jpg" alt="E-waste Impact" onerror="this.src='2.png';">
                    <div class="awareness-card-content">
                        <h3>Environmental Impact</h3>
                        <p>Learn how improper e-waste disposal affects our environment and ecosystems.</p>
                        <a href="articles/environmental-impact.php">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="awareness-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="images/circular-economy.jpg" alt="Circular Economy" onerror="this.src='3.png'">
                    <div class="awareness-card-content">
                        <h3>Circular Economy</h3>
                        <p>Discover how electronic recycling contributes to a sustainable circular economy.</p>
                        <a href="articles/circular-economy.php">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="awareness-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="images/recycling-process.jpg" alt="Recycling Process" onerror="this.src='4.png';">
                    <div class="awareness-card-content">
                        <h3>Recycling Process</h3>
                        <p>Follow the journey of your e-waste from collection to recycling and recovery.</p>
                        <a href="articles/recycling-process.php">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="awareness-card" data-aos="fade-up" data-aos-delay="400">
                    <img src="images/tips.jpg" alt="Eco Tips" onerror="this.src='5.png'">
                    <div class="awareness-card-content">
                        <h3>Eco-Friendly Tips</h3>
                        <p>Simple ways to reduce e-waste generation and extend the lifespan of your devices.</p>
                        <a href="articles/eco-tips.php">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="quiz-cta" data-aos="fade-up">
                <h3>Test Your Knowledge</h3>
                <p>Take our interactive quiz and earn reward points while learning about e-waste.</p>
                <a href="quiz.php" class="btn btn-accent">Take the Quiz</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>What Our Users Say</h2>
                <p>Hear from people who are making a difference by using our platform.</p>
            </div>

            <div class="testimonials-slider" data-aos="fade-up">
                <div class="testimonial-cards" id="testimonialCards">
                    <div class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="testimonial-text">This platform made recycling my old electronics so easy! I scheduled a pickup, and everything was handled professionally. Plus, I earned reward points that I used toward eco-friendly products!</p>
                        <p class="testimonial-author">Sarah Johnson</p>
                        <p class="testimonial-position">Regular User</p>
                    </div>

                    <div class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="testimonial-text">As a small business owner, I was looking for a responsible way to dispose of our old equipment. EcoRecycle provided the perfect solution with their transparent tracking system. Now I can see exactly where our e-waste ends up.</p>
                        <p class="testimonial-author">Michael Chen</p>
                        <p class="testimonial-position">Business Owner</p>
                    </div>

                    <div class="testimonial-card">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="testimonial-text">I joined as a collector six months ago, and the platform has made route planning and pickup scheduling incredibly efficient. The dashboard is intuitive, and the customers are engaged in the process.</p>
                        <p class="testimonial-author">David Rodriguez</p>
                        <p class="testimonial-position">E-waste Collector</p>
                    </div>
                </div>

                <div class="testimonial-nav" id="testimonialNav">
                    <div class="testimonial-dot active" data-index="0"></div>
                    <div class="testimonial-dot" data-index="1"></div>
                    <div class="testimonial-dot" data-index="2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact/CTA Section -->
    <section class="contact-cta" id="schedule">
        <div class="container">
            <div data-aos="fade-up">
                <h2>Ready to Recycle Your E-Waste?</h2>
                <p>Join thousands of environmentally conscious individuals and businesses making a difference through responsible e-waste recycling.</p>
                <a href="schedule-pickup.php" class="btn">Schedule a Pickup Now</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-wave">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>About EcoRecycle</h3>
                    <p>We're on a mission to revolutionize e-waste management through technology, education, and community engagement.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <a href="#home"><i class="fas fa-home"></i> Home</a>
                    <a href="#about"><i class="fas fa-info-circle"></i> About</a>
                    <a href="#services"><i class="fas fa-cogs"></i> Services</a>
                    <a href="awareness"><i class="fas fa-lightbulb"></i> Awareness</a>
                    <a href="schedule-pickup.php"><i class="fas fa-calendar-check"></i> Schedule Pickup</a>
                </div>

                <div class="footer-col">
                    <h3>Services</h3>
                    <a href="#services"><i class="fas fa-recycle"></i> E-Waste Collection</a>
                    <a href="#services"><i class="fas fa-tachometer-alt"></i> Collector Dashboard</a>
                    <a href="#services"><i class="fas fa-map-marked-alt"></i> Waste Tracking</a>
                    <a href="rewards.php"><i class="fas fa-trophy"></i> Rewards Program</a>
                    <a href="quiz.php"><i class="fas fa-question-circle"></i> Educational Quiz</a>
                </div>

                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <a href="mailto:support@ecorecycle.com"><i class="fas fa-envelope"></i> support@ecorecycle.com</a>
                    <a href="tel:+1234567890"><i class="fas fa-phone"></i> +1 (234) 567-890</a>
                    <a href="#"><i class="fas fa-map-marker-alt"></i> 123 Green Lane, Eco City, EC 12345</a>
                    <a href="contact.php"><i class="fas fa-comment"></i> Contact Form</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 EcoRecycle. All rights reserved.</p>
                <p>Designed with <i class="fas fa-heart"></i> for a greener planet | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- AOS Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Font Awesome (for fallback if CDN fails) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });

            // Header Scroll Effect
            const header = document.getElementById('header');
            const handleScroll = () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            };
            window.addEventListener('scroll', handleScroll);

            // Mobile Navigation Toggle
            const hamburger = document.getElementById('hamburger');
            const navLinks = document.getElementById('navLinks');
            const toggleNav = () => {
                navLinks.classList.toggle('active');
                hamburger.querySelector('i').classList.toggle('fa-bars');
                hamburger.querySelector('i').classList.toggle('fa-times');
            };
            hamburger.addEventListener('click', toggleNav);

            // Close mobile nav when a link is clicked
            navLinks.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (navLinks.classList.contains('active')) {
                        toggleNav();
                    }
                });
            });

            // Smooth Scroll for Anchor Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = anchor.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Testimonials Slider
            const testimonialCards = document.getElementById('testimonialCards');
            const testimonialDots = document.querySelectorAll('.testimonial-dot');
            let currentTestimonial = 0;

            const updateTestimonial = (index) => {
                testimonialCards.style.transform = `translateX(-${index * 100}%)`;
                testimonialDots.forEach(dot => dot.classList.remove('active'));
                testimonialDots[index].classList.add('active');
                currentTestimonial = index;
            };

            testimonialDots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const index = parseInt(dot.getAttribute('data-index'));
                    updateTestimonial(index);
                });
            });

            // Auto-slide every 5 seconds
            let autoSlide = setInterval(() => {
                currentTestimonial = (currentTestimonial + 1) % testimonialDots.length;
                updateTestimonial(currentTestimonial);
            }, 5000);

            // Pause auto-slide on hover
            testimonialCards.addEventListener('mouseenter', () => clearInterval(autoSlide));
            testimonialCards.addEventListener('mouseleave', () => {
                autoSlide = setInterval(() => {
                    currentTestimonial = (currentTestimonial + 1) % testimonialDots.length;
                    updateTestimonial(currentTestimonial);
                }, 5000);
            });

            // Enhanced Hero Section Animations
            const particlesContainer = document.querySelector('.particles');

            // Create additional dynamic particles
            const createParticle = () => {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 10}s`;
                particle.style.animationDuration = `${10 + Math.random() * 10}s`;
                particlesContainer.appendChild(particle);

                // Remove particle after animation completes
                particle.addEventListener('animationend', () => {
                    particle.remove();
                });
            };

            // Generate initial particles and keep creating them
            for (let i = 0; i < 10; i++) {
                createParticle();
            }
            setInterval(createParticle, 2000); // Add new particle every 2 seconds

            // Create falling leaves
            const createLeaf = () => {
                const leaf = document.createElement('div');
                leaf.classList.add('leaf');
                leaf.style.left = `${Math.random() * 100}%`;
                leaf.style.animationDuration = `${4 + Math.random() * 4}s`; // 4-8 seconds
                leaf.style.animationDelay = `${Math.random() * 5}s`;

                // Use inline SVG for leaf
                leaf.innerHTML = `
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12 6C10.34 6 9 7.34 9 9C9 10.66 10.34 12 12 12C13.66 12 15 10.66 15 9C15 7.34 13.66 6 12 6Z" fill="#3FA796"/>
                    </svg>
                `;
                document.querySelector('.hero').appendChild(leaf);

                // Remove leaf after animation
                leaf.addEventListener('animationend', () => {
                    leaf.remove();
                });
            };

            // Generate initial leaves and keep creating them
            for (let i = 0; i < 5; i++) {
                createLeaf();
            }
            setInterval(createLeaf, 3000); // Add new leaf every 3 seconds
        });
    </script>
</body>
</html>
                   