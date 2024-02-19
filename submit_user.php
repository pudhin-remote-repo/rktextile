<?php

include 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userName = $_POST["userName"] ?? '';
    $userAddress = $_POST["userAddress"] ?? '';
    $userMobile = $_POST["userMobile"] ?? '';
    $userGST = $_POST["userGST"] ?? '';
    


    // Prepare the SQL INSERT statement
    $stmt = $conn->prepare("INSERT INTO user (name, address, mobile, gst_number) VALUES (?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("ssss", $userName, $userAddress, $userMobile, $userGST);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "User added successfully";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Unable to prepare statement";
    }

    $conn->close();
}
?>
