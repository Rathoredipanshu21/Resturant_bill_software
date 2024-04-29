<?php
include("db.php"); // Include your database connection file

$sql = "SELECT MAX(bill_number) AS last_bill_number FROM bills";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$lastBillNumber = $row['last_bill_number'];

echo $lastBillNumber;
?>
