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
    'p_name' => '',
    'supplier_name' => '',
    'supplier_contact' => '',
    'city_name' => '',
    'p_date' => ''
];

// 1. Editing check
if (isset($_GET['id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['id']);
    $res = mysqli_query($con, "SELECT p_name, supplier_name, supplier_contact, city_name, p_date FROM proadd WHERE id = $edit_id");

    if ($res && mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    } else {
        $message = "❌ Product not found.";
        $edit_mode = false;
    }
}

// 2. Form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edit_id = intval($_POST['edit_id']);

    $p_name = mysqli_real_escape_string($con, $_POST['p_name']);
    $supplier_name = mysqli_real_escape_string($con, $_POST['supplier_name']);
    $supplier_contact = mysqli_real_escape_string($con, $_POST['supplier_contact']);
    $city_name = mysqli_real_escape_string($con, $_POST['city_name']);
    $p_date = mysqli_real_escape_string($con, $_POST['p_date']);

    if ($p_name && $supplier_name && $supplier_contact && $city_name && $p_date) {
        $update_sql = "UPDATE proadd SET 
            p_name='$p_name',
            supplier_name='$supplier_name',
            supplier_contact='$supplier_contact',
            city_name='$city_name',
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
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
            font-weight: 600;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }
        input[type="submit"], .back-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .back-btn {
            background-color: #007bff;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        .message {
            background: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
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

        <label>Supplier Name</label>
        <input type="text" name="supplier_name" required value="<?php echo htmlspecialchars($edit_data['supplier_name']); ?>">

        <label>Supplier Contact</label>
        <input type="text" name="supplier_contact" required value="<?php echo htmlspecialchars($edit_data['supplier_contact']); ?>">

        <label>City Name</label>
        <input type="text" name="city_name" required value="<?php echo htmlspecialchars($edit_data['city_name']); ?>">

        <label>Purchase Date</label>
        <input type="date" name="p_date" required value="<?php echo htmlspecialchars($edit_data['p_date']); ?>">

        <div class="btn-container">
            <input type="submit" value="Update">
            <a href="5dashboard.php" class="back-btn">Back</a>
        </div>
    </form>
    <?php else: ?>
        <p>❌ Product data not available to edit.</p>
    <?php endif; ?>
</div>
</body>
</html>
