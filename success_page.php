<?php
include 'db_connection.php'; // Include your database connection

// Check if bill_id parameter is set in the URL
if (isset($_GET['bill_id'])) {
    $insertedBillId = $_GET['bill_id'];

    // Fetch bill details based on the inserted bill ID
    $getBillQuery = $conn->prepare("SELECT * FROM bill WHERE id = ?");
    $getBillQuery->bind_param("i", $insertedBillId);
    $getBillQuery->execute();
    $billResult = $getBillQuery->get_result();

    if ($billResult->num_rows > 0) {
        $billRow = $billResult->fetch_assoc();
        $totalAmount = 0;
?>

<!DOCTYPE html>
<html>
    
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

            <title>Bill Details</title>
            <style>
                /* Your CSS styles here */
                table {
                    width: 100%;
                    height: 100%;
                    border-collapse: collapse;
                    border: 1px solid black;
                }
                th, td {
                    text-align: right;
                    border: 1px solid black;
                    padding: 5px;
                    margin: 0;

                }
                th {
                    text-align: center;
                    border: 1px solid black;
                }
                .button-container {
    display: flex;
    justify-content: flex-end;
}

            </style>
                  <link rel="stylesheet" href="styless.css">


            
     

        </head>
        <body>
    <script>
        function generateOriginal() {
            const content = document.getElementById('pdf-content-button');
            content.style.display = "none";
            window.print();
 
        }
        function generateDuplicate() {
            const content = document.getElementById('pdf-content-button');
            content.style.display = "none";
            const type = document.getElementById('bill-type');
            const billType = 'DUPLICATE INVOICE'; 
            type.textContent = billType;
            window.print();
        }
    </script> 
    <div class="button-container" id="pdf-content-button">
    <button onclick="generateOriginal()" id="pdf-button-id" class="btn btn-primary" style="display: inline-block; background-color: green; margin: 5px;">Original Copy</button>
    <button onclick="generateDuplicate()" id="pdf-button-id-duplicate" class="btn btn-primary" style="display: inline-block; background-color: green; margin: 5px;">Duplicate Copy</button>
</div>

        <div id="invoiceContent">
        <table id="myTable">
            <colgroup>
                <col style="width: 6%;"> <!-- S.NO. -->
                <col style="width: 58%;"> <!-- CONSTRUCTIONS -->
                <col style="width: 12%;"> <!-- MTR/QTY -->
                <col style="width: 12%;"> <!-- PRICE/RS -->
                <col style="width: 12%;"> <!-- AMOUNT/RS -->
            </colgroup>
            <tr>
                         <!-- <td style="width: 20%;">
                        <img src="logo.jpeg" alt="Company Logo" style="max-width: 100%; height: auto;border: none;">
                        </td> -->
              <td colspan="5" style="text-align: center; ">
                <h2>RATHI KANNAN TEXTILES</h3>
                SANGEETHA THETRE, 1B, 3rd STREET,<br>
                KARUMATHAMPATTI(P.O), SULUR(T.K), COIMBATORE - 641 668<br>
                TAMILNADU, INDIA<br>
                GSTIN : 33FIPPR3621J1ZW<br>
                EMAIL : radhikannantextiles@gmail.com<br>
                CELL : +91 866 720 8519<br>
              </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left;">CONSIGNEE:</td>
                <td colspan="3" style="text-align: center;" id="bill-type">ORIGINAL INVOICE</td>
            </tr>
          
            <tr >
            <td rowspan="5" colspan="2" style="text-align: left;">
  <div style="display: inline-flex; gap: 20px">
      <?php
      $billingQuery = $conn->prepare("SELECT u.*, b.ship_user_id 
                                      FROM user u 
                                      JOIN bill b ON u.id = b.user_id 
                                      WHERE b.id = ?");
      $billingQuery->bind_param("i", $insertedBillId);
      $billingQuery->execute();
      $billingResult = $billingQuery->get_result();

      $shippingQuery = $conn->prepare("SELECT u.* 
                                      FROM user u 
                                      JOIN bill b ON u.id = b.ship_user_id 
                                      WHERE b.id = ?");
      $shippingQuery->bind_param("i", $insertedBillId);
      $shippingQuery->execute();
      $shippingResult = $shippingQuery->get_result();

      if ($billingResult->num_rows > 0 && $shippingResult->num_rows > 0) {
          $billingRow = $billingResult->fetch_assoc();
          $shippingRow = $shippingResult->fetch_assoc();

          if ($billRow['user_id'] === $billRow['ship_user_id']) {
              echo '<div  style="width:50%;">';
              echo '<strong>Billing & Shipping To:</strong>';
              echo '<p><strong>' . htmlspecialchars($billingRow['name']) . '</strong></p>';
              echo '<p>' . htmlspecialchars($billingRow['address']) . '</p>';
              echo '<p>GST: ' . htmlspecialchars($billingRow['gst_number']) . '</p>';
              echo '</div>';
          } else {
              echo '<div style="width:50%;">';
              echo '<strong>Billing To:</strong>';
              echo '<p><strong>' . htmlspecialchars($billingRow['name']) . '</strong></p>';
              echo '<p>' . htmlspecialchars($billingRow['address']) . '</p>';
              echo '<p>GSTIN: ' . htmlspecialchars($billingRow['gst_number']) . '</p>';
              echo '</div>';

              echo '<div style="width:50%;">';
              echo '<strong>Shipping To:</strong>';
              echo '<p><strong>' . htmlspecialchars($shippingRow['name']) . '</strong></p>';
              echo '<p>' . htmlspecialchars($shippingRow['address']) . '</p>';
              echo '<p>GSTIN: ' . htmlspecialchars($shippingRow['gst_number']) . '</p>';
              echo '</div>';
          }
      } else {
          echo 'No user associated with the provided bill ID';
      }
      ?>
  </div>
</td>
                <td colspan="2" style="text-align: left;">
                    DATE
                 </td> 
                 <td colspan="1"  style="text-align: center;"> 
                    <?php echo date('d-m-Y', strtotime($billRow['date'])); ?>
                </td>
            </tr>
            <tr>
                
                <td colspan="2" style="text-align: left;">
                    INVOICE NO:
                 </td> 
                 <td  style="text-align: center;">
                     <?php echo $billRow['number']; ?>
                </td>
            </tr>
            <tr>
                
                <td colspan="2" style="text-align: left;" >
                    AGENT
                 </td> 
                 <td  style="text-align: center;"> KANNAN</td>
            </tr>
            <tr>
                
                <td colspan="2" style="text-align: left;">
                    TRANSPORT
                 </td> 
                 <td  style="text-align: center;"> <?php echo $billRow["transport"] ?></td>
            </tr>
        
            <tr>
                
                <td colspan="2" style="text-align: left;">
                    HSN CODE:
                 </td> 
                 <td  style="text-align: center;">
                     551611
                </td>
            </tr>
            
            <tr style="text-align: center;">
                <th>S.NO.</th>
                <th>CONSTRUTIONS</th>
                <th>MTR/QTY</th>
                <th>PRICE/RS</th>
                <th>AMOUNT/RS</th>
            </tr>

    <?php
            // Fetch bill items associated with the bill ID
            $getBillItemsQuery = $conn->prepare("SELECT * FROM billitem WHERE bill_id = ?");
            $getBillItemsQuery->bind_param("i", $insertedBillId);
            $getBillItemsQuery->execute();
            $billItemsResult = $getBillItemsQuery->get_result();
            $counter = 1;
            while ($billItem = $billItemsResult->fetch_assoc()) {
                $total = $billItem['item_quantity'] * $billItem['item_price'];
                $totalAmount += $total;
                ?>
                <tr style="border:none;">
                    <td><?php echo $counter ?></td>
                    <td style="text-align:left;">
                    <?php
                            $getUserIDQuery = $conn->prepare("SELECT * FROM product WHERE id = ?");
                            $getUserIDQuery->bind_param("i", $billItem['product_id']);
                            $getUserIDQuery->execute();
                            $result = $getUserIDQuery->get_result();
                        
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $userName = $row['name'];
                                $hsn_code = $row['hsn_code'];
                            }
                            else{
                                $userName = "";
                                $hsn_code = "";
                            }
                          
                            $getUserIDQuery->close();
                            echo htmlspecialchars($userName) . '&nbsp;&nbsp;&nbsp;&nbsp;'; 
                            echo '<span style="font-size: 12px;">(' . htmlspecialchars($hsn_code) . ')</span>'; 

                             
                         ?>
                    </td>
                    <td ><?php echo htmlspecialchars($billItem['item_quantity']); ?></td>
                    <td><?php echo number_format($billItem['item_price'], 2); ?></td>
                    <td ><?php echo number_format($total, 2,); ?></td>
                </tr>
                <?php
                $counter++;
            }
            $taxAmount = 2.5 / 100 * $totalAmount;
            $amountWithTax = $totalAmount + $taxAmount + $taxAmount;
            $roundedTotal = round($amountWithTax);
            $roundOffValue = $roundedTotal - $amountWithTax;
            
            // Round the amount and calculate the round-off value
            $amountWithTaxRound = round($amountWithTax);
            $roundOffText = ($roundOffValue >= 0) ? '+' : '-'; // Determine if it's a positive or negative round-off
            
            // Adjust the rounded total based on the round-off value
            if ($roundOffValue !== 0) {
                $amountWithTaxRound = $roundedTotal; // Adjust the amount to the rounded total
            }
            ?>
       <tr style="height: 50px;">
       <td></td>
        <td colspan="1"></td>
        <td ></td>
        <td ></td>
        <td ></td>
    </tr>

            <tr>
                <td colspan="2" style="text-align: left;"></td>
                <td colspan="2" style="text-align: center;"><strong>TOTAL VALUE/RS</strong></td>
                <td  style="text-align: RIGHT;"><strong><?php echo number_format($totalAmount, 2); ?></strong></td>
            </tr>
          
            <tr>
                <td rowspan="5" colspan="2" style="text-align: left; ">
                   PAYMENT DATE : <br>
                   PAYMENT DUE: NEFT/RTGS<br>
                   OUR BANK : KARUMATHAMPATTI<br>
                   A/C NO : 5020006877572 <br>
                   IFSC CODE : HDFC0007078  <br>
                </td>
                <td colspan="2">
                    CGST  2.50%
                 </td> 
                 <td colspan="1"> <strong><?php echo number_format(($taxAmount), 2); ?></strong></td>
            </tr>
            <tr>
                
                <td colspan="2">
                    SGST  2.50%
                 </td> 
                 <td ><strong><?php echo number_format(($taxAmount), 2); ?></strong></td>
            </tr>
            <tr>
                
                <td colspan="2">
                    TOTAL WITH TAX
                 </td>
                 <td ><strong><?php echo number_format($amountWithTax, 2); ?></strong></td>
            </tr>
            <tr>
                <td colspan="2">
                    ROUND OFF
                </td>
                <td>
                <strong> <?php echo ($roundOffValue >= 0) ? '+' : '-'; ?> <?php echo number_format(abs($roundOffValue), 2); ?></strong>
                </td>
            </tr>

            
            <tr>
                
                <td colspan="2">
                    TOTAL VALUE OF RS
                 </td> 
                 <td > <strong><?php echo number_format($amountWithTaxRound); ?></strong></td>
            </tr>
            <tr >
                <td colspan="5" style="text-align: left;">
                    <strong>INVOICE AMOUNT IN WORDS: <span style="margin-left:10px"> <?php echo convertToRupees($amountWithTaxRound) ?></strong> </span>
                </td>
                
            </tr>
            <tr >
                <td colspan="5" style="text-align: left;">
                    We are not responsible for any loss or damage in transit.  We will not accept any claim processing of goods.  Over due interest will be charged at 24% from date of invoice. 
                    <br><br><br>
                    <!-- <p style="text-align: left;"> CHECKED BY, <span style="text-align: right; margin-left: 200px"> for RATHI KANNAN TEXTILES</span></p> -->
                    <div style="display: flex; justify-content: space-between;">
                        <p style="margin-left: 20px; ">CHECKED BY,</p>
                        <p style="margin-right: 20px;">for RATHI KANNAN TEXTILES</p>
                    </div>


                </td>
                
            </tr>
        </table>

        </div>


       
        </html>

        <?php
    } else {
        echo "<p>No bill found with the provided ID.</p>";
    }

    // Close prepared statements and database connection
    $getBillQuery->close();
    $getBillItemsQuery->close();
    $conn->close();
} else {
    echo "<p>Bill ID not found in the URL.</p>";
}

?>



<?php

function convertToRupees($number) {
    $ones = array(
        0 => "ZERO",
        1 => "ONE",
        2 => "TWO",
        3 => "THREE",
        4 => "FOUR",
        5 => "FIVE",
        6 => "SIX",
        7 => "SEVEN",
        8 => "EIGHT",
        9 => "NINE",
        10 => "TEN",
        11 => "ELEVEN",
        12 => "TWELVE",
        13 => "THIRTEEN",
        14 => "FOURTEEN",
        15 => "FIFTEEN",
        16 => "SIXTEEN",
        17 => "SEVENTEEN",
        18 => "EIGHTEEN",
        19 => "NINETEEN"
    );
    $tens = array(
        0 => "ZERO",
        1 => "TEN",
        2 => "TWENTY",
        3 => "THIRTY",
        4 => "FORTY",
        5 => "FIFTY",
        6 => "SIXTY",
        7 => "SEVENTY",
        8 => "EIGHTY",
        9 => "NINETY"
    );
    $hundreds = array(
        "",
        "HUNDRED",
        "THOUSAND",
        "LAKH",
        "CRORE"
    );

    if ($number == 0) {
        return "ZERO RUPEES ONLY";
    }

    $crore = intval($number / 10000000);
    $lakh = intval(($number % 10000000) / 100000);
    $thousand = intval((($number % 100000) / 1000));
    $hundred = intval($number % 1000);

    $result = '';

    if ($crore > 0) {
        $result .= $ones[$crore] . ' ' . $hundreds[4] . ' ';
    }

    if ($lakh > 0) {
        $result .= $ones[$lakh] . ' ' . $hundreds[3] . ' ';
    }

    if ($thousand > 0) {
        if ($thousand >= 100) {
            $result .= $ones[intval($thousand / 100)] . ' ' . $hundreds[1] . ' ';
            $thousand %= 100;
        }

        if ($thousand >= 20) {
            $result .= $tens[intval($thousand / 10)] . ' ';
            $thousand %= 10;
        }

        if ($thousand > 0) {
            $result .= $ones[$thousand] . ' ';
        }
        $result .= $hundreds[2] . ' ';
    }

    if ($hundred > 0) {
        if ($hundred >= 100) {
            $result .= $ones[intval($hundred / 100)] . ' ' . $hundreds[1] . ' ';
            $hundred %= 100;
        }

        if ($hundred >= 20) {
            $result .= $tens[intval($hundred / 10)] . ' ';
            $hundred %= 10;
        }

        if ($hundred > 0) {
            $result .= $ones[$hundred] . ' ';
        }
    }

    $result .= 'RUPEES ONLY';
    return $result;
}


?>