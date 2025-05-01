<?php
// Include the connection file
include('connection.php');

// Initialize variables
$successMessage = "";
$productName = $category = $price = $image = $description = $stock_quantity = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $productName = $_POST['productName'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock_quantity = $_POST['stock_quantity'];
    $image = $_FILES['image']['name'];

    // Move uploaded image
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $successMessage = "Image uploaded successfully!";
        } else {
            $successMessage = "Error uploading image.";
        }
    } else {
        $successMessage = "Only JPG, JPEG, PNG, and GIF images allowed.";
    }

    // Insert product into database
    if ($productName && $category && $price && $image && $description && $stock_quantity !== "") {
        $sql = "INSERT INTO products (product_name, category, price, image_path, description, stock_quantity) 
                VALUES (:productName, :category, :price, :image, :description, :stock_quantity)";
        
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":productName", $productName);
        oci_bind_by_name($stmt, ":category", $category);
        oci_bind_by_name($stmt, ":price", $price);
        oci_bind_by_name($stmt, ":image", $image);
        oci_bind_by_name($stmt, ":description", $description);
        oci_bind_by_name($stmt, ":stock_quantity", $stock_quantity);

        if (oci_execute($stmt)) {
            $successMessage = "✅ Product added successfully!";
        } else {
            $e = oci_error($stmt);
            $successMessage = "❌ Error adding product: " . $e['message'];
        }
    } else {
        $successMessage = "Please fill all fields and upload an image.";
    }
}

// Fetch products to display
$sql = "SELECT * FROM products";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
$products = [];
while ($row = oci_fetch_assoc($stmt)) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Add Product</title>
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
        <select id="dashboardSelect" class="dashboard-select" onchange="navigateDashboard()">
            <option value="seller" selected>Seller</option>
            <option value="buyer">Buyer</option>
        </select>
    </nav>
</header>
<main>
    <aside class="side-nav">
        <ul>
            <li><a href="Products_sell.php">Add Products</a></li>
            <li><a href="remove_pro.php">Remove Products</a></li>
            <li><a href="change_pro.php">Change Products</a></li>
        </ul>
    </aside>
    <section id="add-product" class="dashboard-content">
        <h2>Add Your Product</h2>

        <?php if ($successMessage): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form id="productForm" method="POST" action="add_product.php" enctype="multipart/form-data">
            <label for="productName">Product Name</label>
            <input type="text" id="productName" name="productName" placeholder="Enter product name" required>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="" disabled selected>Select category</option>
                <option value="fruits">Fruits</option>
                <option value="vegetables">Vegetables</option>
                <option value="dairy">Dairy Products</option>
                <option value="bakedGoods">Baked Goods</option>
                <option value="crafts">Handmade Crafts</option>
            </select>

            <label for="price">Price (in USD)</label>
            <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required>

            <label for="description">Product Description</label>
            <textarea id="description" name="description" placeholder="Enter product description" required></textarea>

            <label for="stock_quantity">Stock Quantity</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" required>

            <label for="image">Upload Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Add Product</button>
        </form>
    </section>

    <section id="product-list">
        <h2>Added Products</h2>
        <ul>
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <li>
                        <h3><?php echo $product['PRODUCT_NAME']; ?></h3>
                        <p>Category: <?php echo $product['CATEGORY']; ?></p>
                        <p>Price: $<?php echo $product['PRICE']; ?></p>
                        <p>Description: <?php echo $product['DESCRIPTION']; ?></p>
                        <p>Stock: <?php echo $product['STOCK_QUANTITY']; ?> available</p>
                        <img src="uploads/<?php echo $product['IMAGE_PATH']; ?>" alt="<?php echo $product['PRODUCT_NAME']; ?>" width="100">
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products added yet.</p>
            <?php endif; ?>
        </ul>
    </section>
</main>
<footer>
    <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
</footer>
<script src="script.js"></script>
</body>
</html>
