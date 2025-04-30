<?php
// Include the connection file
include('connection.php');

// Now you can execute Oracle queries using the $conn variable.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Add Product</title>
    <link rel="stylesheet" href="seller_dashboard/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../assets/logo.png" alt="UrbanFood Logo">
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
                <li><a href="Products_sell.php">Add Products</a></li>
                <li><a href="remove_pro.php">Remove Products</a></li>
                <li><a href="change_pro.php">Change Products</a></li>
            </ul>
        </aside>
        <section id="add-product" class="dashboard-content">
            <h2>Add Your Product</h2>
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

                <label for="image">Upload Product Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit">Add Product</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $productName = $_POST['productName'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    // Move the uploaded image to a folder (make sure the folder is writable)
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

    // Insert the product into the database
    $sql = "INSERT INTO products (product_name, category, price, image) VALUES (:productName, :category, :price, :image)";
    $stmt = oci_parse($conn, $sql);

    // Bind parameters
    oci_bind_by_name($stmt, ":productName", $productName);
    oci_bind_by_name($stmt, ":category", $category);
    oci_bind_by_name($stmt, ":price", $price);
    oci_bind_by_name($stmt, ":image", $image);

    // Execute the query
    if (oci_execute($stmt)) {
        echo "Product added successfully!";
    } else {
        $e = oci_error($stmt);
        echo "Error adding product: " . $e['message'];
    }
}
?>
