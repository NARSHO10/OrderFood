<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Optional: check if user is admin
if ($_SESSION['role'] !== 'admin') { die("Access denied"); }

// Handle Add Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name  = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $img   = trim($_POST['img']);

    if ($name !== '' && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO menu_items (name, price, img) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $img);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_menu.php?success=added");
        exit;
    }
}

// Handle Remove Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $id = (int)$_POST['id'];
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_menu.php?success=removed");
        exit;
    }
}

// Fetch all menu items
$menuItems = [];
$result = $conn->query("SELECT id, name, price, img FROM menu_items ORDER BY created_at ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin — Manage Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container my-5">
    <?php include __DIR__ . '/../components/nav.php'; ?>
    <h1 class="mb-4">Manage Menu Items</h1>

    <!-- Add Item Form -->
    <div class="card mb-4">
        <div class="card-header">Add New Item</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image URL</label>
                    <input type="text" name="img" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Add Item</button>
            </form>
        </div>
    </div>

    <!-- Existing Items -->
    <h2 class="mb-3">Current Menu</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Image</th>
                <th style="width:120px">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($menuItems)): ?>
                <tr><td colspan="4" class="text-center">No items yet.</td></tr>
            <?php else: ?>
                <?php foreach ($menuItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <?php if ($item['img']): ?>
                                <img src="<?php echo htmlspecialchars($item['img']); ?>" alt="" style="width:80px;height:auto;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
