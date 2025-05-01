<?php
// MongoDB connection
require 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanFood Home</title>
    <link rel="stylesheet" href="Home/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<!-- Header Section -->
<header id="top-header">
    <h1>Welcome to UrbanFoods</h1>
    <a href="login.php">
        <button id="login-button">Login</button>
    </a>
    <a href="cart.php">
        <button id="cart">Cart</button>
    </a>
    <div id="logo-wrapper">
        <img src="assets/logo.png" alt="UrbanFood Logo" id="logo">
        
    </div>
    <div class="header-buttons">
        <button onclick="location.href='Bregis.php'">Sign In as Buyer</button>
        <button onclick="location.href='sellerReg.php'">Sign In as Seller</button>
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

<!-- Slideshow Section -->
<section id="slideshow">
    <div class="slide-container">
        <img src="assets/hen.jpg" alt="Image 1">
        <img src="assets/fruits.jpg" alt="Image 2">
        <img src="assets/vegetable.jpg" alt="Image 3">
        <img src="assets/baked.jpg" alt="Image 4">
        <img src="assets/hand-made.jpg" alt="Image 5">
    </div>
</section>

<!-- Main Content -->
<main id="main-content">
    <!-- About Us Section -->
    <section id="about">
        <div class="center-container">
            <img src="assets/about.png" alt="about_us" class="small-image">
        </div>
        <h2>About Us</h2>
        <p>
            UrbanFood connects urban farmers and local producers to customers looking for fresh and eco-friendly products. We're all about sustainability, quality, and community.
        </p>
    </section>

    <!-- Our Services Section -->
    <section id="services">
        <div class="center-container">
            <img src="assets/service.png" alt="services" class="small-image">
        </div>
        <h2>Our Services</h2>
        <ul>
            <li>Doorstep delivery of fresh produce.</li>
            <li>An online marketplace for local producers.</li>
            <li>Eco-friendly product options for our community.</li>
            <li>Customized baked goods and handmade crafts.</li>
        </ul>
    </section>

    <!-- Contact Us Section -->
    <section id="contact">
        <div class="center-container">
            <img src="assets/contact.png" alt="contact_us" class="small-image">
        </div>
        <h2>Contact Us</h2>
        <p>We'd love to hear from you! Get in touch with us:</p>
        <ul>
            <li>Email: support@urbanfood.com</li>
            <li>Phone: +94 123 456 789</li>
            <li>Address: 123 Green Lane, Colombo, Sri Lanka</li>
        </ul>
    </section>

    <!-- Feedback Form -->

<section id="feedback-section">
    <h2>Leave Your Feedback</h2>
    <form action="submit_feedback.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" rows="5" placeholder="Your Feedback" required></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
</section>


<script src="home/scripts.js"></script>
<footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>
    
</body>
</html>
