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
include "navigation.php";
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

if (isset($_POST['delete_bill'])) {
    $billId = $_POST['delete_bill']; // Get the user ID to delete
    // You can perform your SQL query for deleting the user here
    // For example:
    $sql = "DELETE FROM bill WHERE id=$billId";
    // Execute the query using 
    $conn->query($sql);
}


$conn->close();
?>

<html>
    <head>
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
            text-align: center;

        }
        .bill-content{
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            margin: 5px;
        }
    </style>
          <link rel="stylesheet" href="styless.css">
          <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


</head>



<body>
    <div style="margin:10px;">
    <h3 style="text-align:center">Bill Lists</h3>

    <table>
    
            <tr>
                <th>B.No.</th>
                <th>Date</th>
                <th>Billing To</th>
                <th>Shipping To</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
            <colgroup>
                <col style="width: 5%;"> <!-- S.NO. -->
                <col style="width: 15%;"> <!-- CONSTRUCTIONS -->
                <col style="width: 25%;"> <!-- MTR/QTY -->
                <col style="width: 25%;"> <!-- PRICE/RS -->
                <col style="width: 15%;"> <!--GST -->
                <col style="width: 10%;"> <!-- Action-->
            </colgroup>

            
            <?php
              include "db_connection.php";
              $getBillQuery = $conn->query('SELECT * from bill order by number desc');
            //   $BillList = $getBillQuery->fetch_assoc();
        

            while ($billItem = $getBillQuery->fetch_assoc()) {
            
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($billItem['number']); ?></td>
                    <td><?php echo $billItem['date']; ?></td>

                    <td>
                        <?php
                            $getUserIDQuery = $conn->prepare("SELECT name FROM user WHERE id = ?");
                            $getUserIDQuery->bind_param("i", $billItem['user_id']);
                            $getUserIDQuery->execute();
                            $result = $getUserIDQuery->get_result();
                        
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $userName = $row['name'];
                            }
                            else{
                                $userName = "";
                            }
                          
                            $getUserIDQuery->close();
                            echo htmlspecialchars($userName); 
                         ?>
                    </td>
                    <td>
                        <?php
                            $getUserIDQuery = $conn->prepare("SELECT name FROM user WHERE id = ?");
                            $getUserIDQuery->bind_param("i", $billItem['ship_user_id']);
                            $getUserIDQuery->execute();
                            $result = $getUserIDQuery->get_result();
                        
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $userName = $row['name'];
                            }
                            else{
                                $userName = "";
                            }
                          
                            $getUserIDQuery->close();
                            echo htmlspecialchars($userName); 
                         ?>
                    </td>
                    <td style="text-align:right;"><?php echo number_format($billItem['amount'] + $billItem['tax'], 2); ?></td>
                    <td style="display: inline-flex; gap:10px">
                        <form action="success_page.php" method="GET">
                            <input type="hidden" name="bill_id" value="<?php echo $billItem['id']; ?>">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </form>
                        <form method="POST">
                            <button type="submit"  name="delete_bill" class="btn btn-danger"  onclick="return confirm('Are you sure you want to delete this Bill?')" value="<?php echo $billItem['id']; ?>">
                            <i class="fas fa-trash-alt"></i> </button>
                        </form>
                        <form action="bill_edit_form.php" method="GET">
                            <input type="hidden" name="bill_id" value="<?php echo $billItem['id']; ?>">
                            <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-edit"></i>
                            </button>
                        </form>

                    </td>

                </tr>
                <?php
            }
            ?>
        
     </table>
        </div>
</body>
    
</html>


