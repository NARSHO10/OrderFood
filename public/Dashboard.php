<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ./index.php');
    exit;
}
$userId   = $_SESSION['user_id'];
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';

// --- Fetch cart count ---
$cartCount = 0;
$stmt = $conn->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $cartCount = $row['total_items'] ?? 0;
}
$stmt->close();

// --- Fetch recent orders ---
$orders = [];
$stmt = $conn->prepare("SELECT id, total, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] =  $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodOrder — Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="Dashbody">
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h4 mb-0">Welcome back, <?php echo htmlspecialchars($userName); ?>!</h1>
                <p class="text-muted small mb-0">Overview of your recent activity</p>
            </div>
            <div>
                <a href="./orders.php" class="btn btn-outline-primary me-2"><i class="bi bi-receipt"></i> My Orders</a>
                <a href="/FoodOrderingApp/public/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Current Cart</h6>
                        <p class="card-text text-muted" >You have <strong><?php echo $cartCount; ?></strong> items in your cart.</p>
                        <a href="./cart.php" class="btn btn-sm btn-primary">View Cart</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Recent Orders</h6>
                        <?php if (!empty($orders)): ?>
                            <p class="card-text text-muted">Last order: <strong>#<?php echo $orders[0]['id']; ?></strong> — <?php echo ucfirst($orders[0]['status']); ?></p>
                        <?php else: ?>
                            <p class="card-text text-muted">No orders yet.</p>
                        <?php endif; ?>
                        <a href="/FoodOrderingApp/public/orders.php" class="btn btn-sm btn-outline-primary">See all orders</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Account</h6>
                        <p class="card-text text-muted">Manage your profile and payment methods.</p>
                        <a href="/FoodOrderingApp/public/edit_profile.php" class="btn btn-sm btn-outline-secondary">Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <section class="mt-5">
            <h5 class="mb-3">Recent Orders</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="5" class="text-center">No orders found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match ($order['status']) {
                                            'completed','delivered' => 'bg-success',
                                            'pending' => 'bg-secondary',
                                            'paid' => 'bg-info',
                                            'shipped' => 'bg-primary',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-warning text-dark'
                                        };
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center small">
            &copy; <?php echo date('Y'); ?> FoodOrder. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
