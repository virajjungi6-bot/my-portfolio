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

// Search filters
$search_name = isset($_GET['search_name']) ? mysqli_real_escape_string($con, $_GET['search_name']) : ''; 
$search_date = isset($_GET['search_date']) ? mysqli_real_escape_string($con, $_GET['search_date']) : '';  

// Base query with JOIN
$sql = "SELECT sd.sale_id, sd.customer_name, sd.customer_contact, sd.total_amount, 
               sd.amount_paid, sd.amount_pending, sd.payment_status, sd.sale_date,
               sp.p_name, sp.sell_price, sp.qty_sold
        FROM selldata sd
        LEFT JOIN sellproducts sp ON sd.sale_id = sp.sale_id
        WHERE 1";

if ($search_name !== '') {     
    $sql .= " AND sd.customer_name LIKE '%$search_name%'"; 
}  

if ($search_date !== '') {     
    $sql .= " AND sd.sale_date = '$search_date'"; 
}  

$result = mysqli_query($con, $sql); 
?>  

<!DOCTYPE html> 
<html> 
<head>     
    <title>Sell Records</title>     
    <style>         
        body { font-family: Arial, sans-serif; padding: 20px; background: #f8f8f8; }         
        h2 { margin-bottom: 15px; }         
        form { background: #fff; padding: 15px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0px 0px 5px rgba(0,0,0,0.1); }         
        input[type="text"], input[type="date"] { padding: 8px 10px; margin-right: 10px; border: 1px solid #ccc; border-radius: 4px; }         
        button { padding: 8px 15px; background: #27ae60; color: #fff; border: none; border-radius: 4px; cursor: pointer; }         
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 0 5px rgba(0,0,0,0.1); }         
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }         
        th { background-color: #007bff; color: white; }         
        .action-btn,a { text-decoration: none; padding: 5px 10px; color: white; border-radius: 4px; font-size: 0.9rem; margin: 2px; }         
        .edit-btn,a { background-color: #3498db; }         
        .delete-btn { background-color: #e74c3c; }     
    </style> 
</head> 
<body>  

<h2>Sell Management</h2>  

<form method="get" action="">     
    <input type="text" name="search_name" placeholder="Customer Name" value="<?= htmlspecialchars($search_name) ?>">     
    <input type="date" name="search_date" value="<?= htmlspecialchars($search_date) ?>">     
    <button type="submit">Search</button>     
    <a href="?" style="margin-left:10px;">Reset</a>     
    <a href="5dashboard.php" style="margin-left:10px;">Back to Dashboard</a>      
</form>  

<table>     
    <thead>         
        <tr>             
            <th>Sale ID</th>             
            <th>Customer Name</th>             
            <th>Contact</th>             
            <th>Total</th>             
            <th>Paid</th>             
            <th>Pending</th>             
            <th>Status</th>             
            <th>Sale Date</th>             
            <th>Product</th>
            <th>Sell Price</th>
            <th>Quantity</th>
            <th>Actions</th>         
        </tr>     
    </thead>     
    <tbody>     
    <?php if (mysqli_num_rows($result) > 0): ?>         
        <?php while($row = mysqli_fetch_assoc($result)): ?>             
            <tr>                 
                <td><?= $row['sale_id'] ?></td>                 
                <td><?= htmlspecialchars($row['customer_name']) ?></td>                 
                <td><?= htmlspecialchars($row['customer_contact']) ?></td>                 
                <td><?= number_format($row['total_amount'], 2) ?></td>                 
                <td><?= number_format($row['amount_paid'], 2) ?></td>                 
                <td><?= number_format($row['amount_pending'], 2) ?></td>                 
                <td><?= htmlspecialchars($row['payment_status']) ?></td>                 
                <td><?= $row['sale_date'] ?></td>                 
                <td><?= htmlspecialchars($row['p_name']) ?></td>
                <td><?= number_format($row['sell_price'], 2) ?></td>
                <td><?= (int)$row['qty_sold'] ?></td>
                <td>                  
                    <a class="action-btn edit-btn" href="13sellfinal.php?sale_id=<?= htmlspecialchars($row['sale_id']); ?>">Edit</a>                   
                    <a class="action-btn delete-btn" href="14selldelete.php?sale_id=<?= $row['sale_id'] ?>&action=delete" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</a>                  
                </td>             
            </tr>         
        <?php endwhile; ?>     
    <?php else: ?>         
        <tr><td colspan="12">No records found.</td></tr>     
    <?php endif; ?>      
    </tbody> 
</table>  

</body> 
</html>
