<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: 25_404_error.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>StoreMate Header</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      margin-top: 8vh; 
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: linear-gradient(90deg, #1abc9c, #16a085);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2vw; 
      height: 10vh; 
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      z-index: 1000;
    }

    .logo {
      font-size: 4vw; 
      font-weight: 700;
      color: #fff;
      letter-spacing: 2px;
      cursor: default;
      user-select: none;
    }

    nav {
      display: flex;
      align-items: center;
      gap: 3vw; 
    }

    nav a {
      color: #ecf0f1;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.5vw;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #f39c12;
    }

    .btn-login {
      padding: 1vh 2vw;
      background-color: #f39c12;
      color: #fff !important;
      border-radius: 25px;
      font-weight: 600;
      font-size: 1.2vw; /* Adjusting font size using viewport width */
      box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
      transition: background-color 0.3s ease;
    }

    .btn-login:hover {
      background-color: #e67e22;
      box-shadow: 0 6px 16px rgba(230, 126, 34, 0.6);
    }

    /* Responsive Menu for smaller devices */
    @media (max-width: 768px) {
      nav {
        gap: 2vw;
      }

      nav a, .btn-login {
        font-size: 2.5vw; 
        padding: 1vh 3vw;
      }

      .logo {
        font-size: 5vw;
    }

    @media (max-width: 480px) {
      nav {
        gap: 3vw; 
      }

      .logo {
        font-size: 6vw;
      }

      nav a {
        font-size: 3.5vw;
      }

      .btn-login {
        font-size: 4vw; 
        padding: 2vh 4vw;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">StoreMate</div>
  <nav>

  </nav>
</header>

</body>
</html>
