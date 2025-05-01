<?php
include 'connection.php';
session_start();

// Fetch products from the database
$query = "SELECT * FROM products WHERE LOWER(category) = 'vegetables'";
$stid = oci_parse($conn, $query);
oci_execute($stid);

// Initialize an array to store products
$products = [];
while ($row = oci_fetch_assoc($stid)) {
    $products[] = $row;
}

oci_free_statement($stid);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vegetables - UrbanFood</title>
    <link rel="stylesheet" href="home/stylesP.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header -->
<header id="top-header">
    <h1>Welcome to UrbanFood</h1>
    <div id="logo-wrapper">
        <img src="assets/logo.png" alt="UrbanFood Logo" id="logo">
    </div>
</header>

<!-- Navigation Bar -->
<div id="logo-navbar">
    <nav>
        <ul>
            <li><a href="home_page.php">Home</a></li>
            <li><a href="home_page.php#about">About Us</a></li>
            <li><a href="home_page.php#services">Our Services</a></li>
            <li><a href="home_page.php#contact">Contact Us</a></li>
            <li class="dropdown">
                <a href="#">Products ▼</a>
                <ul class="dropdown-menu">
                    <li><a href="fruits.php">Fruits</a></li>
                    <li><a href="vegetables.php">Vegetables</a></li>
                    <li><a href="dairy.php">Dairy Products</a></li>
                    <li><a href="baked_goods.php">Baked Goods</a></li>
                    <li><a href="hand_made.php">Handmade Crafts</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>

<!-- Dynamic Vegetables Section -->
<main id="gallery-main">
    <h2>Fresh Vegetables</h2>
    <section class="gallery-grid">
        <?php foreach ($products as $product): ?>
            <div class="gallery-item">
                <img src="<?php echo htmlspecialchars($product['IMAGE_PATH']); ?>" alt="<?php echo htmlspecialchars($product['PRODUCT_NAME']); ?>">
                <h3><?php echo htmlspecialchars($product['PRODUCT_NAME']); ?></h3>
                <p><?php echo htmlspecialchars($product['DESCRIPTION']); ?></p>
                <p class="price">Rs. <?php echo number_format($product['PRICE'], 2); ?></p>
                <p class="stock">Available: <?php echo (int)$product['STOCK_QUANTITY']; ?> units</p>

                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['PRODUCT_ID']; ?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo (int)$product['STOCK_QUANTITY']; ?>" required>
                    <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2025 UrbanFood. All rights reserved.</p>
</footer>

</body>
</html>
