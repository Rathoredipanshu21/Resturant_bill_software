<?php
// Retrieve the bill data from the POST request
$items = $_POST['items'];

// Database connection
include("db.php");

// Get the latest bill number from the database and increment it for the new bill
$sql = "SELECT MAX(Bill_No) AS max_bill FROM bill";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$billNumber = $row['max_bill'] + 1;

// Iterate over the items and insert them into the database
foreach ($items as $productName => $item) {
    $quantity = $item['qty'];
    $price = $item['price'];
    $date = date('Y-m-d H:i:s'); // Current date and time

    // SQL query to insert data into the 'bill' table
    $sql = "INSERT INTO bill (Bill_No, product_name, quantity, price, Date) VALUES ('$billNumber', '$productName', '$quantity', '$price', '$date')";
    
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Insertion successful
        echo "Bill data saved successfully.";
    } else {
        // Error occurred
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
