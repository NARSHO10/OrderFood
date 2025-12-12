<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../config/db.php'; // $conn from db.php

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}
$userId = $_SESSION['user_id'];

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name'], $data['price'], $data['quantity'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$name     = $data['name'];
$price    = (float)$data['price'];
$quantity = (int)$data['quantity'];

// Create cart table if not exists
$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB";
$conn->query($sql);

// Insert item into cart
$stmt = $conn->prepare("INSERT INTO cart (user_id, name, price, quantity) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isdi", $userId, $name, $price, $quantity);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close();
?>
