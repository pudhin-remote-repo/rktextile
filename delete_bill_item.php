<?php
include "db_connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = $_POST['delete_item'];

    // Perform deletion query for billitem
    $stmt = $conn->prepare("DELETE FROM billitem WHERE id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "Item deleted successfully";
    } else {
        echo "Error deleting item";
    }

    $stmt->close();
}

?>