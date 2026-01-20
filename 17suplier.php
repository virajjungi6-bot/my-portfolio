<!--<a class='button delete' href='10deletepro.php?id=" . urlencode($row['id']) . "' onclick=\"return confirm('Are you sure to delete this product?');\">Delete</a>-->
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

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $sql = "SELECT id, p_name, category, product_type, supplier_name, supplier_contact, city_name, c_price, s_price, p_qty, p_date 
            FROM proadd 
            WHERE p_name LIKE '%$search%' 
               OR category LIKE '%$search%' 
               OR product_type LIKE '%$search%' 
               OR supplier_name LIKE '%$search%'";
} else {
    $sql = "SELECT id, p_name, category, product_type, supplier_name, supplier_contact, city_name, c_price, s_price, p_qty, p_date 
            FROM proadd";
}

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List with Search/Edit/Delete</title>
    <style>
      /* same CSS as before */
/* same CSS as before */
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
    background-color: #dc3545; 
}

a.delete {
    background-color: #dc3545; 
}

a.button:hover {
    opacity: 0.85;
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

.main a.back {
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    padding: 8px 12px;
    display: inline-block;
    text-align: center;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
    margin: 2px;
}

.main a.back:hover {
    background-color: #5a6268;
}

/* Search and Reset Buttons */
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

.reset-button,.back-button {
    padding: 8px 12px;
    background-color: #007bff; 
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-left: 8px;
    font-size: 1rem;
    display: inline-block;
}

.reset-button:hover,.back-button:hover{
    background-color: #0056b3;
}

    </style>
</head>
<body>

<div class="main">
    <h2>Product List</h2>
   

    <form method="get" class="search-bar">
        <input type="text" name="search" placeholder="Search by product name, category, etc." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="reset-button">Reset</a>
         <a href="5dashboard.php" class="back-button">Back To Page</a>
    </form>

    <table>
        <tr>
            <th>Product Name</th>
            <th>Supplier</th>
            <th>Contact</th>
            <th>City</th>
            <th>Purchase Date</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['p_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['supplier_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['supplier_contact']) . "</td>";
                echo "<td>" . htmlspecialchars($row['city_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['p_date']) . "</td>";
                echo "<td>
                        <a class='button edit' href='18suplier_update.php?id=" . urlencode($row['id']) . "'>Edit</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found</td></tr>";
        }

        mysqli_close($con);
        ?>
    </table>
</div>

</body>
</html>
