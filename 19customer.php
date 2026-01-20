 <!-- <a href='view_products.php?sale_id=" . urlencode($row['sale_id']) . "' class='button'>View Products</a> -->                    
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

// Search handling for customers
$search = "";
if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $sql = "SELECT * FROM selldata 
            WHERE customer_name LIKE '%$search%' 
               OR customer_contact LIKE '%$search%' 
               OR payment_status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM selldata";
}

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
}

.main {
    margin-left: 250px;
    padding: 30px;
    background-color: #f4f4f4;
    min-height: 100vh;
}

.main h2 {
    margin-bottom: 20px;
    color: black;
}

table {
    border-collapse: collapse;
    width: 100%;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

th, td {
    border: 1px solid #ccc;
    padding: 12px 15px;
    text-align: center;
    font-size: 1rem;
}

th {
    background-color: #eee;
}

a.button {
    padding: 6px 12px;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9rem;
    margin: 0 3px;
    display: inline-block;
    min-width: 60px;
}

a.edit {
    background-color: #dc3545; /* Red for Edit */
}

a.delete {
    background-color: #6c757d; /* Gray for Delete */
}

a.back {
    background-color: #007bff; /* Blue for Back */
}

a.button:hover {
    opacity: 0.85;
}

.search-bar {
    margin-bottom: 20px;
}

.search-bar input[type="text"] {
    padding: 8px;
    width: 300px;
    font-size: 1rem;
}

.search-bar button {
    padding: 8px 12px;
    font-size: 1rem;
    background-color: #007bff;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 4px;
}

.search-bar button:hover {
    background-color: #0056b3;
}

.reset-button,
.back-button {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-left: 8px;
    font-size: 1rem;
    display: inline-block;
    border: none;
    cursor: pointer;
}

.reset-button:hover,
.back-button:hover {
    background-color: #0056b3;
}

@media screen and (max-width: 768px) {
    .main {
        margin-left: 200px;
        padding: 20px;
    }
    table {
        font-size: 0.9rem;
    }
    a.button {
        padding: 5px 8px;
        font-size: 0.8rem;
        min-width: 50px;
    }
}

    </style>
</head>
<body>

<div class="main">
    <h2>Customer List</h2>
    
    <form method="get" class="search-bar">
        <input type="text" name="search" placeholder="Search by customer name, contact, or status" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="reset-button">Reset</a>
        <a href="5dashboard.php" class="back-button">Back To Page</a>
    </form>

    
    <table>
        <tr>
            <th>Customer Name</th>
            <th>Contact</th>
            <th>Total Amount</th>
            <th>Amount Paid</th>
            <th>Pending</th>
            <th>Status</th>
            <th>Sale Date</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['customer_name']) . "</td>
                    <td>" . htmlspecialchars($row['customer_contact']) . "</td>
                    <td>" . htmlspecialchars($row['total_amount']) . "</td>
                    <td>" . htmlspecialchars($row['amount_paid']) . "</td>
                    <td>" . htmlspecialchars($row['amount_pending']) . "</td>
                    <td>" . htmlspecialchars($row['payment_status']) . "</td>
                    <td>" . htmlspecialchars($row['sale_date']) . "</td>
                    <td>
                        <a href='20customer_update.php?sale_id=" . urlencode($row['sale_id']) . "' class='button edit'>Edit</a>
                        </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No customers found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
