<?php
session_start();
?>

<?php
include "navigation.php";
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    // Handle product deletion here
    $deleteProductId = $_POST['delete_user'];
    $sql = "DELETE FROM product WHERE id=$deleteProductId";
    // Execute the query using 
    $conn->query($sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    // Handle product update here
    $productIdToUpdate = $_POST['update_product'];
    $updatedProductName = $_POST['updated_name']; // Get the updated name from the form
    $updatedProductPrice = $_POST['updated_price']; // Get the updated price from the form

    // Add code to update product with ID $productIdToUpdate in the database
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $productIdToUpdate = $_POST['update_product'];
    $updatedProductName = $_POST['updated_name']; // Get the updated name from the form
    $updatedProductPrice = $_POST['updated_price']; // Get the updated price from the form

    // Update the product in the database
    $updateQuery = $conn->prepare("UPDATE product SET name = ?, hsn_code = ? WHERE id = ?");
    $updateQuery->bind_param("ssi", $updatedProductName, $updatedProductPrice, $productIdToUpdate);

    if (!$updateQuery->execute()) {
        echo "Error updating record: " . $conn->error;
    }
    $updateQuery->close();
}


// Close the database connection
?>

<html>
<head>
    <style>
         table {
            width: 80%;
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
            text-align: center;

        }
    </style>
    <link rel="stylesheet" href="styless.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div>
        <h3 style="text-align:center">Product Lists</h3>
        <table>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>HSN Code</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT * FROM product";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <?php if (isset($_POST['edit_user']) && $_POST['edit_user'] == $row['id']) { ?>
                            <form method="post">
                                <input type="hidden" name="update_product" value="<?php echo $row['id']; ?>">
                                <td style="text-align: center;"><input type="text" name="updated_name"  value="<?php echo $row['name']; ?>"></td>
                                <td style="text-align: center;"><input type="text" name="updated_price" style="width:100px;" value="<?php echo $row['hsn_code']; ?>"></td>
                                <td style="text-align: center;">
                                    <button type="submit"><i class="fas fa-check-circle" style="padding:8px"></i></button>
                                    <button type="button" onclick="cancelEdit()"><i class="fas fa-times-circle" style="padding:8px"></i></button>
                                </td>
                            </form>
                        <?php } else { ?>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['hsn_code']; ?></td>
                            <td>
                                <form method="post" style="display:inline-flex;">
                                    <button type="submit" name="delete_user" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')" value="<?php echo $row['id']; ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <form method="post" style="display:inline-flex;">
                                    <button type="submit" name="edit_user" class="btn btn-secondary" value="<?php echo $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                    $counter++;
                }
            } else {
                echo "No product data available";
            }
            ?>
        </table>
    </div>
    <script>
        function cancelEdit() {
            window.location.href = window.location.pathname; // Redirect to the same page to cancel edit
        }
    </script>
</body>
</html>
