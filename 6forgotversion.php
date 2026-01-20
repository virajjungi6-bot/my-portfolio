<?php

$conn = mysqli_connect("localhost", "root", "", "pro_login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$usernameError = $oldPassError = $newPassError = $confirmPassError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ✅ Validation
    if (empty($username)) $usernameError = "Please enter username.";
    if (empty($old_password)) $oldPassError = "Please enter old password.";
    if (empty($new_password)) $newPassError = "Please enter new password.";
    elseif (strlen($new_password) < 6) $newPassError = "Password must be at least 6 characters.";
    if (empty($confirm_password)) $confirmPassError = "Please confirm your password.";
    elseif ($new_password !== $confirm_password) $confirmPassError = "Passwords do not match.";

    // ✅ If any error → redirect back with messages
    if ($usernameError || $oldPassError || $newPassError || $confirmPassError) {
        header("Location: 6forgotpwd.php?username=".urlencode($username)
            ."&usernameError=".urlencode($usernameError)
            ."&oldPassError=".urlencode($oldPassError)
            ."&newPassError=".urlencode($newPassError)
            ."&confirmPassError=".urlencode($confirmPassError));
        exit;
    }

    // ✅ Check old password
    $stmt = mysqli_prepare($conn, "SELECT password FROM login_data WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($old_password === $row['password']) {
            // ✅ Update password
            $update_stmt = mysqli_prepare($conn, "UPDATE login_data SET password = ? WHERE username = ?");
            mysqli_stmt_bind_param($update_stmt, "ss", $new_password, $username);
            if (mysqli_stmt_execute($update_stmt)) {
                header("Location: 6forgotpwd.php?msg=Password updated successfully!");
            } else {
                header("Location: 6forgotpwd.php?error=Failed to update password");
            }
        } else {
            header("Location: 6forgotpwd.php?error=Old password is incorrect&username=".urlencode($username));
        }
    } else {
        header("Location: 6forgotpwd.php?error=User not found");
    }
}

mysqli_close($conn);
?>
