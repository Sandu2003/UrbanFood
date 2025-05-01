<?php
include 'connection.php';

// Fetch products from the database
$query = "SELECT * FROM products WHERE category = 'vegetables'";
$stid = oci_parse($conn, $query);
oci_execute($stid);

// Initialize an array to store products
$products = [];
while ($row = oci_fetch_assoc($stid)) {
    // Add product to the products array
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
    <link rel="stylesheet" href="home/stylesp.css">
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
                <li><a href="home_page.html">Home</a></li>
                <li><a href="home_page.html#about">About Us</a></li>
                <li><a href="home_page.html#services">Our Services</a></li>
                <li><a href="home_page.html#contact">Contact Us</a></li>
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
                    <img src="assets/<?php echo htmlspecialchars($product['IMAGE_PATH']); ?>" alt="<?php echo htmlspecialchars($product['PRODUCT_NAME']); ?>">
                    <h3><?php echo htmlspecialchars($product['PRODUCT_NAME']); ?></h3>
                    <p><?php echo htmlspecialchars($product['DESCRIPTION']); ?></p>
                    
                    <!-- Display Price -->
                    <p class="price">Rs. <?php echo number_format($product['PRICE'], 2); ?></p>
                    
                    <!-- Display Available Stock -->
                    <p class="stock">Available: <?php echo (int)$product['STOCK']; ?> units</p>
                    
                    <!-- Add to Cart Button -->
                    <button class="add-to-cart">Add to Cart</button>
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
