<!DOCTYPE html>
<html>
<head>
    <title>Bill Details</title>
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
        }
    </style>
</head>
<body>

<h2>Bill Details</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_name']) && isset($_POST['quantity']) && isset($_POST['price'])) {
        $productNames = $_POST['product_name'];
        $quantities = $_POST['quantity'];
        $prices = $_POST['price'];

        if (count($productNames) > 0 && count($quantities) > 0 && count($prices) > 0) {
            ?>

            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price per Unit</th>
                    <th>Total</th>
                </tr>

                <?php
                $totalAmount = 0;
                for ($i = 0; $i < count($productNames); $i++) {
                    $total = $quantities[$i] * $prices[$i];
                    $totalAmount += $total;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($productNames[$i]); ?></td>
                        <td><?php echo htmlspecialchars($quantities[$i]); ?></td>
                        <td>$<?php echo number_format($prices[$i], 2); ?></td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                    <?php
                }
                ?>

                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Amount</strong></td>
                    <td><strong>$<?php echo number_format($totalAmount, 2); ?></strong></td>
                </tr>
            </table>

            <form method="post" action="edit_bill.php">
                <?php
                for ($i = 0; $i < count($productNames); $i++) {
                    echo '<input type="hidden" name="edit_product_name[]" value="' . htmlspecialchars($productNames[$i]) . '">';
                    echo '<input type="hidden" name="edit_quantity[]" value="' . htmlspecialchars($quantities[$i]) . '">';
                    echo '<input type="hidden" name="edit_price[]" value="' . htmlspecialchars($prices[$i]) . '">';
                }
                ?>
                <input type="submit" value="Edit Bill">
            </form>

            <?php
        } else {
            echo "<p>Please provide at least one product's details.</p>";
        }
    } else {
        echo "<p>No data received.</p>";
    }
}
?>

</body>
</html>
