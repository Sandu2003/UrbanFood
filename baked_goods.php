<?php
include 'connection.php'; // Oracle DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baked Goods - UrbanFood</title>
    <link rel="stylesheet" href="home/stylesP.css">
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
            </ul>
        </nav>
    </div>
<main id="gallery-main">
    <h2>Fresh Baked Goods</h2>
    <section class="gallery-grid">
        <?php
        $query = "SELECT * FROM products WHERE LOWER(category) = 'baked goods'";
        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);

        while ($row = oci_fetch_assoc($stmt)) {
            echo '<div class="gallery-item">';
            echo '<img src="' . htmlspecialchars($row['IMAGE_PATH']) . '" alt="' . htmlspecialchars($row['PRODUCT_NAME']) . '">';
            echo '<h3>' . htmlspecialchars($row['PRODUCT_NAME']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['DESCRIPTION']) . '</p>';
            echo '</div>';
        }

        oci_free_statement($stmt);
        oci_close($conn);
        ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 UrbanFood. All rights reserved.</p>
</footer>
</body>
</html>
