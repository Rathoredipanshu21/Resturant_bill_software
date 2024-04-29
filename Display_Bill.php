<?php
include("db.php");

// Fetch all distinct bill numbers
$sql_bill_numbers = "SELECT DISTINCT Bill_No FROM bill";
$result_bill_numbers = mysqli_query($conn, $sql_bill_numbers);


if (mysqli_num_rows($result_bill_numbers) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Bills</title>
        <style>
            /* CSS styles for better display */
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <?php
        // Loop through each distinct bill number
        while ($row_bill_numbers = mysqli_fetch_assoc($result_bill_numbers)) {
            $bill_number = $row_bill_numbers['Bill_No'];
            ?>
            <h2>Bill Number: <?php echo $bill_number; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data for the current bill number
                    $sql_bill_details = "SELECT * FROM bill WHERE Bill_No = $bill_number";
                    $result_bill_details = mysqli_query($conn, $sql_bill_details);
                    $total_amount = 0; 
                    while ($row = mysqli_fetch_assoc($result_bill_details)) {
                        $total_item_amount = $row['quantity'] * $row['price'];
                        $total_amount += $total_item_amount;
                        ?>
                        <tr>
                            <td><?php echo $row["product_name"]; ?></td>
                            <td><?php echo $row["quantity"]; ?></td>
                            <td><?php echo $row["price"]; ?></td>
                            <td><?php echo $total_item_amount; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Grand Total:</strong></td>
                        <td><?php echo $total_amount; ?></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <?php
        }
        ?>
    </body>
    </html>
    <?php
} else {
    echo "No bill data found.";
}

// Close the database connection
mysqli_close($conn);
?>
