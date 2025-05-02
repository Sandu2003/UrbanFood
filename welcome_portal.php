<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanFood - Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url('assets/welcome.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #f0f0f0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        header h1 {
            font-size: 2.8rem;
            color: #00ffad;
        }

        header p {
            font-size: 1.1rem;
            color: #ccc;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 20px;
        }

        .container h2 {
            margin-bottom: 40px;
            font-size: 2rem;
            color: #ffffff;
        }

        .portal-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 900px;
        }

        .option-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .option-card:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.1);
        }

        .option-card h2 {
            margin-bottom: 20px;
            color: #00ffcc;
        }

        .option-card a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #00ffcc;
            color: #000;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px #00ffcc88;
        }

        .option-card a:hover {
            background-color: #00e6b8;
            box-shadow: 0 0 15px #00ffe088;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.6);
            color: #ccc;
            text-align: center;
            padding: 20px;
            font-size: 0.95rem;
        }

        .socials a {
            margin: 0 10px;
            color: #00ffcc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .socials a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

<header>
    <h1>UrbanFood</h1>
    <p>Where Fresh Meets Fast – Join Us Today</p>
</header>

<div class="container">
    <h2>Select Your Path</h2>
    <div class="portal-options">
        <div class="option-card">
            <h2>Register as Buyer</h2>
            <a href="Bregis.php">Join as Buyer</a>
        </div>
        <div class="option-card">
            <h2>Register as Seller</h2>
            <a href="sellerReg.php">Join as Seller</a>
        </div>
        <div class="option-card">
            <h2>Login</h2>
            <a href="login.php">Login Now</a>
        </div>
    </div>
</div>

<footer>
    <p>Connect with us:</p>
    <div class="socials">
        <a href="#">Facebook</a>
        <a href="#">Instagram</a>
        <a href="#">Twitter</a>
    </div>
    <p>&copy; 2025 UrbanFood. All rights reserved.</p>
</footer>

</body>
</html>
