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
<title>StoreMate Footer</title>
<style>
  html, body {
    height: 100%;
    margin: 0; padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  main {
    flex-grow: 1;
    padding: 4vh 4vw;
    background: #f5f5f5;
  }

  footer {
  background: linear-gradient(90deg, #16a085, #1abc9c);
  color: #ecf0f1;
  padding: 3vh 4vw;
  font-size: 1.2vw;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  gap: 2vw;
  box-shadow: 0 -0.5vh 1vh rgba(0,0,0,0.15);
  position: static; /* ya hata do position property */
  width: 100%;
  bottom: auto;
  left: auto;
  height: auto;
  z-index: auto;
}

  .footer-left, .footer-links, .footer-contact {
    flex: 1 1 25vw;
    min-width: 250px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .footer-left h2 {
    font-size: 2vw;
    margin-bottom: 0.2vh;
    font-weight: 700;
    letter-spacing: 0.2vw;
    line-height: 1;
  }

  .footer-left p,
  .footer-contact p,
  .footer-links a {
    font-size: 1vw;
    margin: 0.1vh 0;
    text-decoration: none;
    color: #ecf0f1;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .footer-links a:hover {
    color: #f39c12;
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    footer {
      font-size: 3vw;
      padding: 2vh 5vw;
      gap: 1vh;
      text-align: center;
      align-items: center;
    }
    .footer-left, .footer-links, .footer-contact {
      min-width: auto;
      margin-bottom: 1.5vh;
      flex-direction: row;
      justify-content: center;
    }
    .footer-links {
      flex-wrap: wrap;
      gap: 2vw;
    }
  }

  @media (max-width: 480px) {
    footer {
      font-size: 4.5vw;
      padding: 3vh 6vw;
      gap: 2vw;
    }
    .footer-links {
      flex-direction: column;
      gap: 3vh;
    }
    .footer-left h2 {
      font-size: 4vw;
    }
    .footer-left p,
    .footer-links a,
    .footer-contact p {
      font-size: 4vw;
    }
  }
</style>
</head>
<body>
  <main>
    
    <h1>Dashboard Content</h1>
    <p>Yeh content jitna bada hoga, footer utni niche ayega.</p>
    <p>...</p>
  </main>

  <footer>
    <div class="footer-left">
      <h2>StoreMate</h2>
      <p>Smart Inventory. Simple Life.</p>
    </div>
    <div class="footer-links">
      <a href="#">Home</a>
      <a href="#">About</a>
      <a href="#">Contact Us</a>
      <a href="#">Help</a>
    </div>
    <div class="footer-contact">
      <p><strong>Email:</strong> support@storemate.com</p>
      <p><strong>Phone:</strong> +91 98765 43210</p>
      <p>&copy; 2025 StoreMate. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
