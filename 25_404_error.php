<?php
// Optional: prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 Not Found</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        background: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
    }
    .error-container {
        text-align: center;
        background: #fff;
        padding: 50px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .error-container h1 {
        font-size: 8rem;
        margin: 0;
        color: #007bff;
    }
    .error-container h2 {
        font-size: 2rem;
        margin: 10px 0 20px;
    }
    .error-container p {
        font-size: 1.2rem;
        margin-bottom: 25px;
    }
    .error-container a {
        text-decoration: none;
        padding: 12px 25px;
        background: #007bff;
        color: #fff;
        border-radius: 8px;
        font-weight: bold;
        transition: 0.3s;
    }
    .error-container a:hover {
        background: #0056b3;
    }
</style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>Oops! The page you are looking for doesn't exist or you don't have permission to access it.</p>
        <a href="index.php">Go to Login Page</a>
    </div>
</body>
</html>
