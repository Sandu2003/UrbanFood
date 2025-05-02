<?php
// Include the connection file
include('connection.php');

// Initialize variables
$productInfo = "";
$searchProductName = "";

// Handle search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchProductName'])) {
    $searchProductName = $_POST['searchProductName'];

    // Search for the product
    $sql = "SELECT * FROM products WHERE product_name = :searchProductName";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":searchProductName", $searchProductName);
    oci_execute($stmt);

    if ($row = oci_fetch_assoc($stmt)) {
        $productInfo = "Product Name: " . $row['PRODUCT_NAME'] . "<br>" .
                       "Category: " . $row['CATEGORY'] . "<br>" .
                       "Price: $" . $row['PRICE'] . "<br>" .
                       "Description: " . $row['DESCRIPTION'] . "<br>" .
                       "Stock Available: " . $row['STOCK_QUANTITY'] . "<br>" .
                       "Image: <br><img src='uploads/" . $row['IMAGE_PATH'] . "' alt='Product Image' width='100'>";
    } else {
        $productInfo = "❌ No product found with that name.";
    }
}

// Handle removal form submission
if (isset($_POST['removeProduct'])) {
    $productNameToRemove = $_POST['productNameToRemove'];

    $deleteSql = "DELETE FROM products WHERE product_name = :productNameToRemove";
    $deleteStmt = oci_parse($conn, $deleteSql);
    oci_bind_by_name($deleteStmt, ":productNameToRemove", $productNameToRemove);

    if (oci_execute($deleteStmt)) {
        $productInfo = "✅ Product <strong>" . htmlspecialchars($productNameToRemove) . "</strong> removed successfully!";
    } else {
        $e = oci_error($deleteStmt);
        $productInfo = "❌ Error removing product: " . $e['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Remove Product</title>
    <link rel="stylesheet" href="seller_dashboard/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/logo.png" alt="UrbanFood Logo">
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
                <li><a href="add_product.php">Add Products</a></li>
                <li><a href="remove_pro.php">Remove Products</a></li>
                <li><a href="change_pro.php">Change Products</a></li>
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

                <?php if ($productInfo && !str_contains($productInfo, "No product found") && !str_contains($productInfo, "removed successfully")): ?>
                    <form method="POST" action="remove_pro.php">
                        <input type="hidden" name="productNameToRemove" value="<?php echo htmlspecialchars($searchProductName); ?>">
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
