<?php


$conn = mysqli_connect("localhost", "root", "", "pro_login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

$usernameError = $passwordError = $confirmError = "";

// Username Validation
if (empty($username)) {
    $usernameError = "Username is required.";
} elseif (strlen($username) < 3) {
    $usernameError = "Username must be at least 3 characters.";
}

// Password Validation
if (empty($password)) {
    $passwordError = "Password is required.";
} elseif (strlen($password) < 8) {
    $passwordError = "Password must be at least 8 characters.";
} elseif (preg_match_all("/[A-Za-z]/", $password) < 3) {
    $passwordError = "Password must have at least 3 letters.";
} elseif (!preg_match("/[0-9]/", $password)) {
    $passwordError = "Password must have at least 1 number.";
}

// Confirm Password
if (empty($confirm_password)) {
    $confirmError = "Please confirm password.";
} elseif ($password !== $confirm_password) {
    $confirmError = "Passwords do not match.";
}

// If any errors, redirect back
if ($usernameError || $passwordError || $confirmError) {
    header("Location: 3register.php?username=" . urlencode($username)
        . "&usernameError=" . urlencode($usernameError)
        . "&passwordError=" . urlencode($passwordError)
        . "&confirmError=" . urlencode($confirmError));
    exit;
}

// Check if username exists
$checkStmt = mysqli_prepare($conn, "SELECT * FROM login_data WHERE username=?");
mysqli_stmt_bind_param($checkStmt, "s", $username);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) > 0) {
    header("Location: 3register.php?error=" . urlencode("Username already exists") . "&username=" . urlencode($username));
    exit;
}

// Insert user
$insertStmt = mysqli_prepare($conn, "INSERT INTO login_data (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($insertStmt, "ss", $username, $password);
if (mysqli_stmt_execute($insertStmt)) {
    header("Location: 3register.php?msg=" . urlencode("✅ Registration successful!"));
    exit;
} else {
    header("Location: 3register.php?error=" . urlencode("Registration failed! Try again."));
    exit;
}
?>
