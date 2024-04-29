<?php
// Connection parameters
$host = 'localhost'; // or your host
$dbname = 'bill_reciept';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch distinct dates
$sql_dates = "SELECT DISTINCT DATE(Date) AS date FROM bill";
$result_dates = $conn->query($sql_dates);

if ($result_dates->num_rows > 0) {
    echo "<div style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;'>";
    while ($row_dates = $result_dates->fetch_assoc()) {
        $date = $row_dates['date'];
        echo "<div style='border: 1px solid #ccc; padding: 10px;'>";
        echo "<h2 style='text-align: center;'>Date: $date</h2>";

        // Query to fetch bill numbers for the current date
        $sql_bill_numbers = "SELECT DISTINCT Bill_No FROM bill WHERE DATE(Date) = '$date'";
        $result_bill_numbers = $conn->query($sql_bill_numbers);

        if ($result_bill_numbers->num_rows > 0) {
            while ($row_bill_numbers = $result_bill_numbers->fetch_assoc()) {
                $bill_number = $row_bill_numbers['Bill_No'];
                echo "<div style='border: 1px solid #ccc; margin-top: 10px; padding: 10px;'>";
                echo "<h3>Bill Number: $bill_number</h3>";

                // Query to fetch details for the current bill number and date
                $sql_bill_details = "SELECT * FROM bill WHERE Bill_No = $bill_number AND DATE(Date) = '$date'";
                $result_bill_details = $conn->query($sql_bill_details);

                if ($result_bill_details->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total Price</th></tr>";
                    while ($row = $result_bill_details->fetch_assoc()) {
                        $product_name = $row["product_name"];
                        $quantity = $row["quantity"];
                        $price = $row["price"];
                        $total_price = $price * $quantity;
                        echo "<tr><td>$product_name</td><td>$quantity</td><td>$price</td><td>$total_price</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No details found for Bill Number: $bill_number";
                }
                echo "</div>";
            }
        } else {
            echo "No bill numbers found for $date";
        }
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "No dates found.";
}

// Close connection
$conn->close();
?>
