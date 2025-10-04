<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Finder - Find & Register Your Perfect Domain Name</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #2d3436;
            min-height: 100vh;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .hero {
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .hero-content {
            max-width: 800px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 4rem;
            color: white;
            margin-bottom: 2rem;
            font-weight: 800;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-container {
            background: white;
            padding: 0.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            display: flex;
            margin: 0 1rem;
        }

        .search-box {
            flex: 1;
            padding: 1.2rem 1.5rem;
            font-size: 1.2rem;
            border: none;
            border-radius: 12px;
            background: transparent;
        }

        .search-box:focus {
            outline: none;
        }

        .search-button {
            padding: 1.2rem 2.5rem;
            font-size: 1.1rem;
            background: #2d3436;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background: #1e2527;
            transform: translateY(-1px);
        }

        .features {
            padding: 6rem 2rem;
            background: white;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2d3436;
        }

        .feature-description {
            color: #636e72;
            line-height: 1.6;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 20s infinite;
        }

        .shape:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 20%;
            right: 15%;
            animation-delay: 5s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 10s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
                padding: 0 1rem;
            }

            .search-container {
                flex-direction: column;
                gap: 1rem;
            }

            .search-box, .search-button {
                width: 100%;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animated background gradient */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero {
            background: linear-gradient(-45deg, #00a4a6, #006d6f, #004f51, #003638);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">DOMAIN FINDER</a>
        </div>
    </header>

    <main class="hero">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="hero-content">
            <h1>Find your perfect domain name</h1>
            <div class="search-container">
                <input type="text" class="search-box" placeholder="Enter your domain name idea..." id="domainSearch">
                <button class="search-button" onclick="searchDomain()">Search Domains</button>
            </div>
        </div>
    </main>

    <section class="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3 class="feature-title">Smart Search</h3>
                <p class="feature-description">Find the perfect domain name with our intelligent search system that suggests the best options for your brand.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3 class="feature-title">Best Prices</h3>
                <p class="feature-description">Get competitive pricing on all domain names with our price-match guarantee and transparent pricing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3 class="feature-title">Secure Registration</h3>
                <p class="feature-description">Register your domain with confidence using our secure and trusted domain registration system.</p>
            </div>
        </div>
    </section>

    <script>
        function searchDomain() {
            const domain = document.getElementById('domainSearch').value.trim();
            if (domain) {
                window.location.href = `search.php?domain=${encodeURIComponent(domain)}`;
            }
        }

        // Allow Enter key to trigger search
        document.getElementById('domainSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchDomain();
            }
        });
    </script>
</body>
</html>
