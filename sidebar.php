

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sidebar Click Dropdown</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #2c3e50;
      color: white;
      padding-top: 20px;
      position: fixed;
      left: 0;
      top: 0;
      overflow-y: auto;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 20px;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      font-size: 16px;
    }

    .sidebar a:hover {
      background-color: #34495e;
    }

    .sells-dropdown {
      position: relative;
      font-family: Arial, sans-serif;
    }

    .sells-dropdown-btn {
      text-decoration: none;
      padding: 5px 15px;
      background-color: #2c3e50;
      color: white;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
      cursor: pointer;
    }

    .sells-dropdown-btn:hover {
      background: #007bff;
    }

    .sells-dropdown-content {
      display: none;
      position: absolute;
      background-color: #fff;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      z-index: 999;
      border-radius: 5px;
    }

    .sells-dropdown-content a {
      color: #333;
      padding: 10px;
      text-decoration: none;
      display: block;
      border-bottom: 1px solid #eee;
    }

    .sells-dropdown-content a:hover {
      background-color: #f5f5f5;
    }

    .sells-dropdown-content.show {
      display: block;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h2>Shop Panel</h2>
  <a href="5dashboard.php">Dashboard</a>
  <a href="8showproduct.php">Stock</a>
  <a href="7productpage.php">Purchase</a>
   <a href="#">Sells</a>
  <div class="sells-dropdown-btn">
       <a href="11saleproduct.php" id="sells-new">New Sale</a>
      <a href="12sellmodify.php" id="sells-modify">Modify Sell</a>
   </div>

  <a href="15cre_deb.php">Debit/Credit</a>
  <a href="19customer.php">Customer</a>
  <a href="17suplier.php">Supplier</a>
  <a href="21billing_show.php">Billing</a>
  <a href="23view_report.php">Report</a>
  <a href="24logout_page.php">Logout</a>
</div>

<script>
  function toggleDropdown(event) {
    event.preventDefault();
    document.getElementById("dropdownMenu").classList.toggle("show");
  }

  document.addEventListener("click", function(e) {
    if (!e.target.closest(".sells-dropdown")) {
      document.getElementById("dropdownMenu").classList.remove("show");
    }
  });
</script>

</body>
</html>
