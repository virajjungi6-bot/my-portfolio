<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Password</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 2vh 2vw;
    }
    .form-container {
      background: #fff;
      padding: 4vh 4vw;
      border-radius: 1.5vh;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 35vw;
      max-width: 90vw;
      text-align: center;
    }
    .form-container h2 {
      margin-bottom: 3vh;
      color: #2c3e50;
      font-size: 2.5vw;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 1.5vh 1.5vw;
      margin-bottom: 1vh;
      border: 1.5px solid #6a11cb;
      border-radius: 1vh;
      font-size: 1.2vw;
      outline: none;
    }
    .error-message {
      color: #e74c3c;
      font-size: 1vw;
      margin-bottom: 1vh;
      text-align: left;
      font-weight: 600;
    }
    .message-box {
      font-weight: bold;
      margin-bottom: 15px;
    }
    .success { color: green; }
    .error { color: red; }
    label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-weight: 600;
      color: #2c3e50;
    }
    input[type="submit"], .button-link {
      width: 100%;
      padding: 1.8vh 1.8vw;
      margin-top: 1.5vh;
      border: none;
      border-radius: 1vh;
      font-size: 1.3vw;
      font-weight: 600;
      color: #fff;
      cursor: pointer;
      text-decoration: none;
      display: block;
      transition: background 0.3s ease;
    }
    input[type="submit"] { background: #2575fc; }
    input[type="submit"]:hover { background: #6a11cb; }
    .button-link { background: #6a11cb; margin-top: 2vh; }
    .button-link:hover { background: #2575fc; }
    @media (max-width: 768px) {
      .form-container { width: 80vw; }
      .form-container h2 { font-size: 5vw; }
      input[type="submit"], .button-link { font-size: 3vw; }
      input { font-size: 3vw; }
      .error-message { font-size: 2.5vw; }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>🔐 Reset Password</h2>

    <div class="message-box">
      <?php if (isset($_GET['msg'])) echo "<p class='success'>{$_GET['msg']}</p>"; ?>
      <?php if (isset($_GET['error'])) echo "<p class='error'>{$_GET['error']}</p>"; ?>
    </div>

    <form action="6forgotversion.php" method="post">
      <label>Username <span style="color:red;">*</span></label>
      <input type="text" name="username" placeholder="Enter Username" value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>">
      <?php if (isset($_GET['usernameError']) && $_GET['usernameError'] != '') echo "<div class='error-message'>{$_GET['usernameError']}</div>"; ?>

      <label>Old Password <span style="color:red;">*</span></label>
      <input type="password" name="old_password" placeholder="Enter Old Password">
      <?php if (isset($_GET['oldPassError']) && $_GET['oldPassError'] != '') echo "<div class='error-message'>{$_GET['oldPassError']}</div>"; ?>

      <label>New Password <span style="color:red;">*</span></label>
      <input type="password" name="new_password" placeholder="Enter New Password">
      <?php if (isset($_GET['newPassError']) && $_GET['newPassError'] != '') echo "<div class='error-message'>{$_GET['newPassError']}</div>"; ?>

      <label>Confirm New Password <span style="color:red;">*</span></label>
      <input type="password" name="confirm_password" placeholder="Confirm New Password">
      <?php if (isset($_GET['confirmPassError']) && $_GET['confirmPassError'] != '') echo "<div class='error-message'>{$_GET['confirmPassError']}</div>"; ?>

      <input type="submit" value="Confirm Password">
    </form>

    <a href="index.php" class="button-link">Back to Login</a>
  </div>
</body>
</html>
