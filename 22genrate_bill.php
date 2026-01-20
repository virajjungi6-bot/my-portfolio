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

$sale_id = isset($_GET['sale_id']) ? (int)$_GET['sale_id'] : 0;

// Get sale details (selldata)
$sql_sale = "SELECT * FROM selldata WHERE sale_id = $sale_id";
$sale = mysqli_fetch_assoc(mysqli_query($con, $sql_sale));

if (!$sale) {
    die("Sale record not found.");
}

// Get products for that sale (sellproducts)
$sql_products = "SELECT * FROM sellproducts WHERE sale_id = $sale_id";
$products = mysqli_query($con, $sql_products);
?>
<!DOCTYPE html>
<html>
<head>
<title>Bill #<?= $sale_id ?></title>
<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #eee; }
    .back-btn {
        display: inline-block;
        background: #007bff;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        margin-top: 20px;
    }
    .back-btn:hover {
        background: #0056b3;
    }
</style>
</head>
<body>
    <h2>Bill Receipt</h2>
    <p><strong>Customer:</strong> <?= htmlspecialchars($sale['customer_name']) ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars($sale['customer_contact']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($sale['sale_date']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($sale['payment_status']) ?></p>

    <table>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Type</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        <?php while($p = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><?= htmlspecialchars($p['p_name']) ?></td>
                <td><?= htmlspecialchars($p['category']) ?></td>
                <td><?= htmlspecialchars($p['product_type']) ?></td>
                <td><?= htmlspecialchars($p['qty_sold']) ?></td>
                <td><?= number_format($p['sell_price'], 2) ?></td>
                <td><?= number_format($p['subtotal'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <th colspan="5" style="text-align:right;">Total</th>
            <th><?= number_format($sale['total_amount'], 2) ?></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:right;">Amount Paid</th>
            <th><?= number_format($sale['amount_paid'], 2) ?></th>
        </tr>
        <tr>
            <th colspan="5" style="text-align:right;">Pending</th>
            <th><?= number_format($sale['amount_pending'], 2) ?></th>
        </tr>
    </table>

    <a href="5dashboard.php" class="back-btn">⬅ Back to Page</a>

    <script>
        window.print();
    </script>
</body>
</html>
