<?php
session_start();

// Dummy credentials (later you can check from database instead)
$buyer_email = "buyer@example.com";
$buyer_password = "buyer123";
$seller_email = "seller@example.com";
$seller_password = "seller123";

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: account_D.php"); // change this to correct dashboard later
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Basic login logic (you can later connect to database)
    if (
        ($role == 'buyer' && $email === $buyer_email && $password === $buyer_password) ||
        ($role == 'seller' && $email === $seller_email && $password === $seller_password)
    ) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_role'] = $role;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials or role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanFood Login</title>
    <link rel="stylesheet" href="Login\styles.css">
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="UrbanFood Logo" style="height: 100px;">
        <h1>UrbanFood Login</h1>
    </header>
    <main>
        <section id="login">
            <h2>Login to Your Account</h2>
            <?php if (isset($error)) echo "<div style='color:red; margin-bottom:10px;'>$error</div>"; ?>
            <form id="login-form" method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <label for="role">Login as:</label>
                <select id="role" name="role" required>
                    <option value="" disabled selected>Choose your role</option>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>

                <button type="submit">Login</button>
            </form>

            <!-- Home Button -->
            <button onclick="goHome()" id="home-button" style="margin-top: 20px;">Go Home</button>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>

    <script>
        function goHome() {
            window.location.href = '../Home/home_page.html'; // Make sure correct path
        }
    </script>
</body>
</html>
