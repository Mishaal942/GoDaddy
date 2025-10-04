<?php
session_start();
require_once 'db.php';

$searchDomain = isset($_GET['domain']) ? trim($_GET['domain']) : '';
$searchDomain = preg_replace('/\.[a-zA-Z]{2,4}$/', '', $searchDomain);
$extensions = ['.com', '.net', '.org', '.info', '.biz'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Domain Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        }

        .header {
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            padding: 1.2rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cart-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            background-color: rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }

        .cart-link:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .results-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .search-summary {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .search-summary h2 {
            color: #2d3436;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .domain-result {
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .domain-result:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .domain-name {
            font-size: 1.2rem;
            color: #2d3436;
            font-weight: 600;
        }

        .domain-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #00a4a6;
            min-width: 100px;
            text-align: right;
        }

        .add-to-cart {
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-to-cart:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0,164,166,0.3);
        }

        .add-to-cart:active {
            transform: translateY(0);
        }

        .availability-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            background-color: #e3fff3;
            color: #00b894;
        }

        /* Loading animation */
        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #00a4a6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2d3436;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            display: none;
            animation: slideIn 0.3s ease;
            z-index: 1000;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .domain-result {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .domain-info {
                flex-direction: column;
                gap: 1rem;
            }

            .price {
                text-align: center;
            }

            .add-to-cart {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">Domain Finder</a>
            <a href="cart.php" class="cart-link">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 20a1 1 0 1 0 0 2 1 1 0 0 0 0-2zM20 20a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Cart
            </a>
        </div>
    </header>

    <div class="results-container">
        <div class="search-summary">
            <h2>Search Results for "<?php echo htmlspecialchars($searchDomain); ?>"</h2>
            <p>Showing available domain names with different extensions</p>
        </div>

        <?php
        foreach ($extensions as $ext) {
            $fullDomain = $searchDomain . $ext;
            $price = number_format(rand(899, 1499) / 100, 2);
            echo "<div class='domain-result'>
                    <div class='domain-name'>$fullDomain</div>
                    <div class='domain-info'>
                        <span class='availability-badge'>Available</span>
                        <div class='price'>$$price</div>
                        <button class='add-to-cart' onclick='addToCart(\"$fullDomain\", $price)'>
                            <span class='button-text'>Add to Cart</span>
                            <div class='loading'></div>
                        </button>
                    </div>
                  </div>";
        }
        ?>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    function addToCart(domain, price) {
        const button = event.currentTarget;
        const buttonText = button.querySelector('.button-text');
        const loading = button.querySelector('.loading');

        // Show loading state
        buttonText.style.display = 'none';
        loading.style.display = 'block';
        button.disabled = true;

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                domain: domain,
                price: price
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showToast(`${domain} added to cart!`);
            }
        })
        .finally(() => {
            // Reset button state
            buttonText.style.display = 'block';
            loading.style.display = 'none';
            button.disabled = false;
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html>
