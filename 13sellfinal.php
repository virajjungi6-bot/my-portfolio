<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "pro_login");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['sale_id'])) {
    die("Sale ID not provided.");
}
$sale_id = (int)$_GET['sale_id'];
$message = "";

// ----------------- FETCH OLD DATA -----------------
$sql = "SELECT * FROM selldata WHERE sale_id = $sale_id";
$res = mysqli_query($conn, $sql);
$sale = mysqli_fetch_assoc($res);

$sql_product = "SELECT * FROM sellproducts WHERE sale_id = $sale_id LIMIT 1";
$res_product = mysqli_query($conn, $sql_product);
$product = mysqli_fetch_assoc($res_product);

$sql_payment = "SELECT * FROM payments WHERE sale_id = $sale_id LIMIT 1";
$res_payment = mysqli_query($conn, $sql_payment);
$payment = mysqli_fetch_assoc($res_payment);

// ----------------- UPDATE LOGIC -----------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name    = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_contact = mysqli_real_escape_string($conn, $_POST['customer_contact']);
    $sale_date        = mysqli_real_escape_string($conn, $_POST['sale_date']);
    $amount_paid      = (float)$_POST['amount_paid'];
    $payment_status   = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $payment_mode     = mysqli_real_escape_string($conn, $_POST['payment_mode']);
    $sell_qty_input   = (int)$_POST['sell_qty']; 

    if ($product) {
        $p_id       = $product['p_id'];
        $sell_price = $product['sell_price'];
        $old_qty    = $product['qty_sold'];

        // ✅ new sell qty + subtotal
        $final_qty     = $sell_qty_input;
        $final_subtotal = $final_qty * $sell_price;

        mysqli_query($conn, "UPDATE sellproducts 
                             SET qty_sold = $final_qty, subtotal = $final_subtotal 
                             WHERE sp_id = {$product['sp_id']}");

        // ✅ Update stock (adjust only difference)
        $qty_diff = $final_qty - $old_qty;
        if ($qty_diff != 0) {
            mysqli_query($conn, "UPDATE proadd 
                                 SET p_qty = p_qty - $qty_diff 
                                 WHERE id = $p_id");
        }

        $total_amount   = $final_subtotal;
        $amount_pending = $total_amount - $amount_paid;
        if ($amount_pending < 0) $amount_pending = 0;
    }

    $update_sql = "UPDATE selldata SET 
        customer_name    = '$customer_name',
        customer_contact = '$customer_contact',
        total_amount     = '$total_amount',
        amount_paid      = '$amount_paid',
        amount_pending   = '$amount_pending',
        payment_status   = '$payment_status',
        sale_date        = '$sale_date'
        WHERE sale_id    = $sale_id";

    $update_payments_sql = "UPDATE payments SET 
        amount_paid    = '$amount_paid',
        amount_pending = '$amount_pending',
        payment_mode   = '$payment_mode',
        payment_date   = '$sale_date'
        WHERE sale_id  = $sale_id";

    if (mysqli_query($conn, $update_sql) && mysqli_query($conn, $update_payments_sql)) {
        $message = "<p class='success'>✅ Sale, Payment & Stock updated successfully!</p>";
    } else {
        $message = "<p class='error'>❌ Update failed: " . mysqli_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify Sale</title>
    <style>
        body {font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0;}
        .container {display: flex; justify-content: center; align-items: center; min-height: 100vh;}
        .card {background: #fff; width: 420px; padding: 25px; border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.15);}
        h2 {text-align: center; color: #333; margin-bottom: 20px;}
        form label {font-weight: bold; display: block; margin: 10px 0 5px; color: #444;}
        form input, form select {width: 100%; padding: 10px; font-size: 15px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;}
        form input:focus, form select:focus {outline: none; border-color: #007bff; box-shadow: 0 0 5px rgba(0,123,255,0.4);}
        button{width: 100%; padding: 12px; margin-top: 15px; background: #007bff; border: none; color: #fff; font-size: 16px; font-weight: bold; border-radius: 6px; cursor: pointer; transition: background 0.3s ease;}
        button:hover {background: #0056b3;}
        .btn-back {display: block; text-align: center; text-decoration: none; background: #6c757d; color: #fff; padding: 12px; margin-top: 10px; font-size: 16px; font-weight: bold; border-radius: 6px; transition: background 0.3s ease;}
        .btn-back:hover {background: #5a6268;}
        .msg {text-align: center; margin-bottom: 15px;}
        .msg p {padding: 10px; border-radius: 6px;}
        .msg .success {background: #d4edda; color: #155724;}
        .msg .error {background: #f8d7da; color: #721c24;}
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Modify Sale ID: <?php echo $sale_id; ?></h2>
        <div class="msg"><?php echo $message; ?></div>

        <form method="POST">
            <label>Customer Name:</label>
            <input type="text" name="customer_name" value="<?php echo $sale['customer_name']; ?>">

            <label>Customer Contact:</label>
            <input type="text" name="customer_contact" value="<?php echo $sale['customer_contact']; ?>">

            <label>Sale Date:</label>
            <input type="date" name="sale_date" value="<?php echo $sale['sale_date']; ?>">

            <label>Sell Quantity:</label>
            <input type="number" name="sell_qty" value="<?php echo $product['qty_sold']; ?>" min="0">

            <label>Amount Paid:</label>
            <input type="number" step="0.01" name="amount_paid" value="<?php echo $sale['amount_paid']; ?>">

            <label>Payment Status:</label>
            <select name="payment_status">
                <option value="Pending" <?php if ($sale['payment_status']=="Pending") echo "selected"; ?>>Pending</option>
                <option value="Partial" <?php if ($sale['payment_status']=="Partial") echo "selected"; ?>>Partial</option>
                <option value="Full Paid" <?php if ($sale['payment_status']=="Full Paid") echo "selected"; ?>>Full Paid</option>
            </select>

            <label>Payment Mode:</label>
            <select name="payment_mode" >
                <option value="Cash" <?php if ($payment['payment_mode']=="Cash") echo "selected"; ?>>Cash</option>
                <option value="UPI" <?php if ($payment['payment_mode']=="UPI") ; ?>>UPI</option>
                <option value="Card" <?php if ($payment['payment_mode']=="Card") ; ?>>Card</option>
                <option value="Bank Transfer" <?php if ($payment['payment_mode']=="Bank Transfer")  ; ?>>Bank Transfer</option>
            </select>

            <button type="submit">Update Sale</button>
            <a class="btn-back" href="12sellmodify.php">⬅ Back to Page</a>
        </form>
    </div>
</div>
</body>
</html>
