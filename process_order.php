<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Calculate total
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'];
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert order (assuming user is logged in, using 1 as default user_id)
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (1, ?)");
        $stmt->execute([$total]);
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        $pdo->commit();
        
        // Redirect to thank you page
        header('Location: thank_you.php');
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error processing order: " . $e->getMessage();
    }
} else {
    header('Location: cart.php');
    exit();
}
?>
