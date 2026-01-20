<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$con = mysqli_connect("localhost", "root", "", "pro_login");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";
$usernameError = "";
$passwordError = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ✅ Validation
    if (empty($username)) {
        $usernameError = "* Username is required.";
    } elseif (strlen($username) < 3) {
        $usernameError = "* Username must be at least 3 characters.";
    }

    if (empty($password)) {
        $passwordError = "* Password is required.";
    } elseif (strlen($password) < 6) {
        $passwordError = "* Password must be at least 6 characters.";
    }

    if (empty($usernameError) && empty($passwordError)) {
        $stmt = mysqli_prepare($con, "SELECT * FROM login_data WHERE username=? AND password=?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            $message = "<p class='success'>✅ Login successful! Redirecting...</p>";
            header("refresh:2; url=5dashboard.php");
            exit;
        } else {
            $message = "<p class='error'>❌ Invalid username or password.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login Page</title>
<style>
    body {
        height: 100vh; display: flex; justify-content: center; align-items: center;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }
    .form-container {
        background: #fff; padding: 2rem; border-radius: 12px;
        width: 100%; max-width: 400px; text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .form-container h2 { margin-bottom: 1rem; font-size: 2rem; }
    .error { color: red; font-size: 14px; text-align: left; margin-top: 5px; }
    .success { color: green; font-size: 16px; font-weight: bold; }
    input[type="text"], input[type="password"] {
        width: 100%; padding: 12px; margin-top: 10px;
        border: 1.5px solid #ccc; border-radius: 8px;
    }
    input[type="submit"] {
        width: 100%; padding: 12px;
        background: linear-gradient(45deg, #2575fc, #6a11cb);
        color: #fff; border: none; border-radius: 8px; margin-top: 15px;
        cursor: pointer;
    }
    .link { margin-top: 15px; display: flex; justify-content: space-between; font-size: 0.9rem; }
    .link a { color: #2575fc; text-decoration: none; }
    .link a:hover { text-decoration: underline; }
</style>
<script>
function validateForm() {
    let username = document.forms["loginForm"]["username"].value.trim();
    let password = document.forms["loginForm"]["password"].value.trim();
    let error = "";

    if (username === "" && password === "") {
        error = "Please enter username and password.";
    } else if (username === "") {
        error = "Please enter username.";
    } else if (password === "") {
        error = "Please enter password.";
    }

    if (error !== "") {
        alert(error);
        return false;
    }
    return true;
}
</script>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>

    <!-- ✅ Message Box -->
    <div class="message-box">
        <?php if (!empty($message)) echo $message; ?>
    </div>

    <form name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm();">
        <div>
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" />
            <?php if (!empty($usernameError)) echo "<div class='error'>$usernameError</div>"; ?>
        </div>

        <div>
            <input type="password" name="password" placeholder="Password" />
            <?php if (!empty($passwordError)) echo "<div class='error'>$passwordError</div>"; ?>
        </div>

        <input type="submit" value="Login" />
        <div class="link">
            <a href="6forgotpwd.php">Forgot Password?</a>
        </div>
    </form>
</div>

</body>
</html>





    <!-- <a href="3register.php">Sign Up</a> -->
        