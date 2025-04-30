<?php
// Include the connection file
include('connection.php');

// Initialize variables for product details
$productInfo = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchProductName = $_POST['searchProductName'];

    // Search for product in the database
    $sql = "SELECT * FROM products WHERE product_name = :searchProductName";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":searchProductName", $searchProductName);
    oci_execute($stmt);

    // Fetch product details if found
    if ($row = oci_fetch_assoc($stmt)) {
        $productInfo = "Product Name: " . $row['PRODUCT_NAME'] . "<br>" .
                       "Category: " . $row['CATEGORY'] . "<br>" .
                       "Price: $" . $row['PRICE'] . "<br>" .
                       "Image: <img src='uploads/" . $row['IMAGE'] . "' alt='Product Image' width='100'>";
    } else {
        $productInfo = "No product found with that name.";
    }
}

// Check if product removal is requested
if (isset($_POST['removeProduct'])) {
    $productNameToRemove = $_POST['productNameToRemove'];

    // Delete the product from the database
    $deleteSql = "DELETE FROM products WHERE product_name = :productNameToRemove";
    $deleteStmt = oci_parse($conn, $deleteSql);
    oci_bind_by_name($deleteStmt, ":productNameToRemove", $productNameToRemove);
    
    if (oci_execute($deleteStmt)) {
        echo "Product removed successfully!";
    } else {
        echo "Error removing product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Remove Product</title>
    <link rel="stylesheet" href="seller_dashboard/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../assests/logo.png" alt="UrbanFood Logo">
        </div>
        <nav class="top-nav">
            <a href="home.html" class="nav-link">Home</a>
            <select id="dashboardSelect" class="dashboard-select" onchange="navigateDashboard()">
                <option value="seller" selected>Seller</option>
                <option value="buyer">Buyer</option>
            </select>
        </nav>
    </header>
    <main>
        <aside class="side-nav">
            <ul>
                <li><a href="Products_sell.html">Add Products</a></li>
                <li><a href="remove_pro.html">Remove Products</a></li>
                <li><a href="change_pro.html">Change Products</a></li>
            </ul>
        </aside>
        <section id="remove-product" class="dashboard-content">
            <h2>Remove a Product</h2>
            <form id="removeProductForm" method="POST" action="remove_pro.php">
                <label for="searchProductName">Search Product Name</label>
                <input type="text" id="searchProductName" name="searchProductName" placeholder="Enter product name" required>
                <button type="submit">Search</button>
            </form>

            <div id="productDetails" class="product-details">
                <h3>Product Details</h3>
                <p id="productInfo"><?php echo $productInfo; ?></p>

                <?php if ($productInfo && !strpos($productInfo, "No product found")): ?>
                    <form method="POST" action="remove_pro.php">
                        <input type="hidden" name="productNameToRemove" value="<?php echo $searchProductName; ?>">
                        <button type="submit" name="removeProduct">Remove Product</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
    </footer>
</body>
</html>
