<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: 25_404_error.php");
    exit;
}

$con = mysqli_connect("localhost", "root", "", "pro_login");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get params
$sale_id = isset($_GET['sale_id']) ? (int) $_GET['sale_id'] : 0;
$action  = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'delete' && $sale_id > 0) {
    $delete_sql = "DELETE FROM selldata WHERE sale_id = $sale_id";
    if (mysqli_query($con, $delete_sql)) {
        echo "<script>alert('Sale deleted successfully.'); window.location.href = '12sellmodify.php';</script>";
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
}
?>
