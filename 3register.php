<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
    body {
        margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }
    .form-container {
        background: #fff; padding: 2rem; border-radius: 12px;
        width: 100%; max-width: 400px; text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .form-container h2 { margin-bottom: 1rem; font-size: 2rem; }
    label { display: block; text-align: left; margin-top: 15px; font-weight: bold; color: #333; }
    .required { color: red; margin-left: 2px; } /* always show * */
    .error { color: red; font-size: 14px; text-align: left; margin-top: 5px; }
    .success { color: green; font-size: 14px; font-weight: bold; }
    input[type="text"], input[type="password"] {
        width: 100%; padding: 12px; margin-top: 5px;
        border: 1.5px solid #ccc; border-radius: 8px;
    }
    input[type="submit"] {
        width: 100%; padding: 12px;
        background: linear-gradient(45deg, #2575fc, #6a11cb);
        color: #fff; border: none; border-radius: 8px; margin-top: 15px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
    }
    .link { margin-top: 15px; text-align: center; font-size: 0.9rem; }
    .link a { color: #2575fc; text-decoration: none; }
    .link a:hover { text-decoration: underline; }
    .strength {
        text-align: left; font-size: 14px; margin-top: 5px; font-weight: bold;
    }
    .weak { color: red; }
    .medium { color: orange; }
    .strong { color: green; }
</style>
<script>
function checkPasswordStrength() {
    let pwd = document.getElementById("password").value;
    let strengthText = document.getElementById("strengthText");

    if (pwd.length === 0) {
        strengthText.textContent = "";
        return;
    }

    let strength = 0;
    if (pwd.length >= 8) strength++;
    if (/[A-Za-z]/.test(pwd)) strength++;
    if (/[0-9]/.test(pwd)) strength++;
    if (/[^A-Za-z0-9]/.test(pwd)) strength++; // special char

    if (strength <= 1) {
        strengthText.textContent = "Weak Password";
        strengthText.className = "strength weak";
    } else if (strength === 2) {
        strengthText.textContent = "Medium Password";
        strengthText.className = "strength medium";
    } else {
        strengthText.textContent = "Strong Password";
        strengthText.className = "strength strong";
    }
}
</script>
</head>
<body>
<div class="form-container">
    <h2>Register</h2>
    <?php if (isset($_GET['msg'])) echo "<p class='success'>{$_GET['msg']}</p>"; ?>
    <?php if (isset($_GET['error'])) echo "<p class='error'>{$_GET['error']}</p>"; ?>

    <form method="post" action="4register.php">

        <label>Username <span class="required">*</span></label>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>" />
        <?php if (isset($_GET['usernameError'])) echo "<div class='error'>{$_GET['usernameError']}</div>"; ?>

        <label>Password <span class="required">*</span></label>
        <input type="password" id="password" name="password" placeholder="Password" onkeyup="checkPasswordStrength()" />
        <div id="strengthText"></div>
        <?php if (isset($_GET['passwordError'])) echo "<div class='error'>{$_GET['passwordError']}</div>"; ?>

        <label>Confirm Password <span class="required">*</span></label>
        <input type="password" name="confirm_password" placeholder="Confirm Password" />
        <?php if (isset($_GET['confirmError'])) echo "<div class='error'>{$_GET['confirmError']}</div>"; ?>

        <input type="submit" value="Register" />
        <div class="link"><a href="index.php">Already have an account? Login</a></div>
    </form>
</div>
</body>
</html>
