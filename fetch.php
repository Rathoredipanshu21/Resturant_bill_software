<?php 
include("db.php");

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM orders WHERE id='$id'";
    $query = mysqli_query($conn, $sql);

    if($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        echo json_encode($data);
    } else {
        echo json_encode(array("error" => "No data found for the given ID"));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<div id="receipt-section">
    <!-- Receipt content goes here -->
</div>

<button id="printReceipt">Print Receipt</button>

<script>
$(document).ready(function() {
    $('#printReceipt').on('click', function() {
        var pdfContent = $('#receipt-section').html(); // Get HTML content of the receipt section
        var originalContents = $('body').html(); // Save the original body content
        $('body').html(pdfContent); // Set the body content to the receipt section HTML
        window.print(); // Print the page
        $('body').html(originalContents); // Restore the original body content
    });

    // Call displayClock function to display current time
    displayClock();
});

function displayClock() {
    var time = new Date().toLocaleTimeString();
    $('#time').text(time);
    setTimeout(displayClock, 1000);
}
</script>

</body>
</html>
