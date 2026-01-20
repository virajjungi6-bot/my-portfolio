<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: 25_404_error.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "pro_login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sale_id = isset($_GET['sale_id']) ? (int)$_GET['sale_id'] : 0;

$customer_name = $customer_contact = $total_amount = $amount_paid = $amount_pending = $payment_status = $sale_date = "";
$message = "";

// Fetch existing sale data
if ($sale_id > 0) {
    $sql = "SELECT * FROM selldata WHERE sale_id = $sale_id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $customer_name    = $row['customer_name'];
        $customer_contact = $row['customer_contact'];
        $total_amount     = $row['total_amount'];
        $amount_paid      = $row['amount_paid'];
        $amount_pending   = $row['amount_pending'];
        $payment_status   = $row['payment_status'];
        $sale_date        = $row['sale_date'];
    } else {
        $message = "No record found!";
    }
} else {
    $message = "Invalid Sale ID!";
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name    = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_contact = mysqli_real_escape_string($conn, $_POST['customer_contact']);
    $total_amount     = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $amount_paid      = mysqli_real_escape_string($conn, $_POST['amount_paid']);
    $amount_pending   = mysqli_real_escape_string($conn, $_POST['amount_pending']);
    $payment_status   = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $sale_date        = mysqli_real_escape_string($conn, $_POST['sale_date']);

    $update_sql = "UPDATE selldata SET 
        customer_name    = '$customer_name',
        customer_contact = '$customer_contact',
        total_amount     = '$total_amount',
        amount_paid      = '$amount_paid',
        amount_pending   = '$amount_pending',
        payment_status   = '$payment_status',
        sale_date        = '$sale_date'
        WHERE sale_id = $sale_id";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Sale data updated successfully!";
    } else {
        $message = "Update failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Sale Data</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        padding: 2vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }
    .container {
        width: 90vw;
        max-width: 450px;
        background: white;
        padding: 3vh 3vw;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 2vh;
        color: #2c3e50;
    }
    label {
        display: block;
        margin-top: 1vh;
        margin-bottom: 0.5vh;
        font-weight: bold;
        color: #333;
    }
    input[type="text"],
    input[type="date"],
    select {
        width: 100%;
        padding: 1vh;
        margin-bottom: 1.5vh;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .button-row {
        display: flex;
        justify-content: space-between;
        gap: 4%;
        margin-top: 2vh;
    }
    input[type="submit"],
    .back-btn {
        width: 48%;
        text-align: center;
        background-color: #3498db;
        color: white;
        border: none;
        cursor: pointer;
        text-decoration: none;
        padding: 1vh;
        border-radius: 5px;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
        background-color: #2980b9;
    }
    .back-btn {
        background-color: #2ecc71;
    }
    .back-btn:hover {
        background-color: #27ae60;
    }
    .message {
        margin-bottom: 1.5vh;
        padding: 1vh;
        color: #fff;
        background-color: #4caf50;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Edit Sale Data</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Customer Name</label>
        <input type="text" name="customer_name" value="<?= htmlspecialchars($customer_name) ?>" required />

        <label>Customer Contact</label>
        <input type="text" name="customer_contact" value="<?= htmlspecialchars($customer_contact) ?>" required />

        <label>Total Amount</label>
        <input type="text" name="total_amount" value="<?= htmlspecialchars($total_amount) ?>" required />

        <label>Amount Paid</label>
        <input type="text" name="amount_paid" value="<?= htmlspecialchars($amount_paid) ?>" required />

        <label>Amount Pending</label>
        <input type="text" name="amount_pending" value="<?= htmlspecialchars($amount_pending) ?>" required />

        <label>Payment Status</label>
        <select name="payment_status" required>
            <option value="Pending" <?= $payment_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Full Paid" <?= $payment_status == 'Full Paid' ? 'selected' : '' ?>>Full Paid</option>
            <option value="Partial" <?= $payment_status == 'Partial' ? 'selected' : '' ?>>Partial</option>
        </select>

        <label>Sale Date</label>
        <input type="date" name="sale_date" value="<?= htmlspecialchars($sale_date) ?>" required />

        <div class="button-row">
            <input type="submit" value="Update Data" />
            <a href="5dashboard.php" class="back-btn">Back to Page</a>
        </div>
    </form>
</div>
</body>
</html>
