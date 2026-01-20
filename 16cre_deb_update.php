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
$message = "";

// Fetch sale and payment data
if ($sale_id > 0) {
    $sql = "SELECT * FROM selldata WHERE sale_id = $sale_id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $amount_paid = $row['amount_paid'];
        $amount_pending = $row['amount_pending'];
        $payment_status = $row['payment_status'];
    } else {
        $message = "No record found!";
    }

    $payment_sql = "SELECT payment_mode FROM payments WHERE sale_id = $sale_id";
    $payment_result = mysqli_query($conn, $payment_sql);
    if ($payment_row = mysqli_fetch_assoc($payment_result)) {
        $payment_mode = $payment_row['payment_mode'];
    }
} else {
    $message = "Invalid Sale ID!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount_paid    = floatval($_POST['amount_paid']);
    $amount_pending = floatval($_POST['amount_pending']);
    $payment_mode   = $_POST['payment_mode'];
    
    // Determine payment status
    if ($amount_pending <= 0) {
        $payment_status = "Full Paid";
        $amount_pending = 0;
    } elseif ($amount_paid > 0) {
        $payment_status = "Partial";
    } else {
        $payment_status = "Pending";
    }

    $update_sql = "UPDATE selldata SET 
        amount_paid = '$amount_paid',
        amount_pending = '$amount_pending',
        payment_status = '$payment_status'
        WHERE sale_id = $sale_id";

    $update_payments_sql = "UPDATE payments SET 
        amount_paid = '$amount_paid',
        amount_pending = '$amount_pending',
        payment_mode = '$payment_mode'
        WHERE sale_id = $sale_id";

    if (mysqli_query($conn, $update_sql) && mysqli_query($conn, $update_payments_sql)) {
        $message = "Amounts updated successfully!";
    } else {
        $message = "Update failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Amount</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2vh;
      background-color: #f9f9f9;
      display: flex;
      justify-content: center;
    }
    .box {
      background: white;
      padding: 2vh 2vw;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
      border-radius: 10px;
    }
    h2 {
      text-align: center;
      margin-bottom: 2vh;
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 1vh;
    }
    input, select, a {
      width: 100%;
      padding: 1vh;
      margin-top: 0.5vh;
      margin-bottom: 1.5vh;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .button-row {
      display: flex;
      justify-content: space-between;
      gap: 4%;
    }
    input[type="submit"], .back-btn {
      width: 48%;
      text-align: center;
      background-color: #3498db;
      color: white;
      border: none;
      cursor: pointer;
      text-decoration: none;
      padding: 1vh;
      border-radius: 5px;
    }
    input[type="submit"]:hover {
      background-color: #2980b9;
    }
    .back-btn {
     background-color: #3498db;
    }
    .back-btn:hover {
      background-color: #2980db;
    }
    .message {
      text-align: center;
      color: green;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="box">
  <h2>Update Payment</h2>
  <?php if (!empty($message)): ?><div class="message"> <?= htmlspecialchars($message) ?> </div><?php endif; ?>
  <form method="POST">
    <label>Amount Paid</label>
    <input type="text" name="amount_paid" value="<?= htmlspecialchars($amount_paid) ?>" required>

    <label>Amount Pending</label>
    <input type="text" name="amount_pending" value="<?= htmlspecialchars($amount_pending) ?>" required>

    <label>Payment Mode</label>
    <select name="payment_mode" required>
      <option value="Cash" <?= $payment_mode == 'Cash' ? 'selected' : '' ?>>Cash</option>
      <option value="UPI" <?= $payment_mode == 'UPI' ? 'selected' : '' ?>>UPI</option>
      <option value="Credit" <?= $payment_mode == 'Credit' ? 'selected' : '' ?>>Credit</option>
      <option value="Online" <?= $payment_mode == 'Online' ? 'selected' : '' ?>>Online</option>
    </select>

    <div class="button-row">
      <input type="submit" value="Update Payment">
      <a href="5Dashboard.php" class="back-btn">Back To Dashboard</a>
    </div>
  </form>
</div>
</body>
</html>
