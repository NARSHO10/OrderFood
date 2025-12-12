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

if (!$data || !isset($data['name'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$name = $data['name'];

// Remove item only for this user
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND name = ? LIMIT 1");
$stmt->bind_param("is", $userId, $name);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Item not found']);
}
$stmt->close();
?>
