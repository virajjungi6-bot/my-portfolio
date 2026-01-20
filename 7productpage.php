<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: 25_404_error.php");
    exit;
}

$message = '';
$errors = [];

// DB Connection
$con = mysqli_connect("localhost", "root", "", "pro_login");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get next auto-increment product ID
$result = mysqli_query($con, "SHOW TABLE STATUS LIKE 'proadd'");
$row = mysqli_fetch_assoc($result);
$next_product_id = $row['Auto_increment'] ?? 1;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $product_type = trim($_POST['product_type']);
    $supplier_name = trim($_POST['supplier_name']);
    $supplier_contact = trim($_POST['supplier_contact']);
    $city_name = trim($_POST['city_name']);
    $cost_price = trim($_POST['cost_price']);
    $sell_price = trim($_POST['sell_price']);
    $qty = trim($_POST['qty']);
    $date = trim($_POST['date']);

    // Validation
    if ($product_name == '') $errors['product_name'] = 'Product Name is required.';
    if ($category == '') $errors['category'] = 'Category is required.';
    if ($product_type == '') $errors['product_type'] = 'Product Type is required.';
    if ($supplier_name == '') $errors['supplier_name'] = 'Supplier Name is required.';
    if ($supplier_contact == '') $errors['supplier_contact'] = 'Supplier Contact is required.';
    elseif (!preg_match('/^[0-9]{10}$/', $supplier_contact))
        $errors['supplier_contact'] = 'Contact must be exactly 10 digits.';
    if ($city_name == '') $errors['city_name'] = 'City Name is required.';
    if ($cost_price == '' || !is_numeric($cost_price) || $cost_price <= 0) $errors['cost_price'] = 'Enter valid Cost Price.';
    if ($sell_price == '' || !is_numeric($sell_price) || $sell_price <= 0) $errors['sell_price'] = 'Enter valid Sell Price.';
    if ($qty == '' || !is_numeric($qty) || $qty <= 0) $errors['qty'] = 'Enter valid Quantity.';
    if ($date == '') $errors['date'] = 'Date is required.';

    // Insert if no errors
    if (empty($errors)) {
        $product_name_esc = mysqli_real_escape_string($con, $product_name);
        $category_esc = mysqli_real_escape_string($con, $category);
        $product_type_esc = mysqli_real_escape_string($con, $product_type);
        $supplier_name_esc = mysqli_real_escape_string($con, $supplier_name);
        $supplier_contact_esc = mysqli_real_escape_string($con, $supplier_contact);
        $city_name_esc = mysqli_real_escape_string($con, $city_name);
        $cost_price_val = floatval($cost_price);
        $sell_price_val = floatval($sell_price);
        $qty_val = intval($qty);
        $date_esc = mysqli_real_escape_string($con, $date);

        $insert_query = "INSERT INTO proadd 
            (p_name, category, product_type, supplier_name, supplier_contact, city_name, c_price, s_price, p_qty, p_date)
            VALUES ('$product_name_esc', '$category_esc', '$product_type_esc', '$supplier_name_esc', '$supplier_contact_esc', '$city_name_esc', $cost_price_val, $sell_price_val, $qty_val, '$date_esc')";

        if (mysqli_query($con, $insert_query)) {
            if ($product_type === 'new') {
                mysqli_query($con, "DELETE FROM proadd WHERE p_name = '$product_name_esc' AND product_type = 'old'");
            }
            header("Location: 8showproduct.php?success=1");
            exit();
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial,sans-serif; background:#f0f0f0; padding:2vh 2vw; display:flex; justify-content:center; align-items:flex-start; min-height:100vh; }
.container { width:90vw; max-width:450px; background:#fff; padding:3vh 3vw; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1);}
h2 { text-align:center; margin-bottom:2vh; color:#2c3e50; }
label { display:block; margin-bottom:0.5vh; font-weight:bold; color:#333; }
.required { color:red; margin-left:2px; }
input[type="text"], input[type="date"], select { width:100%; padding:1vh 1vw; margin-bottom:0.5vh; font-size:1rem; border:1px solid #ccc; border-radius:5px; }
input[type="submit"] { width:100%; padding:1vh; background:#007bff; color:#fff; font-size:1rem; margin-top:10px; border:none; border-radius:5px; cursor:pointer; }
a { display:block; text-align:center; width:80%; margin:0 auto; padding:1vh; background:#007bff; color:#fff; font-size:1rem; border-radius:5px; text-decoration:none; margin-top:5vh; }
.message { margin-bottom:1.5vh; padding:1vh; color:#fff; background-color:#4caf50; border-radius:5px; text-align:center; font-weight:bold; }
.error { color:red; font-size:14px; margin-bottom:1vh; }
</style>
</head>
<body>

<div class="container">
  <h2>Add Your Product</h2>

  <?php if ($message != ''): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <form method="post">
    <label>Next Product ID</label>
    <input type="text" disabled value="<?php echo $next_product_id; ?>">

    <label>Product Name <span class="required">*</span></label>
    <input type="text" name="product_name" placeholder="Enter Product Name" value="<?php echo htmlspecialchars($_POST['product_name'] ?? ''); ?>">
    <?php if(isset($errors['product_name'])) echo "<div class='error'>{$errors['product_name']}</div>"; ?>

    <label>Category <span class="required">*</span></label>
    <input type="text" name="category" placeholder="Enter Category" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
    <?php if(isset($errors['category'])) echo "<div class='error'>{$errors['category']}</div>"; ?>

    <label>Product Type <span class="required">*</span></label>
    <select name="product_type">
        <option value="">-- Select Product Type --</option>
        <option value="old" <?php if(isset($_POST['product_type']) && $_POST['product_type']=='old') echo 'selected'; ?>>Old Product</option>
        <option value="new" <?php if(isset($_POST['product_type']) && $_POST['product_type']=='new') echo 'selected'; ?>>New Product</option>
    </select>
    <?php if(isset($errors['product_type'])) echo "<div class='error'>{$errors['product_type']}</div>"; ?>

    <label>Supplier Name <span class="required">*</span></label>
    <input type="text" name="supplier_name" placeholder="Enter Supplier Name" value="<?php echo htmlspecialchars($_POST['supplier_name'] ?? ''); ?>">
    <?php if(isset($errors['supplier_name'])) echo "<div class='error'>{$errors['supplier_name']}</div>"; ?>

    <label>Supplier Contact <span class="required">*</span></label>
    <input type="text" name="supplier_contact" id="supplier_contact" placeholder="Enter 10-digit number" value="<?php echo htmlspecialchars($_POST['supplier_contact'] ?? ''); ?>" maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
    <?php if(isset($errors['supplier_contact'])) echo "<div class='error'>{$errors['supplier_contact']}</div>"; ?>

    <label>City Name <span class="required">*</span></label>
    <input type="text" name="city_name" placeholder="Enter City Name" value="<?php echo htmlspecialchars($_POST['city_name'] ?? ''); ?>">
    <?php if(isset($errors['city_name'])) echo "<div class='error'>{$errors['city_name']}</div>"; ?>

    <label>Cost Price <span class="required">*</span></label>
    <input type="text" name="cost_price" placeholder="Enter Cost Price" value="<?php echo htmlspecialchars($_POST['cost_price'] ?? ''); ?>">
    <?php if(isset($errors['cost_price'])) echo "<div class='error'>{$errors['cost_price']}</div>"; ?>

    <label>Sell Price <span class="required">*</span></label>
    <input type="text" name="sell_price" placeholder="Enter Sell Price" value="<?php echo htmlspecialchars($_POST['sell_price'] ?? ''); ?>">
    <?php if(isset($errors['sell_price'])) echo "<div class='error'>{$errors['sell_price']}</div>"; ?>

    <label>Quantity <span class="required">*</span></label>
    <input type="text" name="qty" placeholder="Enter Quantity" value="<?php echo htmlspecialchars($_POST['qty'] ?? ''); ?>">
    <?php if(isset($errors['qty'])) echo "<div class='error'>{$errors['qty']}</div>"; ?>

    <label>Date <span class="required">*</span></label>
    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>">
    <?php if(isset($errors['date'])) echo "<div class='error'>{$errors['date']}</div>"; ?>

    <input type="submit" value="Add Product">
    <a href="5dashboard.php">Back to Dashboard </a>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if(!document.getElementById('date').value){
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').value = today;
    }
});
</script>
</body>
</html>
