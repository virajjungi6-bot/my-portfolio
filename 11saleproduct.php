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

$sql = "SELECT id, p_name, category, product_type, s_price, p_qty FROM proadd ORDER BY p_name";
$result = mysqli_query($con, $sql);
$products = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cusname = mysqli_real_escape_string($con, $_POST['cusname']);
    $cusnum = mysqli_real_escape_string($con, $_POST['cusnum']);
    $sale_date = $_POST['sale_date'];
    $final_total = floatval($_POST['final_total']);
    $amt_paid = floatval($_POST['amt_paid']);
    $amt_mode = $_POST['amt_mode'];

    if ($amt_paid >= $final_total) {
        $amt_pen = 0.00;
        $payment_status = "Paid";
    } else {
        $amt_pen = $final_total - $amt_paid;
        $payment_status = "Pending";
    }

    $sql1 = "INSERT INTO selldata (customer_name, customer_contact, total_amount, amount_paid, amount_pending, payment_status, sale_date)
             VALUES ('$cusname', '$cusnum', '$final_total', '$amt_paid', '$amt_pen', '$payment_status', '$sale_date')";

    if (mysqli_query($con, $sql1)) {
        $sale_id = mysqli_insert_id($con);

        $sql2 = "INSERT INTO payments (sale_id, amount_paid, amount_pending, payment_mode, payment_date)
                 VALUES ('$sale_id', '$amt_paid', '$amt_pen', '$amt_mode', '$sale_date')";
        mysqli_query($con, $sql2);

        $p_name = $_POST['p_name'];
        $category = $_POST['category'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $subtotal = $_POST['subtotal'];

        for ($i = 0; $i < count($p_name); $i++) {
            $name = mysqli_real_escape_string($con, $p_name[$i]);
            $cat = mysqli_real_escape_string($con, $category[$i]);
            $ptype = mysqli_real_escape_string($con, $type[$i]);
            $sprice = floatval($price[$i]);
            $quantity = intval($qty[$i]);
            $sub = floatval($subtotal[$i]);

            $get_pid_query = "SELECT id FROM proadd WHERE p_name = '$name' AND category = '$cat' AND product_type = '$ptype' LIMIT 1";
            $pid_result = mysqli_query($con, $get_pid_query);
            $p_id = 0;
            if ($pid_row = mysqli_fetch_assoc($pid_result)) {
                $p_id = $pid_row['id'];
            }

            $sql3 = "INSERT INTO sellproducts (sale_id, p_id, p_name, category, product_type, sell_price, qty_sold, subtotal)
                     VALUES ('$sale_id', '$p_id', '$name', '$cat', '$ptype', '$sprice', '$quantity', '$sub')";
            mysqli_query($con, $sql3);

            $update_stock = "UPDATE proadd SET p_qty = p_qty - $quantity WHERE id = '$p_id'";
            mysqli_query($con, $update_stock);
        }

        echo "<script>alert('Sell data inserted successfully.'); window.location.href='11saleproduct.php';</script>";
    } else {
        echo "Error inserting sell data: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sell Products</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        padding: 3vh 5vw;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-bottom: 3vh;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
        border: 1px solid #ccc;
        padding: 1.5vh 1vw;
        text-align: center;
        font-size: 1.8vh;
    }
    th {
       background-color: #007bff;
       color:white;
    }
    select, input[type=number], input[readonly], input:disabled, input[type=text] {
        padding: 1vh;
        font-size: 1.6vh;
        width: 100%;
        box-sizing: border-box;
    }
    input:disabled {
        background-color: #eee;
        color: #666;
    }
    h2 {
        text-align: center;
        margin-bottom: 2vh;
    }
    .btn, input[type=submit], button, a {
        padding: 1.5vh 3vw;
        font-size: 2vh;
        border: none;
        cursor: pointer;
        margin: 1vh;
        border-radius: 0.5vh;
        text-decoration: none;
        color: white;
        display: inline-block;
    }
    .btn-add { background-color: #007bff; }
    .btn-sell { background-color: #28a745; }
    .btn-cancel { background-color: #dc3545; }
    a { background-color: #007bff; }

    .cus table {
        width: auto;
        margin: 5vh auto;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .cus td {
        padding: 1.5vh 2vw;
    }
</style>
</head>
<body>

<h2>Sell Products</h2>
<form method="POST" id="saleForm" action="11saleproduct.php">
    <table id="productTable">
        <thead>
            <tr>
                <th>Product *</th>
                <th>Category</th>
                <th>Type</th>
                <th>Sell Price (₹)</th>
                <th>Qty to Sell *</th>
                <th>Available Qty</th>
                <th>Subtotal (₹)</th>
                <th>Cur Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>

    <div class="cus">
        <table>
            <tr>
                <td><button type="button" class="btn btn-add" onclick="addRow()">Add More</button></td>
                <td>
                    <input type="hidden" name="final_total" id="finalTotalInput">
                    Date: <input type="date" name="sale_date" value="<?= date('Y-m-d') ?>" readonly>
                </td>
                <td><strong>Final Total: ₹<span id="finalTotal">0.00</span></strong></td>
            </tr>
            <tr>
                <td>Customer Name *</td>
                <td><input type="text" name="cusname"></td>
                <td><input type="submit" class="btn-sell" value="Final Sell"></td>
            </tr>
            <tr>
                <td>Contact Number *</td>
                <td><input type="text" name="cusnum"></td>
                <td><button type="reset" class="btn btn-cancel" onclick="resetTable()">Cancel</button></td>
            </tr>
            <tr>
                <td>Amount Paid *</td>
                <td><input type="text" name="amt_paid"></td>
                <td><a href="12sellmodify.php">Modify Data</a></td>
            </tr>
            <tr>
                <td>Amount Pending</td>
                <td><input type="text" name="amt_pen" readonly></td>
                <td><a href="5dashboard.php">Back to Dashboard</a></td>
            </tr>
            <tr>
                <td>Payment Mode</td>
                <td>
                    <select name="amt_mode">
                        <option>Cash</option>
                        <option>UPI</option>
                        <option>Credit/Debit Card</option>
                        <option>Other</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
</form>

<script>
const products = <?= json_encode($products); ?>;
const tableBody = document.getElementById('tableBody');
const finalTotalSpan = document.getElementById('finalTotal');
const finalTotalInput = document.getElementById('finalTotalInput');
let rowCount = 0;

function getTodayDate() {
    return new Date().toISOString().split('T')[0];
}

function addRow() {
    const rowId = `row-${rowCount++}`;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="p_name[]" onchange="fillDetails(this, '${rowId}')">
                <option value="">Select product</option>
                ${products.map(p => `<option value="${p.p_name}">${p.p_name}</option>`).join('')}
            </select>
        </td>
        <td><input type="text" name="category[]" id="category-${rowId}" readonly></td>
        <td><input type="text" name="type[]" id="type-${rowId}" readonly></td>
        <td><input type="text" name="price[]" id="price-${rowId}" readonly></td>
        <td><input type="number" name="qty[]" id="qty-${rowId}" min="1" value="1" oninput="validateQty('${rowId}'); updateSubtotal('${rowId}')"></td>
        <td><input type="text" id="available-${rowId}" readonly></td>
        <td><input type="text" name="subtotal[]" id="subtotal-${rowId}" readonly></td>
        <td><input type="text" name="sale_date[]" id="date-${rowId}" value="${getTodayDate()}" readonly></td>
        <td><button type="button" onclick="removeRow(this)">❌</button></td>
    `;
    tableBody.appendChild(row);
}

function fillDetails(select, rowId) {
    const product = products.find(p => p.p_name === select.value);
    if(!product) return;
    document.getElementById(`category-${rowId}`).value = product.category;
    document.getElementById(`type-${rowId}`).value = product.product_type;
    document.getElementById(`price-${rowId}`).value = parseFloat(product.s_price).toFixed(2);
    document.getElementById(`available-${rowId}`).value = product.p_qty;
    updateSubtotal(rowId);
}

function validateQty(rowId) {
    const qtyInput = document.getElementById(`qty-${rowId}`);
    const available = parseInt(document.getElementById(`available-${rowId}`).value) || 0;
    let qty = parseInt(qtyInput.value);
    if(qty > available) qtyInput.value = available;
    if(qty < 1) qtyInput.value = 1;
}

function updateSubtotal(rowId) {
    const price = parseFloat(document.getElementById(`price-${rowId}`).value) || 0;
    const qty = parseInt(document.getElementById(`qty-${rowId}`).value) || 0;
    document.getElementById(`subtotal-${rowId}`).value = (price*qty).toFixed(2);
    calculateFinalTotal();
}

function calculateFinalTotal() {
    let total = 0;
    document.querySelectorAll('[name="subtotal[]"]').forEach(input => total += parseFloat(input.value)||0);
    finalTotalSpan.textContent = total.toFixed(2);
    finalTotalInput.value = total.toFixed(2);
}

function removeRow(btn) {
    btn.closest('tr').remove();
    calculateFinalTotal();
}

function resetTable() {
    tableBody.innerHTML = '';
    finalTotalSpan.textContent = '0.00';
    finalTotalInput.value = '0.00';
    rowCount = 0;
    addRow();
}

// Initial row
addRow();

// --- Strict validation ---
document.getElementById('saleForm').addEventListener('submit', function(e){
    let valid = true;

    document.querySelectorAll('.error-msg').forEach(el=>el.remove());

    // Customer name
    const cusname = this.cusname.value.trim();
    if(cusname === '') { showError(this.cusname,"Customer Name is required."); valid=false; }

    // Contact
    const cusnum = this.cusnum.value.trim();
    if(cusnum === '') { showError(this.cusnum,"Contact Number is required."); valid=false; }

    // Amount paid
    const amt_paid = this.amt_paid.value.trim();
    if(amt_paid === '') { showError(this.amt_paid,"Amount Paid is required."); valid=false; }

    // Product rows
    document.querySelectorAll('#productTable tbody tr').forEach(tr=>{
        const p_name = tr.querySelector('[name="p_name[]"]');
        const qty = tr.querySelector('[name="qty[]"]');

        if(p_name.value.trim()===''){ showError(p_name,"Select a product."); valid=false; }
        if(qty.value.trim()==='' || parseInt(qty.value)<1){ showError(qty,"Quantity must be at least 1."); valid=false; }
    });

    if(!valid) e.preventDefault();
});

function showError(input,msg){
    const div = document.createElement('div');
    div.className='error-msg';
    div.style.color='red';
    div.style.fontSize='1.2vh';
    div.style.marginTop='0.3vh';
    div.innerText=msg;
    input.parentNode.appendChild(div);
}
</script>

</body>
</html>
