<!-- <?php this is not work page now
// session_start();
// $con = mysqli_connect("localhost", "root", "", "pro_login");

// if (!$con) {
//     die("Database connection failed: " . mysqli_connect_error());
// }

// $message = "";

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     $username = trim($_POST['username']);
//     $password = trim($_POST['password']);

//     if (empty($username) || empty($password)) {
//         $message = "<p style='color:red;'>Username and Password are required.</p>";
//     } else {
//         $stmt = mysqli_prepare($con, "SELECT * FROM login_data WHERE username=? AND password=?");
//         mysqli_stmt_bind_param($stmt, "ss", $username, $password);
//         mysqli_stmt_execute($stmt);
//         $result = mysqli_stmt_get_result($stmt);

//         if (mysqli_num_rows($result) === 1) {
//             $message = "<p style='color:green;'>Login successful! Redirecting...</p>";
//             header("refresh:2; url=5dashboard.php");
//         } else {
//             $message = "<p style='color:red;'>Invalid username or password.</p>";
//         }
//     }
// }
?> -->