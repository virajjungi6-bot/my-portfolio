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

$search = '';
if (isset($_GET['search']) && $_GET['search'] != '') {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $sql = "SELECT sale_id, customer_name, customer_contact, total_amount, amount_paid, amount_pending, payment_status 
            FROM selldata 
            WHERE amount_pending > 0 
              AND payment_status = 'Pending'
              AND (customer_name LIKE '%$search%' 
               OR customer_contact LIKE '%$search%')";
} else {
    $sql = "SELECT sale_id, customer_name, customer_contact, total_amount, amount_paid, amount_pending, payment_status
            FROM selldata
            WHERE amount_pending > 0 
              AND payment_status = 'Pending'";
}

$result = mysqli_query($con, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Debit - Credit Data</title>
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}
html, body {
  width: 100vw;
  height: 100vh;
  background: #ecf0f1;
}
h3 {
  width: 100vw;
  padding: 2vh 2vw;
  background: #2c3e50;
  color: white;
  font-size: 2vh;
}
.search-bar {
  display: flex;
  align-items: center;
  gap: 1vw;
  padding: 2vh 2vw;
}
.search-bar input[type="text"] {
  padding: 1vh 1vw;
  width: 25vw;
  font-size: 1.8vh;
}
.search-bar button, .reset-button ,a{
  padding: 1vh 1.5vw;
  font-size: 1.8vh;
  border: none;
  cursor: pointer;
  border-radius: 0.5vh;
}
.search-bar button,a {
  background-color: #007bff;
  color: white;
}
.reset-button,a {
  background-color: #007bff;
  color: white;
  text-decoration: none;
}

.table-wrap {
  width: 96vw;
  margin: 0 auto;
  padding: 2vh 0;
}
table.layout {
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 0.4vh 1vh rgba(0,0,0,0.1);
}
table.layout th, table.layout td {
  border: 0.1vh solid #ccc;
  padding: 1.2vh 1vw;
  text-align: center;
  font-size: 1.8vh;
}
table.layout th {
  background: #34495e;
  color: white;
}
table.layout tr:hover td {
  background: #f5f5f5;
}
.btn-update {
  background: #007bff;
  color: white;
  padding: 0.8vh 1.2vw;
  border-radius: 0.5vh;
  text-decoration: none;
  display: inline-block;
}
.btn-update:hover {
  background: #0056b3;
}
</style>
</head>
<body>

<h3>Debit - Credit Data</h3>
<form method="get" class="search-bar">
    <input type="text" name="search" placeholder="pending payment" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <a href="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?>" class="reset-button">Reset</a>
    <a href="5dashboard.php">Home Page</a>

</form>

<div class="table-wrap">
    <table class="layout">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Customer Contact</th>
                <th>Total Amount</th>
                <th>Paid</th>
                <th>Pending</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sale_id = htmlspecialchars($row['sale_id']);
        echo "<tr>
                <td>" . htmlspecialchars($row['customer_name']) . "</td>
                <td>" . htmlspecialchars($row['customer_contact']) . "</td>
                <td>" . htmlspecialchars($row['total_amount']) . "</td>
                <td>" . htmlspecialchars($row['amount_paid']) . "</td>
                <td>" . htmlspecialchars($row['amount_pending']) . "</td>
                <td>" . htmlspecialchars($row['payment_status']) . "</td>
                <td><a href='16cre_deb_update.php?sale_id=$sale_id' class='btn-update'>Update Payments</a></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No records found</td></tr>";
}
?>
        </tbody>
    </table>
</div>

</body>
</html>
