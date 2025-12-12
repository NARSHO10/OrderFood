<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodOrder — Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .hero {
            background: linear-gradient(90deg, rgba(255,179,64,0.08), rgba(255,99,71,0.04));
            border-radius: .5rem;
        }
        .food-card img { height: 160px; object-fit: cover; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <main class="container my-5">
        <section class="hero p-4 p-md-5 mb-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h1 class="display-6 fw-bold">Delicious food delivered to your door</h1>
                    <p class="lead">Browse our menu, add items to your cart and enjoy fast delivery or pickup.</p>
                    <p>
                        <a href="./menu.php" class="btn btn-primary btn-lg me-2">View Menu</a>
                        <!-- Show Cart only if logged in -->
                        <?php if (!empty($_SESSION['user_id'])): ?>
                            <a href="../cart.php" class="btn btn-outline-secondary btn-lg me-2">
                                View Cart <i class="bi bi-cart"></i>
                            </a>
                        <?php endif; ?>
                        <!--  Always show login options -->
                        <a href="./Login.php" class="btn btn-success btn-lg me-2">Login</a>
                    </p>
                </div>
                <div class="col-md-5 text-center">
                    <img src="https://tse4.mm.bing.net/th/id/OIP.wPqc_gcHQnhtw75cOjzVBQHaE8?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Delicious food" class="img-fluid rounded">
                </div>
            </div>
        </section>

        <!-- Popular items section stays the same -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">Popular items</h2>
                <a href="./menu.php" class="small text-decoration-none">See full menu →</a>
            </div>

            <div class="row g-3">
                <?php
                $items = [
                    ['name'=>'Classic Burger','price'=>'8.99','img'=>'https://tse3.mm.bing.net/th/id/OIP.27EBy3KbcB6sEKoulZOZTwHaLH?rs=1&pid=ImgDetMain&o=7&rm=3'],
                    ['name'=>'Margherita Pizza','price'=>'12.50','img'=>'https://tse3.mm.bing.net/th/id/OIP.Zgim-HnEgdzBq8UjHVaUygHaJQ?rs=1&pid=ImgDetMain&o=7&rm=3'],
                    ['name'=>'Caesar Salad','price'=>'7.25','img'=>'https://tse3.mm.bing.net/th/id/OIP.8UagT3WWGxruvIOqJTCPeQHaE8?w=3000&h=2000&rs=1&pid=ImgDetMain&o=7&rm=3'],
                    ['name'=>'Chicken Wrap','price'=>'6.75','img'=>'https://insanelygoodrecipes.com/wp-content/uploads/2022/01/Homemade-Chicken-Shawarma-with-Vegetables.jpg'],
                ];
                foreach ($items as $it): ?>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card food-card h-100 shadow-sm">
                            <img src="<?php echo $it['img']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($it['name']); ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($it['name']); ?></h5>
                                <p class="text-muted mb-3">$<?php echo $it['price']; ?></p>
                                <div class="mt-auto">
                                    <a href="/FoodOrderingApp/public/menu.php" class="btn btn-sm btn-outline-primary w-100">Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="text-center text-muted small">
            <p class="mb-0">Open daily • Fast delivery • Secure payments</p>
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
