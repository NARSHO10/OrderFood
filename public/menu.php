<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // connect to DB

// Fetch menu items from DB
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodOrder — Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /*  Force all menu images to same size */
        .card-img-top {
            width: 100%;
            height: 200px;       
            object-fit: cover;   
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <main class="container my-5">
        <h1 class="text-center mb-4">Our Menu</h1>
        <div class="row g-3">
            <?php if (empty($menuItems)): ?>
                <p class="text-center">No menu items available.</p>
            <?php else: ?>
                <?php foreach ($menuItems as $item): ?>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($item['img']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text mb-3">$<?php echo number_format($item['price'], 2); ?></p>
                                <div class="mt-auto">
                                    <button type="button" class="btn btn-primary w-100"
                                        onclick="addToCart('<?php echo addslashes($item['name']); ?>', <?php echo (float)$item['price']; ?>)">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center small">
            &copy; <?php echo date('Y'); ?> FoodOrder. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/FoodOrderingApp/public/script.js"></script>
</body>
</html>
