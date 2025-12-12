<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$orderId = $_GET['order_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <main class="container my-5">
        <div class="text-center">
            <h1 class="mb-4">🎉 Order Placed Successfully!</h1>
            <?php if ($orderId): ?>
                <p>Your order ID is <strong><?php echo htmlspecialchars($orderId); ?></strong>.</p>
            <?php else: ?>
                <p>Order confirmed, but no ID was provided.</p>
            <?php endif; ?>
            <div class="mt-4">
                <a href="menu.php" class="btn btn-primary">Back to Menu</a>
                <a href="cart.php" class="btn btn-secondary">View Cart</a>
            </div>
        </div>
    </main>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center small">
            &copy; <?php echo date('Y'); ?> FoodOrder. All rights reserved.
        </div>
    </footer>
</body>
</html>
