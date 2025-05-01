<?php
session_start();
include 'connection.php'; // Oracle DB connection

// Function to search product by name
function searchProduct($productName) {
    global $conn;
    $sql = "SELECT * FROM products WHERE LOWER(product_name) LIKE LOWER(:product_name)";
    $stmt = oci_parse($conn, $sql);
    $likeName = '%' . $productName . '%';
    oci_bind_by_name($stmt, ":product_name", $likeName);
    oci_execute($stmt);
    return oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
}

// Function to update product
function updateProduct($productId, $productName, $category, $price, $imagePath, $description, $stock) {
    global $conn;
    $sql = "UPDATE products 
            SET product_name = :product_name, 
                category = :category, 
                price = :price, 
                image = :image_path,
                description = :description,
                stock = :stock 
            WHERE product_id = :product_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":product_name", $productName);
    oci_bind_by_name($stmt, ":category", $category);
    oci_bind_by_name($stmt, ":price", $price);
    oci_bind_by_name($stmt, ":image_path", $imagePath);
    oci_bind_by_name($stmt, ":description", $description);
    oci_bind_by_name($stmt, ":stock", $stock);
    oci_bind_by_name($stmt, ":product_id", $productId);
    return oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
}

// Initialize variables
$product = null;
$updateMessage = "";

// Handle search
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['searchProductName'])) {
    $product = searchProduct($_POST['searchProductName']);
}

// Handle update
if (isset($_POST['updateProduct'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['editProductName'];
    $category = $_POST['editCategory'];
    $price = $_POST['editPrice'];
    $description = $_POST['editDescription'];
    $stock = $_POST['editStock'];

    // Handle image
    if (isset($_FILES['editImage']) && $_FILES['editImage']['error'] == 0) {
        $imageTmpName = $_FILES['editImage']['tmp_name'];
        $imageName = basename($_FILES['editImage']['name']);
        $imagePath = 'uploads/' . $imageName;
        move_uploaded_file($imageTmpName, $imagePath);
    } else {
        $imagePath = $_POST['existingImage'];
    }

    $isUpdated = updateProduct($productId, $productName, $category, $price, $imagePath, $description, $stock);
    $updateMessage = $isUpdated ? "✅ Product updated successfully!" : "❌ Failed to update product!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Change Product</title>
    <link rel="stylesheet" href="seller_dashboard/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/logo.png" alt="UrbanFood Logo">
        </div>
        <nav class="top-nav">
            <a href="home_page.php" class="nav-link">Home</a>
            <select id="dashboardSelect" onchange="navigateDashboard()" class="dashboard-select">
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

        <section class="dashboard-content">
            <h2>Change Product Details</h2>

            <!-- Search Form -->
            <form method="POST" action="change_pro.php">
                <label for="searchProductName">Search Product Name</label>
                <input type="text" id="searchProductName" name="searchProductName" placeholder="Enter product name" required>
                <button type="submit">Search</button>
            </form>

            <!-- Display if found -->
            <?php if ($product): ?>
                <div class="product-details">
                    <h3>Edit Product Details</h3>
                    <form method="POST" action="change_pro.php" enctype="multipart/form-data">
                        <input type="hidden" name="productId" value="<?= htmlspecialchars($product['PRODUCT_ID']) ?>">

                        <label for="editProductName">Product Name</label>
                        <input type="text" id="editProductName" name="editProductName" value="<?= htmlspecialchars($product['PRODUCT_NAME']) ?>" required>

                        <label for="editCategory">Category</label>
                        <select id="editCategory" name="editCategory" required>
                            <?php
                            $categories = ['fruits', 'vegetables', 'dairy', 'bakedGoods', 'crafts'];
                            foreach ($categories as $cat) {
                                $selected = (strtolower($product['CATEGORY']) === strtolower($cat)) ? 'selected' : '';
                                echo "<option value=\"$cat\" $selected>" . ucfirst($cat) . "</option>";
                            }
                            ?>
                        </select>

                        <label for="editPrice">Price ($)</label>
                        <input type="number" step="0.01" id="editPrice" name="editPrice" value="<?= htmlspecialchars($product['PRICE']) ?>" required>

                        <label for="editStock">Stock</label>
                        <input type="number" id="editStock" name="editStock" value="<?= htmlspecialchars($product['STOCK']) ?>" required>

                        <label for="editDescription">Description</label>
                        <textarea id="editDescription" name="editDescription" rows="4" required><?= htmlspecialchars($product['DESCRIPTION']) ?></textarea>

                        <label for="editImage">Product Image</label>
                        <input type="file" id="editImage" name="editImage" accept="image/*">

                        <!-- Hidden input for existing image path -->
                        <input type="hidden" name="existingImage" value="<?= !empty($product['IMAGE']) ? htmlspecialchars($product['IMAGE']) : '' ?>">

                        <!-- Display current image if available -->
                        <?php if (!empty($product['IMAGE'])): ?>
                            <p>Current Image:</p>
                            <img src="<?= htmlspecialchars($product['IMAGE']) ?>" width="100" alt="Current Image">
                        <?php endif; ?>

                        <button type="submit" name="updateProduct">Update Product</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Update message -->
            <?php if ($updateMessage): ?>
                <p style="margin-top: 15px; font-weight: bold;"><?= $updateMessage ?></p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
    </footer>

    <script>
        function navigateDashboard() {
            const selected = document.getElementById("dashboardSelect").value;
            if (selected === "buyer") {
                window.location.href = "login.php";
            } else {
                window.location.href = "seller_dashboard.php";
            }
        }
    </script>
</body>
</html>
