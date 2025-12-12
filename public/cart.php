<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /FoodOrderingApp/public/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$cartItems = [];
$total = 0;

// Fetch cart items from DB
$stmt = $conn->prepare("SELECT name, price, quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodOrder — Cart</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <main class="container my-5">
        <h1 class="text-center mb-4">Your Cart</h1>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="width:110px">Price</th>
                            <th style="width:100px">Quantity</th>
                            <th style="width:110px">Total</th>
                            <th style="width:110px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cartItems)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Your cart is empty.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cartItems as $item):
                                $itemTotal = $item['price'] * $item['quantity'];
                                $total += $itemTotal;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo (int)$item['quantity']; ?></td>
                                    <td>$<?php echo number_format($itemTotal, 2); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeFromCart('<?php echo addslashes($item['name']); ?>')">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <h4 class="text-end">Total: $<?php echo number_format($total, 2); ?></h4>
                <div class="text-end mt-3">
                    <?php if (!empty($cartItems)): ?>
                        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center small">
            &copy; <?php echo date('Y'); ?> FoodOrder. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- App script -->
    <script src="/FoodOrderingApp/public/script.js"></script>
</body>
</html>
