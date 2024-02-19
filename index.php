<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to the login page or any other authentication page
    header("Location: login.php");
    exit();
}
?>

<?php
include 'db_connection.php';
include 'navigation.php';

// Fetch existing usernames from the users table
$sql = "SELECT name FROM user";
$result = $conn->query($sql);
$usernames = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usernames[] = $row['name'];
    }
}

$sql = "SELECT name FROM product";
$result = $conn->query($sql);
$productNames = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productNames[] = $row['name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing Details</title>
    <script>


        function addProductRow() {
            const table = document.getElementById('products-table');
            const newRow = table.insertRow();

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3);

            cell1.innerHTML = '<select name="product_name[]" style="padding: 8px; border-radius: 5px;width:200px;"  required><?php foreach ($productNames as $name) { echo "<option value=\'" . htmlspecialchars($name) . "\'>" . htmlspecialchars($name) . "</option>"; } ?></select>';
            cell2.innerHTML = '<input type="number" name="quantity[]" style="padding: 4px;;width:80px;" required>';
            cell3.innerHTML = '<input type="number" step="0.01" name="price[]" style="padding: 4px;width:80px;" required>';
            cell4.innerHTML = '<button type="button" onclick="deleteProductRow(this)" style="padding:5px;" ><i class="fas fa-trash-alt"></i></button>';
        }

        function deleteProductRow(row) {
            const table = document.getElementById('products-table');
            const rowIndex = row.parentNode.parentNode.rowIndex;
            table.deleteRow(rowIndex);
        }

    


    </script>
    <style>
        /* Add your CSS styles here */
        table {
            border-collapse: collapse;
            margin: 0 auto;
            
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;

        }
        .bill-content{
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            margin: 5px;
        }
        .first-content{
            margin: 0 auto;
            display: inline-flex; 
            width:80%;
        }
    </style>
      <link rel="stylesheet" href="styless.css">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">



</head>
<body>
<script>
    function validateBill() {
        const products = document.getElementsByName('product_name[]');
        if (products.length === 0) {
            alert("Please add at least one product to the bill.");
            return false; // Prevent form submission
        }
        return true; // Proceed with form submission
    }
</script>

<div class="bill-content">
<!-- <h2 style="text-align: center">Billing Details</h2> -->

<form method="post" action="bill_submit.php" onsubmit="return validateBill();">

<table style="width: 80%;">
            <colgroup>
                <col style="width: 30%;"> <!-- S.NO. -->
                <col style="width: 20%;"> <!-- CONSTRUCTIONS -->
                <col style="width: 20%;"> <!-- MTR/QTY -->
                <col style="width: 10%;"> <!-- MTR/QTY -->

            
            </colgroup>
        <tr>
            <th>Billing party</th>
            <th>Shipping party</th>
            <th>Tranport</th>
            <th>Date</th>
           
        </tr>

        <tr>
            <td>
                <select name="user" id="user" style="padding: 8px; border-radius: 5px;">
                <?php
                    foreach ($usernames as $username) {
                     echo "<option value='" . htmlspecialchars($username) . "'>" . htmlspecialchars($username) . "</option>";
                 }
                ?>
                </select>
            </td>
            <td>
                <select name="user-ship" id="user-ship" style="padding: 8px; border-radius: 5px;">
                <?php
                     foreach ($usernames as $username) {
                    echo "<option value='" . htmlspecialchars($username) . "'>" . htmlspecialchars($username) . "</option>";
                    }
                ?>
                </select>
            </td>
            <td>
                 <?php
                   $todayDate = date("Y-m-d");
                   $transport = "TN37BW6097";
                ?>
                <input type="text" name="bill-transport" id="bill-transport" value="<?php echo $transport; ?>" style="padding: 8px;width:100px;">
            </td>
            <td>
                <input type="date" name="bill-date" id="bill-date" value="<?php echo $todayDate; ?>" style="padding: 5px; ">     
           </td>
        </tr>
    </table>

    
    

   
    <br><br>
    

    <table id="products-table" style="width: 80%;">
            <colgroup>
                <col style="width: 30%;"> <!-- S.NO. -->
                <col style="width: 20%;"> <!-- CONSTRUCTIONS -->
                <col style="width: 20%;"> <!-- MTR/QTY -->
                <col style="width: 10%;"> <!-- MTR/QTY -->

            
            </colgroup>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price per Unit</th>
            <th>
               
                <button type="button" style="padding:5px;" onclick="addProductRow()">
                <i class="fas fa-plus"></i> 

            </button>

            </th>
        </tr>

        <tr>
            <td>
                <select name="product_name[]" style="padding: 8px; border-radius: 5px;width:200px;" required>
                    <?php foreach ($productNames as $name) { echo "<option value='" . htmlspecialchars($name) . "'>" . htmlspecialchars($name) . "</option>"; } ?>
                </select>
            </td>
            <td><input type="number" name="quantity[]" style="padding: 4px;width:80px;" required></td>
            <td><input type="number" step="0.01" name="price[]" style="padding: 4px; width:80px;" required></td>
            <td>
                <button type="button" onclick="deleteProductRow(this)"  style="padding: 5px;"  ><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    </table>
    <br>
    <input type="submit" value="Generate Bill" class="btn-secondary" style="margin-left:80%">
    </form>
</div>



</body>
</html>



