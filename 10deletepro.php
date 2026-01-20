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

// DELETE logic
if (isset($_GET['id'])) {
    $del_id = intval($_GET['id']);
    $del_query = "DELETE FROM proadd WHERE id = $del_id";

    if (mysqli_query($con, $del_query)) {
        // Reset AUTO_INCREMENT after deletion
        $res = mysqli_query($con, "SELECT MAX(id) AS max_id FROM proadd");
        $row = mysqli_fetch_assoc($res);
        $max_id = $row['max_id'] ?? 0;
        $next_id = $max_id + 1;
        mysqli_query($con, "ALTER TABLE proadd AUTO_INCREMENT = $next_id");

        header("Location: 8showproduct.php");  
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
} else {
    echo "No ID specified to delete.";
}
?>
