<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = [
    'domain' => $data['domain'],
    'price' => $data['price']
];

echo json_encode(['success' => true]);
?>
