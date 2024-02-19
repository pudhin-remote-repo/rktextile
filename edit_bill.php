<?php
include 'db_connection.php';

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
    <title>Edit Bill</title>
    <script>
        function addProductRow() {
            const table = document.getElementById('products-table');
            const newRow = table.insertRow();

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3);

            cell1.innerHTML = '<input type="text" name="product_name[]" required>';
            cell2.innerHTML = '<input type="number" name="quantity[]" required>';
            cell3.innerHTML = '<input type="number" step="0.01" name="price[]" required>';
            cell4.innerHTML = '<button type="button" onclick="deleteProductRow(this)">Delete</button>';
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
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Edit Bill</h2>

<form method="post" action="show_bill.php">

   
    <table id="products-table">
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price per Unit</th>
            <th>Action</th>
        </tr>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['edit_product_name']) && isset($_POST['edit_quantity']) && isset($_POST['edit_price'])) {
                $productNames = $_POST['edit_product_name'];
                $quantities = $_POST['edit_quantity'];
                $prices = $_POST['edit_price'];

                for ($i = 0; $i < count($productNames); $i++) {
                    ?>
                    <tr>
                    <td>
                            <select name="product_name[]" required>
                                <?php foreach ($productNames as $name) { echo "<option value='" . htmlspecialchars($name) . "'>" . htmlspecialchars($name) . "</option>"; } ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" value="<?php echo htmlspecialchars($quantities[$i]); ?>" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="price[]" value="<?php echo htmlspecialchars($prices[$i]); ?>" required>
                        </td>
                        <td>
                            <button type="button" onclick="deleteProductRow(this)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>

    </table>
    <br>
    <button type="button" onclick="addProductRow()">Add Product</button>
    <input type="submit" value="Update Bill">
</form>

</body>
</html>
