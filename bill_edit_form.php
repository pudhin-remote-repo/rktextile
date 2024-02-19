<?php
session_start();
include "navigation.php";
include 'db_connection.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_item'])) {
        $item_id = $_POST['update_item'];
        $product_id = $_POST['product_id'][$item_id];
        $quantity = $_POST['item_quantity'][$item_id];
        $price = $_POST['item_price'][$item_id];

        $stmt = $conn->prepare("UPDATE billitem SET product_id = ?, item_quantity = ?, item_price = ? WHERE id = ?");
        $stmt->bind_param("idii", $product_id, $quantity, $price, $item_id);

        if ($stmt->execute()) {
            echo "Item updated successfully";
        } else {
            echo "Error updating item";
        }

        $stmt->close();
    } elseif (isset($_POST['delete_item'])) {
        $item_id = $_POST['delete_item'];
        $stmt = $conn->prepare("DELETE FROM billitem WHERE id = ?");
        $stmt->bind_param("i", $item_id);

        if ($stmt->execute()) {
            echo "Item deleted successfully";
        } else {
            echo "Error deleting item";
        }

        $stmt->close();
    } elseif (isset($_POST['add_item'])) {
        $product_id_new = $_POST['product_id_new'];
        $quantity_new = $_POST['item_quantity_new'];
        $price_new = $_POST['item_price_new'];

        $stmt = $conn->prepare("INSERT INTO billitem (bill_id, product_id, item_quantity, item_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $bill_id, $product_id_new, $quantity_new, $price_new);

        if ($stmt->execute()) {
            echo "Item added successfully";
        } else {
            echo "Error adding item";
        }

        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bill'])) {
    $user_id = getUserIdByName($conn, $_POST['user']);
    $shipping_user_id = getUserIdByName($conn, $_POST['user-ship']);
    $transport = $_POST['bill-transport'];
    $bill_date = $_POST['bill-date'];
    $bill_id = $_GET['bill_id'];

    $stmt = $conn->prepare("UPDATE bill SET user_id = ?, ship_user_id = ?, transport = ?, date = ? WHERE id = ?");
    $stmt->bind_param("iisss", $user_id, $shipping_user_id, $transport, $bill_date, $bill_id);

    if ($stmt->execute()) {
        echo "Bill details updated successfully";
    } else {
        echo "Error updating bill details";
    }

    $stmt->close();
}

if (!empty($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    include 'db_connection.php';

    $sql = "SELECT * FROM bill WHERE id = $bill_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $shipping_user_id = $row['ship_user_id'];
        $transport = $row['transport'];
        $bill_date = $row['date'];
        $bill_number = $row['number']; // Corrected variable name
    } else {
        echo "No bill found with the given ID";
        exit();
    }

    $sql = "SELECT id, item_quantity, item_price, product_id FROM billitem WHERE bill_id = $bill_id";
    $result = $conn->query($sql);
    $billItems = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $billItems[] = $row;
        }
    }

    $BillerName = getBillNameByUserId($conn, $user_id);
    $ShipperName = getBillNameByUserId($conn, $shipping_user_id);

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

} else {
    echo "No bill ID provided";
    exit();
}

function getUserIdByName($conn, $username) {
    $stmt = $conn->prepare("SELECT id FROM user WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userId = $stmt->get_result()->fetch_assoc()['id'] ?? null;
    $stmt->close();

    return $userId;
}

function getBillNameByUserId($conn, $user_id) {
    $stmt = $conn->prepare("SELECT name FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $BillName = $stmt->get_result()->fetch_assoc()['name'] ?? null;
    $stmt->close();

    return $BillName;
}

function getProductNameById($conn, $product_id) {
    $stmt = $conn->prepare("SELECT name FROM product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $ProductName = $stmt->get_result()->fetch_assoc()['name'] ?? null;
    $stmt->close();

    return $ProductName;
}

?>

<!-- The rest of your HTML and JavaScript code remains unchanged -->
<html>
<head>
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

        .bill-content {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            margin: 5px;
        }

        .first-content {
            margin: 0 auto;
            display: inline-flex;
            width: 80%;
        }
    </style>
    <script>
        function addProductRow() {
            const table = document.getElementById('products-table');
            const newRow = table.insertRow();

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);
            const cell4 = newRow.insertCell(3);

            cell1.innerHTML = '<select name="product_name_new[]" style="padding: 8px; border-radius: 5px;width:200px;"  required><?php foreach ($productNames as $name) { echo "<option value=\'" . htmlspecialchars($name) . "\'>" . htmlspecialchars($name) . "</option>"; } ?></select>';
            cell2.innerHTML = '<input type="number" name="quantity_new[]" style="padding: 4px;;width:80px;" required>';
            cell3.innerHTML = '<input type="number" step="0.01" name="price_new[]" style="padding: 4px;width:80px;" required>';
            cell4.innerHTML = '<button type="button" onclick="deleteProductRow(this)" style="padding:5px;" ><i class="fas fa-trash-alt"></i></button>';
        }

        function deleteProductRow(row) {
            const table = document.getElementById('products-table');
            const rowIndex = row.parentNode.parentNode.rowIndex;
            table.deleteRow(rowIndex);
        }
    </script>
    <link rel="stylesheet" href="styless.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<div>
    <h3 style="text-align:center">Edit Bill</h3>
    <form method="post">

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
                <th>Transport</th>
                <th>Date</th>
            </tr>
            <tr>
                <td>
                    <select name="user" id="user" style="padding: 8px; border-radius: 5px;">
                        <?php
                        foreach ($usernames as $username) {
                            $selected = ($username == $BillerName) ? "selected" : "";
                            echo "<option value='" . htmlspecialchars($username) . "' $selected>" . htmlspecialchars($username) . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="user-ship" id="user-ship" style="padding: 8px; border-radius: 5px;">
                        <?php
                        foreach ($usernames as $username) {
                            $selected = ($username == $ShipperName) ? "selected" : "";
                            echo "<option value='" . htmlspecialchars($username) . "' $selected>" . htmlspecialchars($username) . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="bill-transport" id="bill-transport" value="<?php echo htmlspecialchars($transport); ?>" style="padding: 8px;width:100px;">
                </td>
                <td>
                    <input type="date" name="bill-date" id="bill-date" value="<?php echo htmlspecialchars($bill_date); ?>" style="padding: 5px;">
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
            <?php foreach ($billItems as $item) { ?>
                <tr>
                    <td>
                        <select name="product_id[]" style="padding: 8px; border-radius: 5px;width:200px;" required>
                            <?php
                            include "db_connection.php";
                            $productNamebyId = getProductNameById($conn, $item['product_id']);
                            foreach ($productNames as $name) {
                                $selected = ($name == $productNamebyId) ? "selected" : "";
                                echo "<option value='" . htmlspecialchars($name) . "' $selected>" . htmlspecialchars($name) . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td><input type="number" name="item_quantity[]" style="padding: 4px;width:80px;" value="<?php echo htmlspecialchars($item['item_quantity']); ?>" required></td>
                    <td><input type="number" step="0.01" name="item_price[]" style="padding: 4px; width:80px;" value="<?php echo htmlspecialchars($item['item_price']); ?>" required></td>
                    <td>
                        <form method="post">
                            <button type="submit" name="delete_item" style="padding: 5px;" value="<?php echo $item['id']; ?>">
                                <i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <br>
        <input type="submit" value="Update Bill" name="update_bill" class="btn-secondary" style="margin-left:80%">
    </form>
</div>
</body>
</html>
