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

$message = '';
$edit_mode = false;
$edit_id = null;
$edit_data = [
    'p_name' => '', 'category' => '', 'product_type' => '',
    'supplier_name' => '', 'supplier_contact' => '', 'city_name' => '',
    'c_price' => '', 's_price' => '', 'p_qty' => '', 'p_date' => ''
];

if (isset($_GET['id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['id']);
    $res = mysqli_query($con, "SELECT * FROM proadd WHERE id = $edit_id");

    if ($res && mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    } else {
        $message = "❌ Product not found.";
        $edit_mode = false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edit_id = intval($_POST['edit_id']);

    $p_name = mysqli_real_escape_string($con, $_POST['p_name']);
    $category = $_POST['category'];
    $product_type = $_POST['product_type'];
    $supplier_name = mysqli_real_escape_string($con, $_POST['supplier_name']);
    $supplier_contact = mysqli_real_escape_string($con, $_POST['supplier_contact']);
    $city_name = mysqli_real_escape_string($con, $_POST['city_name']);
    $c_price = floatval($_POST['c_price']);
    $s_price = floatval($_POST['s_price']);
    $p_qty = intval($_POST['p_qty']);
    $p_date = mysqli_real_escape_string($con, $_POST['p_date']);

    if ($p_name && $category && $product_type && $supplier_name && $supplier_contact && $city_name && $c_price > 0 && $s_price > 0 && $p_qty > 0 && $p_date) {
        $update_sql = "UPDATE proadd SET 
            p_name='$p_name',
            category='$category',
            product_type='$product_type',
            supplier_name='$supplier_name',
            supplier_contact='$supplier_contact',
            city_name='$city_name',
            c_price=$c_price,
            s_price=$s_price,
            p_qty=$p_qty,
            p_date='$p_date'
            WHERE id=$edit_id";

        if (mysqli_query($con, $update_sql)) {
            header("Location: 8showproduct.php");  
            exit();
        } else {
            $message = "❌ Update failed: " . mysqli_error($con);
        }
    } else {
        $message = "❌ Please fill all fields correctly.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 520px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .btn-back {
           background: #007bff;
            color: white;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            width: 48%;
            font-size: 16px;
        }
        .btn-update {
           background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 6px;
            width: 48%;
            font-size: 16px;
        }
       
        .message {
            background: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($edit_mode): ?>
    <form method="post">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">

        <label>Product Name</label>
        <input type="text" name="p_name" required value="<?php echo htmlspecialchars($edit_data['p_name']); ?>">

        <label>Category</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <?php
            $categories = ["Groceries","Personal Care","Dairy Products","Snacks & Namkeen","Spices & Masalas","Oil & Ghee","Beverages","Cleaning & Household","Bakery & Biscuits","Stationery"];
            foreach ($categories as $cat) {
                $sel = ($cat === $edit_data['category']) ? "selected" : "";
                echo "<option value=\"$cat\" $sel>$cat</option>";
            }
            ?>
        </select>

        <label>Product Type</label>
        <select name="product_type" required>
            <option value="">-- Select --</option>
            <option value="new" <?php echo ($edit_data['product_type'] === 'new') ? 'selected' : ''; ?>>New</option>
            <option value="old" <?php echo ($edit_data['product_type'] === 'old') ? 'selected' : ''; ?>>Old</option>
        </select>

        <label>Supplier Name</label>
        <input type="text" name="supplier_name" required value="<?php echo htmlspecialchars($edit_data['supplier_name']); ?>">

        <label>Supplier Contact</label>
        <input type="text" name="supplier_contact" required value="<?php echo htmlspecialchars($edit_data['supplier_contact']); ?>">

        <label>City Name</label>
        <input type="text" name="city_name" required value="<?php echo htmlspecialchars($edit_data['city_name']); ?>">

        <label>Cost Price</label>
        <input type="number" step="0.01" name="c_price" required value="<?php echo htmlspecialchars($edit_data['c_price']); ?>">

        <label>Sell Price</label>
        <input type="number" step="0.01" name="s_price" required value="<?php echo htmlspecialchars($edit_data['s_price']); ?>">

        <label>Quantity</label>
        <input type="number" name="p_qty" required value="<?php echo htmlspecialchars($edit_data['p_qty']); ?>">

        <label>Purchase Date</label>
        <input type="date" name="p_date" required value="<?php echo htmlspecialchars($edit_data['p_date']); ?>">

        <div class="btn-container">
            <a href="5dashboard.php" class="btn-back">⬅ Back</a>
            <input type="submit" class="btn-update" value="Update Product">
        </div>
    </form>
    <?php else: ?>
        <p>❌ Product data not available to edit.</p>
    <?php endif; ?>
</div>
</body>
</html>
