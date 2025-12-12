<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /FoodOrderingApp/ppublic/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch cart items for this user
$stmt = $conn->prepare("SELECT id, name, price, quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $total += $row['price'] * $row['quantity'];
}
$stmt->close();

// If cart is empty, redirect back
if (empty($cartItems)) {
    header('Location: /FoodOrderingApp/public/cart.php?error=empty');
    exit;
}

// Create order
$orderStmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
$orderStmt->bind_param("id", $userId, $total);
if (!$orderStmt->execute()) {
    die("Error creating order: " . $orderStmt->error);
}
$orderId = $orderStmt->insert_id;
$orderStmt->close();

// Create order_items table if not exists
$orderItemsTable = "
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;
";
$conn->query($orderItemsTable);

// Insert items into order_items
$itemStmt = $conn->prepare("INSERT INTO order_items (order_id, name, price, quantity) VALUES (?, ?, ?, ?)");
foreach ($cartItems as $item) {
    $itemStmt->bind_param("isdi", $orderId, $item['name'], $item['price'], $item['quantity']);
    $itemStmt->execute();
}
$itemStmt->close();

// Clear cart
$delStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$delStmt->bind_param("i", $userId);
$delStmt->execute();
$delStmt->close();

// Redirect to order confirmation
header("Location: /FoodOrderingApp/public/order_success.php?order_id=$orderId");
exit;
?>
