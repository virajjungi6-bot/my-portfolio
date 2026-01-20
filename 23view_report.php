<?php

session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: 25_404_error.php");
    exit;
}
$con = mysqli_connect("localhost", "root", "", "pro_login");
if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

$type = isset($_GET['type']) ? $_GET['type'] : 'daily';

switch ($type) {
    case 'weekly':
        $sql = "SELECT YEARWEEK(sale_date, 1) AS period,
                       MIN(sale_date) AS start_date,
                       MAX(sale_date) AS end_date,
                       SUM(total_amount) AS total_sales,
                       COUNT(*) AS total_orders
                FROM selldata
                GROUP BY YEARWEEK(sale_date, 1)
                ORDER BY start_date DESC";
        break;

    case 'monthly':
        $sql = "SELECT DATE_FORMAT(sale_date, '%Y-%m') AS period,
                       DATE_FORMAT(sale_date, '%M %Y') AS month_name,
                       SUM(total_amount) AS total_sales,
                       COUNT(*) AS total_orders
                FROM selldata
                GROUP BY DATE_FORMAT(sale_date, '%Y-%m')
                ORDER BY period DESC";
        break;

    default:
        $sql = "SELECT sale_date AS period,
                       SUM(total_amount) AS total_sales,
                       COUNT(*) AS total_orders
                FROM selldata
                GROUP BY sale_date
                ORDER BY sale_date DESC";
}

$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f8f8f8;
        }
        h2 {
            margin-bottom: 20px;
        }
        .btn-group {
            margin-bottom: 15px;
        }
        a {
            display: inline-block;
            padding: 8px 14px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
            font-size: 14px;
        }
        a:hover {
            background: #0056b3;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>

<h2>Sales Report - <?= ucfirst($type) ?></h2>

<div class="btn-group">
    <a href="5dashboard.php">Back to Dashboard</a>
    <a href="?type=daily">Daily</a>
    <a href="?type=weekly">Weekly</a>
    <a href="?type=monthly">Monthly</a>
</div>

<table>
    <tr>
        <?php if ($type == 'weekly'): ?>
            <th>Week Start</th>
            <th>Week End</th>
        <?php elseif ($type == 'monthly'): ?>
            <th>Month</th>
        <?php else: ?>
            <th>Date</th>
        <?php endif; ?>
        <th>Total Orders</th>
        <th>Total Sales (₹)</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <?php if ($type == 'weekly'): ?>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
            <?php elseif ($type == 'monthly'): ?>
                <td><?= $row['month_name'] ?></td>
            <?php else: ?>
                <td><?= $row['period'] ?></td>
            <?php endif; ?>
            <td><?= $row['total_orders'] ?></td>
            <td><?= number_format($row['total_sales'], 2) ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
