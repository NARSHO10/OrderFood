<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Protect admin pages
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">FoodOrder Admin</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>
                </span>
                <a href="/FoodOrderingApp/public/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        <div class="row g-4">
            <!-- Admin Menu -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title"><i class="bi bi-list"></i> Manage Menu</h4>
                        <p class="card-text">Add, edit, or remove food items from the menu.</p>
                        <a href="admin_menu.php" class="btn btn-primary">Go to Menu Management</a>
                    </div>
                </div>
            </div>

            <!-- Admin Orders -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title"><i class="bi bi-bag-check"></i> Manage Orders</h4>
                        <p class="card-text">View and update customer orders (approve, ship, deliver).</p>
                        <a href="admin_orders.php" class="btn btn-success">Go to Order Management</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center small">
            &copy; <?php echo date('Y'); ?> FoodOrder Admin. All rights reserved.
        </div>
    </footer>
</body>
</html>
