<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Domain Finder</title>
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

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .checkout-form {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .order-summary {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: #2d3436;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f1f1;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2d3436;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00a4a6;
            box-shadow: 0 0 0 3px rgba(0,164,166,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .domain-name {
            font-weight: 500;
        }

        .price {
            font-weight: 600;
            color: #00a4a6;
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 2px solid #f1f1f1;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .submit-button {
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
            margin-top: 2rem;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,164,166,0.3);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: #00a4a6;
        }

        .payment-method.active {
            border-color: #00a4a6;
            background-color: rgba(0,164,166,0.1);
        }

        .payment-method img {
            height: 30px;
            margin-bottom: 0.5rem;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #00a4a6;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .secure-badge svg {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 968px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                grid-template-columns: 1fr;
            }
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
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">Domain Finder</a>
        </div>
    </header>

    <div class="checkout-container">
        <div class="checkout-form">
            <h2 class="section-title">Billing Information</h2>
            <form id="payment-form" action="process_order.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="address">Street Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="zipCode">ZIP Code</label>
                        <input type="text" id="zipCode" name="zipCode" required>
                    </div>
                </div>

                <h2 class="section-title">Payment Method</h2>
                <div class="payment-methods">
                    <div class="payment-method active">
                        <img src="https://cdn-icons-png.flaticon.com/512/349/349221.png" alt="Credit Card">
                        <span>Credit Card</span>
                    </div>
                    <div class="payment-method">
                        <img src="https://cdn-icons-png.flaticon.com/512/174/174861.png" alt="PayPal">
                        <span>PayPal</span>
                    </div>
                    <div class="payment-method">
                        <img src="https://cdn-icons-png.flaticon.com/512/5968/5968416.png" alt="Apple Pay">
                        <span>Apple Pay</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" name="cardNumber" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="expiryDate">Expiry Date</label>
                        <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" required>
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    <span>Complete Purchase</span>
                    <div class="loading"></div>
                </button>

                <div class="secure-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 6c1.4 0 2.5 1.1 2.5 2.5V13c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1h-5c-.6 0-1-.4-1-1v-4c0-.6.4-1 1-1V9.5C9.5 8.1 10.6 7 12 7zm0 2c-.3 0-.5.2-.5.5V13h1V9.5c0-.3-.2-.5-.5-.5z"/>
                    </svg>
                    Secure Checkout
                </div>
            </form>
        </div>

        <div class="order-summary">
            <h2 class="section-title">Order Summary</h2>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $total += $item['price'];
            ?>
                <div class="order-item">
                    <div class="domain-name"><?php echo htmlspecialchars($item['domain']); ?></div>
                    <div class="price">$<?php echo number_format($item['price'], 2); ?></div>
                </div>
            <?php endforeach; ?>

            <div class="order-total">
                <span>Total</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
        </div>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', () => {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
                method.classList.add('active');
            });
        });

        // Form submission
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const button = document.querySelector('.submit-button');
            const buttonText = button.querySelector('span');
            const loading = button.querySelector('.loading');

            buttonText.style.display = 'none';
            loading.style.display = 'block';
            button.disabled = true;

            // Simulate form submission (replace with actual submission)
            setTimeout(() => {
                window.location.href = 'thank_you.php';
            }, 2000);
        });
    </script>
</body>
</html>
