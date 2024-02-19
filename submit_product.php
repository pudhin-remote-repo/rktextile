<?php

include 'db_connection.php'; // Assuming this file includes your database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $productName = $_POST["productName"] ?? '';
    $productPrice = $_POST["productPrice"] ?? '';

    // Prepare the SQL INSERT statement
    $stmt = $conn->prepare("INSERT INTO product (name, hsn_code) VALUES (?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("ss", $productName, $productPrice);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "Product added successfully";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Unable to prepare statement";
    }

    // Close the connection (assuming $conn is your database connection)
    $conn->close();
}
?>
