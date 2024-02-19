<?php
include 'db_connection.php'; 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data from the form
   
    $products = $_POST["product_name"];
    $quantities = $_POST["quantity"];
    $prices = $_POST["price"];
    $username = $_POST["user"];
    $date = $_POST["bill-date"];
    $shipUser = $_POST["user-ship"];
    $billTransport = $_POST["bill-transport"];

    // Fetch the user ID based on the provided username
    $getUserIDQuery = $conn->prepare("SELECT id FROM user WHERE name = ?");
    $getUserIDQuery->bind_param("s", $username);
    $getUserIDQuery->execute();
    $result = $getUserIDQuery->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
    }

    $getShipUserId = $conn->prepare("SELECT id FROM user WHERE name = ?");
    $getShipUserId->bind_param("s", $shipUser);
    $getShipUserId->execute();
    $result = $getShipUserId->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shipUserId = $row['id'];
    }
  
  
    $getUserIDQuery->close();

    // Calculate total bill amount
    $totalBillAmount = 0;
    foreach ($quantities as $key => $quantity) {
        $totalBillAmount += $quantity * $prices[$key];
    }

    // Here, calculate tax or any other additional charges if needed
    $maxBillNumberQuery = $conn->query("SELECT MAX(number) AS max_bill_number FROM bill");
    $maxBillNumberResult = $maxBillNumberQuery->fetch_assoc();
    $currentBillNumber = $maxBillNumberResult['max_bill_number'] ?? 0; // Default to 0 if no bill exists

    $billNumber = $currentBillNumber + 1;
    $tax = 5 / 100 * $totalBillAmount; 

    $totalBillIncludingTax = $totalBillAmount + $tax;

    $insertBillQuery = $conn->prepare("INSERT INTO bill (number, amount, tax, user_id, date, ship_user_id, transport) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertBillQuery->bind_param("iddisis", $billNumber, $totalBillAmount, $tax, $userId, $date, $shipUserId, $billTransport);
    $insertBillQuery->execute();

    $billId = $conn->insert_id;

    // Insert data into the billItem table for each product
    $insertBillItemQuery = $conn->prepare("INSERT INTO billitem (bill_id, product_id, item_quantity, item_price) VALUES (?, ?, ?, ?)");

    foreach ($products as $key => $productName) {
        $quantity = $quantities[$key];
        $price = $prices[$key];
    
        // Fetch the product ID based on the product name
        $getProductIDQuery = $conn->prepare("SELECT id FROM product WHERE name = ?");
        $getProductIDQuery->bind_param("s", $productName);
        $getProductIDQuery->execute();
        $result = $getProductIDQuery->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $productId = $row['id'];
        
        // Bind parameters for inserting into billitem table
            $insertBillItemQuery->bind_param("iidd", $billId, $productId, $quantity, $price);
            $insertBillItemQuery->execute();
        } else {
            echo "Product not found!";
        }
    
        $getProductIDQuery->close();


    }



    // Close prepared statements
    $insertBillQuery->close();
    $insertBillItemQuery->close();
    $conn->close();
    header("Location: success_page.php?bill_id=$billId");

    exit;
}
?>
