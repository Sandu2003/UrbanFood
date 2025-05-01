<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';

    // Simulate login by setting session variables
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = $role;

    // Redirect to respective dashboard
    if ($role === 'buyer') {
        header("Location: account_D.php");
    } elseif ($role === 'seller') {
        header("Location: add_product.php");
    } else {
        // Default fallback (optional)
        header("Location: Home/home_page.html");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UrbanFood Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Login/styles.css">
    
</head>
<body>
    <header>
        <img src="assets/logo.png" alt="UrbanFood Logo" style="height: 100px;">
        <h1>UrbanFood Login</h1>
    </header>

    <main>
        <section id="login">
            <h2>Login to Your Account</h2>

            <form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>

                <label for="role">Login as:</label>
                <select name="role" id="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>

                <button type="submit">Login</button>
            </form>

            <button onclick="goHome()" style="margin-top: 20px;">Go Home</button>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>

    <script>
        function goHome() {
            window.location.href = 'Home/home_page.html'; // Adjust as needed
        }
    </script>
</body>
</html>
