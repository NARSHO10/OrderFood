<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../config/db.php'; // $conn from db.php (mysqli OOP)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /FoodOrderingApp/public/register.php');
    exit;
}

$name = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    header('Location: /FoodOrderingApp/public/register.php?error=missing');
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /FoodOrderingApp/public/register.php?error=invalid_email');
    exit;
}
if ($password !== $confirm) {
    header('Location: /FoodOrderingApp/public/register.php?error=password_mismatch');
    exit;
}

// Check if email already exists
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    header('Location: /FoodOrderingApp/public/register.php?error=email_taken');
    exit;
}
$stmt->close();

// Insert new user
$hash = password_hash($password, PASSWORD_DEFAULT);
$ins = $conn->prepare('INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())');
$ins->bind_param('sss', $name, $email, $hash);
$ok = $ins->execute();
if (!$ok) {
    // log $ins->error in real app
    $ins->close();
    header('Location: /FoodOrderingApp/public/register.php?error=server');
    exit;
}
$userId = $ins->insert_id;
$ins->close();

// Set session and redirect to dashboard
$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $name;
header('Location: /FoodOrderingApp/public/Dashboard.php');
exit;
?>