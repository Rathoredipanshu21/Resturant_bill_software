<?php 
include("db.php");

// Function to calculate total gross amount for a given period
function calculateTotalGrossAmount($conn, $startDate, $endDate) {
    $totalGrossAmount = 0;

    $sql = "SELECT * FROM meal_items WHERE order_date BETWEEN '$startDate' AND '$endDate'";
    $query = mysqli_query($conn, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $quantity = isset($row['quantity']) ? $row['quantity'] : 0;
            $price = isset($row['price']) ? $row['price'] : 0;
            $totalGrossAmount += $quantity * $price;
        }
    }

    return $totalGrossAmount;
}

// Default filter values
$startDate = '1970-01-01'; // Default start date
$endDate = date('Y-m-d'); // Today

// Calculate start and end date for the past week
$startDateWeek = date('Y-m-d', strtotime('-7 days'));
$endDateWeek = date('Y-m-d');

// Retrieve all orders from the database for the past week
$sqlWeek = "SELECT * FROM meal_items WHERE order_date BETWEEN '$startDateWeek' AND '$endDateWeek'";
$queryWeek = mysqli_query($conn, $sqlWeek);

// Initialize total gross amount variable for the past week
$totalGrossAmountWeek = calculateTotalGrossAmount($conn, $startDateWeek, $endDateWeek);
$totalItemsSoldWeek = ($queryWeek) ? mysqli_num_rows($queryWeek) : 0;

// Retrieve all orders from the database
$sql = "SELECT * FROM meal_items"; // Changed to select from meal_items table
$query = mysqli_query($conn, $sql);

// Initialize total gross amount variable
$totalGrossAmount = 0;
$totalItemsSold = 0;

// Calculate total gross amount and total items sold
if ($query && mysqli_num_rows($query) > 0) {
    while($row = mysqli_fetch_assoc($query)) {
        $quantity = isset($row['quantity']) ? $row['quantity'] : 0;
        $price = isset($row['price']) ? $row['price'] : 0;
        $total = $quantity * $price;
        $totalGrossAmount += $total;
        $totalItemsSold += $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Order Details</h2>
        <!-- Filter Form -->
        <form id="filterForm">
            <div class="form-group">
                <label for="filter">Filter By:</label>
                <select class="form-control" id="filter" name="filter">
                    <option value="all">All</option>
                    <option value="last_week">Last Week</option>
                    <option value="last_month">Last Month</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filter</button>
        </form>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>DateTime</th> 
                </tr>
            </thead>
            <tbody>
                <?php
                if ($query && mysqli_num_rows($query) > 0) {
                    $counter = 1;
                    mysqli_data_seek($query, 0); // Reset pointer to beginning of result set
                    while($row = mysqli_fetch_assoc($query)) {
                        $productName = isset($row['product_name']) ? $row['product_name'] : '';
                        $quantity = isset($row['quantity']) ? $row['quantity'] : 0;
                        $price = isset($row['price']) ? $row['price'] : 0;
                        $total = $quantity * $price;
                        $orderDate = isset($row['order_date']) ? $row['order_date'] : '';
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $productName; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td><?php echo $price; ?></td>
                            <td><?php echo $total; ?></td>
                            <td><?php echo $orderDate; ?></td>
                        </tr>
                        <?php 
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found.</td></tr>"; // Updated colspan to 6
                }
                ?>
            </tbody>
        </table>
        <!-- Display Total Gross Amount and Total Items Sold -->
        <h4>Total Gross Amount: <?php echo $totalGrossAmount; ?></h4>
        <h4>Total Items Sold: <?php echo $totalItemsSold; ?></h4>
        
        <!-- Display Total Gross Amount and Total Items Sold for the past week -->
        <h4>Total Gross Amount (Last Week): <?php echo $totalGrossAmountWeek; ?></h4>
        <h4>Total Items Sold (Last Week): <?php echo $totalItemsSoldWeek; ?></h4>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>

$(document).ready(function() {
        $('#add').on('click', function() {
            var name = $('#vegitable').val();
            var qty = parseInt($('#qty').val());
            var price = parseFloat($('#price').text());

            if (qty <= 0) {
                var errorMsg = '<span class="alert alert-danger ml-5">Minimum Qty should be 1 or More than 1</span>';
                $('#errorMsg').html(errorMsg).fadeOut(9000);
                return; // Exit function if qty is not valid
            }

            // Send AJAX request to add item to the database
            $.ajax({
                type: 'POST',
                url: 'add_item.php', // PHP script to handle adding item to the database
                data: {
                    name: name,
                    qty: qty,
                    price: price
                },
                success: function(response) {
                    // Update the page content if needed
                    // For example, display a success message or refresh the table
                    console.log('Item added successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error adding item to the database:', error);
                }
            });
        });

    $(document).ready(function() {
    // Handle form submission
    $('#filterForm').submit(function(event) {
        event.preventDefault();
        var filter = $('#filter').val();
        var url = 'filter.php'; // New PHP file for filtering
        
        // Send AJAX request to fetch filtered data
        $.ajax({
            type: 'POST',
            url: url,
            data: { filter: filter },
            success: function(response) {
                // Replace table content with new data
                var tableContent = $(response);
                $('.container').html(tableContent);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });
});

  </script>
</body>
</html>
