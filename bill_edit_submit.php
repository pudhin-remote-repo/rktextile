<?php
session_start();
include "navigation.php";

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to the login page or any other authentication page
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user = $_POST['user'];
    $user_ship = $_POST['user-ship'];
    $bill_transport = $_POST['bill-transport'];
    $bill_date = $_POST['bill-date'];
    $product_ids = $_POST['product_id'];
    $item_quantities = $_POST['item_quantity'];
    $item_prices = $_POST['item_price'];

    // Assuming bill_id is available as a GET parameter
    if (!empty($_GET['bill_id'])) {
        $bill_id = $_GET['bill_id'];
        
        // Perform updates in the database based on the collected form data
        include 'db_connection.php';
        
        // Update the 'bill' table with new values
        $update_bill_query = "UPDATE bill SET user_id = ?, ship_user_id = ?, transport = ?, date = ? WHERE id = ?";
        $stmt = $conn->prepare($update_bill_query);
        $stmt->bind_param("iisss", $user, $user_ship, $bill_transport, $bill_date, $bill_id);
        $stmt->execute();
        
        // Update the 'billitem' table with edited item details
        $delete_previous_items_query = "DELETE FROM billitem WHERE bill_id = ?";
        $stmt = $conn->prepare($delete_previous_items_query);
        $stmt->bind_param("i", $bill_id);
        $stmt->execute();

        // Insert new edited items
        $insert_item_query = "INSERT INTO billitem (bill_id, product_id, item_quantity, item_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_item_query);
        $stmt->bind_param("iiid", $bill_id, $product_id, $item_quantity, $item_price);

        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $item_quantity = $item_quantities[$i];
            $item_price = $item_prices[$i];
            $stmt->execute();
        }
        
        $conn->close();
        echo "Bill updated successfully!";
    } else {
        echo "No bill ID provided";
    }
} else {
    echo "Invalid request";
}
?>
