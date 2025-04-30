<?php
session_start();
include 'connection.php'; // Include the database connection

// Function to search for product based on name
function searchProduct($productName) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to update product details
function updateProduct($productId, $productName, $category, $price, $imagePath) {
    global $conn;
    $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $productName, $category, $price, $imagePath, $productId);
    return $stmt->execute();
}

// Handling the product search form submission
$product = null;
if (isset($_POST['searchProductName'])) {
    $searchProductName = $_POST['searchProductName'];
    $product = searchProduct('%' . $searchProductName . '%');
}

// Handling the product update form submission
if (isset($_POST['updateProduct'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['editProductName'];
    $category = $_POST['editCategory'];
    $price = $_POST['editPrice'];

    // Handle file upload for image
    $imagePath = "";
    if (isset($_FILES['editImage']) && $_FILES['editImage']['error'] == 0) {
        $imageTmpName = $_FILES['editImage']['tmp_name'];
        $imageName = $_FILES['editImage']['name'];
        $imagePath = 'uploads/' . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
    } else {
        // Keep old image if no new one uploaded
        $imagePath = $_POST['existingImage'];
    }

    $isUpdated = updateProduct($productId, $productName, $category, $price, $imagePath);
    $updateMessage = $isUpdated ? "Product updated successfully!" : "Failed to update product!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Change Product</title>
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
        <section id="change-product" class="dashboard-content">
            <h2>Change Product Details</h2>

            <!-- Search Product Form -->
            <form method="POST" action="">
                <label for="searchProductName">Search Product Name</label>
                <input type="text" id="searchProductName" name="searchProductName" placeholder="Enter product name" required>
                <button type="submit">Search</button>
            </form>

            <?php if ($product): ?>
                <!-- Product Details Form to Edit -->
                <div id="editProductDetails" class="product-details">
                    <h3>Edit Product Details</h3>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="productId" value="<?= $product['id'] ?>">

                        <label for="editProductName">Product Name</label>
                        <input type="text" id="editProductName" name="editProductName" value="<?= $product['name'] ?>" required>

                        <label for="editCategory">Category</label>
                        <select id="editCategory" name="editCategory" required>
                            <option value="fruits" <?= $product['category'] == 'fruits' ? 'selected' : '' ?>>Fruits</option>
                            <option value="vegetables" <?= $product['category'] == 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                            <option value="dairy" <?= $product['category'] == 'dairy' ? 'selected' : '' ?>>Dairy Products</option>
                            <option value="bakedGoods" <?= $product['category'] == 'bakedGoods' ? 'selected' : '' ?>>Baked Goods</option>
                            <option value="crafts" <?= $product['category'] == 'crafts' ? 'selected' : '' ?>>Handmade Crafts</option>
                        </select>

                        <label for="editPrice">Price (in USD)</label>
                        <input type="number" id="editPrice" name="editPrice" value="<?= $product['price'] ?>" step="0.01" required>

                        <label for="editImage">Upload New Product Image</label>
                        <input type="file" id="editImage" name="editImage" accept="image/*">
                        <input type="hidden" name="existingImage" value="<?= $product['image'] ?>">

                        <button type="submit" name="updateProduct">Update Product</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if (isset($updateMessage)): ?>
                <p><?= $updateMessage ?></p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
    </footer>
</body>
</html>
