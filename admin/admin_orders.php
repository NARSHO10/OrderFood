<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Optional: check if user is admin
// if ($_SESSION['role'] !== 'admin') { die("Access denied"); }

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $status  = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_orders.php?updated=1");
    exit;
}

// Fetch all orders with user info
$orders = [];
$result = $conn->query("
    SELECT o.id, o.user_id, o.total, o.status, o.created_at, u.name AS user_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin — Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <main class="container my-5">
        <h1 class="mb-4">Manage Client Orders</h1>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Order status updated successfully!</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="text-center">No orders yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                <td>$<?php echo number_format($order['total'], 2); ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo ucfirst($order['status']); ?></span>
                                </td>
                                <td>
                                    <form method="post" class="d-flex">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" class="form-select form-select-sm me-2">
                                            <option value="pending"   <?php if($order['status']=='pending') echo 'selected'; ?>>Pending</option>
                                            <option value="approved"  <?php if($order['status']=='approved') echo 'selected'; ?>>Approved</option>
                                            <option value="shipped"   <?php if($order['status']=='shipped') echo 'selected'; ?>>Shipped</option>
                                            <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>Delivered</option>
                                            <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
