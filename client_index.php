<?php
session_start();
?>

<?php
include "navigation.php";
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    // Handle product deletion here
    $deleteUserId = $_POST['delete_user'];
   
    $sql = "DELETE FROM user WHERE id=$deleteUserId";
    // Execute the query using 
    $conn->query($sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    // Handle product update here
    $userIdToUpdate = $_POST['update_user'];
    $updatedUserName = $_POST['updated_name']; // Get the updated name from the form
    $updatedUserPrice = $_POST['updated_address']; // Get the updated price from the form
    $updatedUserGst = $_POST['updated_gst']; 

    // Add code to update product with ID $productIdToUpdate in the database
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $userIdToUpdate = $_POST['update_user'];
    $updatedUserName = $_POST['updated_name']; // Get the updated name from the form
    $updatedUserPrice = $_POST['updated_address']; // Get the updated price from the form
    $updatedUserGst = $_POST['updated_gst']; 

    // Update the product in the database
    $updateQuery = $conn->prepare("UPDATE user SET name = ?, address = ?, gst_number = ? WHERE id = ?");
    $updateQuery->bind_param("sssi", $updatedUserName, $updatedUserPrice, $updatedUserGst, $userIdToUpdate);

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
            text-align: center;

        }
    </style>
    <link rel="stylesheet" href="styless.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div style="margin:10px;">
        <h3 style="text-align:center">Companies Lists</h3>
        <table>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Address</th>
                <th>GSTIN</th>
                <th>Action</th>
            </tr>
            <colgroup>
                <col style="width: 5%;"> <!-- S.NO. -->
                <col style="width: 25%;"> <!-- CONSTRUCTIONS -->
                <col style="width: 40%;"> <!-- MTR/QTY -->
                <col style="width: 20%;"> <!-- PRICE/RS -->
                <col style="width: 10%;"> <!--GST -->
            </colgroup>
            <?php
            $sql = "SELECT * FROM user";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <?php if (isset($_POST['edit_user']) && $_POST['edit_user'] == $row['id']) { ?>
                            <form method="post">
                                <input type="hidden" name="update_user" value="<?php echo $row['id']; ?>">
                                <td><input type="text" name="updated_name" value="<?php echo $row['name']; ?>"></td>
                                <td><input type="text" name="updated_address" value="<?php echo $row['address']; ?>"></td>
                                <td><input type="text" name="updated_gst" value="<?php echo $row['gst_number']; ?>"></td>
                                <td style="text-align: center;">
                                    <button type="submit"><i class="fas fa-check-circle" style="padding:8px"></i></button>
                                    <button type="button" onclick="cancelEdit()"><i class="fas fa-times-circle" style="padding:8px"></i></button>
                                </td>
                            </form>
                        <?php } else { ?>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['gst_number']; ?></td>
                            <td style="text-align: center;">
                                <form method="post" style="display:inline-flex;">
                                    <button type="submit" name="delete_user" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')" value="<?php echo $row['id']; ?>">
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
                echo "No user data available";
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
