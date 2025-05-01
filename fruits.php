<?php
include 'connection.php'; // Oracle DB connection
session_start(); // Start session (useful for login-based future features)

// Handle Add to Cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1; // Default quantity is 1

    // Insert into cart table using cart_seq for auto-increment
    $insert_cart_query = "INSERT INTO cart (cart_id, product_id, quantity) VALUES (cart_seq.NEXTVAL, :product_id, :quantity)";
    $stmt = oci_parse($conn, $insert_cart_query);
    oci_bind_by_name($stmt, ":product_id", $product_id);
    oci_bind_by_name($stmt, ":quantity", $quantity);

    $result = oci_execute($stmt);
    if ($result) {
        echo "<script>alert('Product added to cart successfully!');</script>";
    } else {
        $e = oci_error($stmt);
        echo "<script>alert('Failed to add to cart: " . htmlspecialchars($e['message']) . "');</script>";
    }

    oci_free_statement($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruits - UrbanFood</title>
    <link rel="stylesheet" href="home/stylesp.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header Section -->
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

<!-- Fruits Gallery Section -->
<main id="gallery-main">
    <h2>Fresh Fruits</h2>
    <section id="gallery-grid">
        <?php
        // Fetch fruits from the database
        $query = "SELECT * FROM products WHERE LOWER(category) = 'fruits'";
        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);

        while ($row = oci_fetch_assoc($stmt)) {
            echo '<div class="gallery-item">';
            echo '<img src="' . htmlspecialchars($row['IMAGE_PATH']) . '" alt="' . htmlspecialchars($row['PRODUCT_NAME']) . '">';
            echo '<h3>' . htmlspecialchars($row['PRODUCT_NAME']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['DESCRIPTION']) . '</p>';
            echo '<p class="price">Rs. ' . number_format($row['PRICE'], 2) . '</p>';
            echo '<p class="stock">Available: ' . (int)$row['STOCK_QUANTITY'] . ' units</p>';

            // Add to Cart Form
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['PRODUCT_ID']) . '">';
            echo '<button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>';
            echo '</form>';

            echo '</div>';
        }

        oci_free_statement($stmt);
        oci_close($conn);
        ?>
    </section>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2025 UrbanFood. All rights reserved.</p>
</footer>

</body>
</html>
