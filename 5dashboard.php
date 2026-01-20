<?php
$con = mysqli_connect("localhost", "root", "", "pro_login");
if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: 25_404_error.php");
    exit;
}



// Total Sales
$totalSales = 0;
$q1 = mysqli_query($con, "SELECT SUM(total_amount) AS total FROM selldata");
if ($q1 && $row = mysqli_fetch_assoc($q1)) {
    $totalSales = $row['total'] ?? 0;
}

// Total Products
$totalProducts = 0;
$q2 = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM proadd");
if ($q2 && $row = mysqli_fetch_assoc($q2)) {
    $totalProducts = $row['cnt'] ?? 0;
}

// Low Stock Count
$lowStock = 0;
$q3 = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM proadd WHERE p_qty <= 5");
if ($q3 && $row = mysqli_fetch_assoc($q3)) {
    $lowStock = $row['cnt'] ?? 0;
}

// Total Customers
$totalCustomers = 0;
$q4 = mysqli_query($con, "SELECT COUNT(DISTINCT customer_name) AS cnt FROM selldata");
if ($q4 && $row = mysqli_fetch_assoc($q4)) {
    $totalCustomers = $row['cnt'] ?? 0;
}

// Sales Summary (Last 5)
$salesData = mysqli_query($con, "SELECT sale_id, customer_name, sale_date, total_amount, payment_status FROM selldata ORDER BY sale_id DESC LIMIT 5");

// Low Stock Products
$lowStockData = mysqli_query($con, "SELECT p_name, p_qty FROM proadd WHERE p_qty <= 5 ORDER BY p_qty ASC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Store Dashboard</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
    html, body { width: 100vw; height: 100vh; overflow: hidden; }
    table.layout { width: 100vw; height: 100vh; border-collapse: collapse; }
    td.sidebar-cell { width: 15vw; background-color: #f4f4f4; vertical-align: top; }
    td.content-cell { width: 85vw; background: #ecf0f1; vertical-align: top; }
    .main-content { padding: 2vh 2vw; height: calc(100vh - 6vh - 10vh); overflow-y: auto; }
    .dashboard-cards { display: flex; gap: 2vw; flex-wrap: wrap; margin-bottom: 3vh; }
    .card { flex: 1 1 auto; background: white; padding: 2vh 2vw; border-radius: 1vw; box-shadow: 0 0.5vh 1vh rgba(0, 0, 0, 0.1); text-align: center; min-width: 20vw; }
    .card h3 { margin-bottom: 1vh; color: #34495e; font-size: 1.3vw; }
    .card p { font-size: 2vw; font-weight: bold; }
    .card.total-sales { color: #27ae60; }
    .card.total-products { color: #2980b9; }
    .card.low-stock { color: #e74c3c; }
    .card.total-customers { color: #8e44ad; }
    .tables { display: flex; flex-wrap: wrap; gap: 2vw; }
    .table-wrapper { flex: 1 1 40vw; background: white; padding: 2vh 2vw; border-radius: 1vw; box-shadow: 0 0.5vh 1vh rgba(0, 0, 0, 0.1); overflow-x: auto; }
    .table-wrapper h3 { margin-bottom: 1vh; font-size: 1.3vw; color: #34495e; }
    table.data { width: 100%; border-collapse: collapse; }
    thead { background-color: #16a085; color: white; }
    thead.low-stock-header { background-color: #e74c3c; }
    th, td { padding: 1.2vh 1vw; border: 0.1vh solid #ddd; font-size: 1vw; text-align: left; }
    footer { height: 6vh; background-color: #34495e; color: white; display: flex; justify-content: center; align-items: center; font-size: 1vw; }
  </style>
</head>
<body>

<table class="layout">
  <tr style="height: 10vh;">
        <td colspan="2"><?php include 'header.php'; ?></td>
  </tr>

  <tr style="height: auto;">
    <td class="sidebar-cell">
      <?php include 'sidebar.php'; ?>
    </td>

    <td class="content-cell">
      <div class="main-content">
        <section class="dashboard-cards">
          <div class="card total-sales"><h3>Total Sales</h3><p>₹ <?php echo number_format($totalSales,2); ?></p></div>
          <div class="card total-products"><h3>Total Products</h3><p><?php echo $totalProducts; ?></p></div>
          <div class="card low-stock"><h3>Low Stock</h3><p><?php echo $lowStock; ?> Items</p></div>
          <div class="card total-customers"><h3>Total Customers</h3><p><?php echo $totalCustomers; ?></p></div>
        </section>

        <section class="tables">
          <div class="table-wrapper">
            <h3>Sales Summary</h3>
            <table class="data">
              <thead>
                <tr>
                  <th>Invoice No</th>
                  <th>Customer</th>
                  <th>Date</th>
                  <th>Total (₹)</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if(mysqli_num_rows($salesData) > 0){
                    while($row = mysqli_fetch_assoc($salesData)){
                        echo "<tr>
                                <td>{$row['sale_id']}</td>
                                <td>{$row['customer_name']}</td>
                                <td>{$row['sale_date']}</td>
                                <td>{$row['total_amount']}</td>
                                <td>{$row['payment_status']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No Sales Found</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>

          <div class="table-wrapper">
            <h3 style="color:#e74c3c;">Low Stock Alerts</h3>
            <table class="data">
              <thead class="low-stock-header">
                <tr>
                  <th>Product</th>
                  <th>Stock Left</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if(mysqli_num_rows($lowStockData) > 0){
                    while($row = mysqli_fetch_assoc($lowStockData)){
                        echo "<tr>
                                <td>{$row['p_name']}</td>
                                <td>{$row['p_qty']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No Low Stock Items</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </td>
  </tr>
</table>

<?php include 'footer.php'; ?>
</body>
</html>
