<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Domain Finder</title>
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
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            padding: 1.2rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
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

        .cart-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .cart-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #2d3436;
        }

        .cart-items {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #eee;
        }

        .domain-name {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #00a4a6;
        }

        .remove-button {
            background: #ff7675;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-button:hover {
            background: #d63031;
        }

        .cart-summary {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-top: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .checkout-button {
            background: linear-gradient(135deg, #00a4a6 0%, #006d6f 100%);
            color: white;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,164,166,0.3);
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .empty-cart h2 {
            color: #2d3436;
            margin-bottom: 1rem;
        }

        .continue-shopping {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.8rem 1.5rem;
            background: #00a4a6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .continue-shopping:hover {
            background: #008486;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .cart-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">Domain Finder</a>
        </div>
    </header>

    <div class="cart-container">
        <h1 class="cart-title">Shopping Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Add some domains to get started!</p>
                <a href="index.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $total += $item['price'];
                ?>
                    <div class="cart-item">
                        <div class="domain-name"><?php echo htmlspecialchars($item['domain']); ?></div>
                        <div class="price">$<?php echo number_format($item['price'], 2); ?></div>
                        <button class="remove-button" onclick="removeFromCart('<?php echo $item['domain']; ?>')">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <button class="checkout-button" onclick="window.location.href='checkout.php'">
                    Proceed to Checkout
                </button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function removeFromCart(domain) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    domain: domain
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
