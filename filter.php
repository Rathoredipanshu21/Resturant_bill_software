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

if(isset($_POST['filter'])) {
    $filter = $_POST['filter'];
    $startDate = '';
    $endDate = date('Y-m-d');

    if($filter == 'last_week') {
        // Calculate start and end dates of the last week
        $startOfWeek = strtotime('last monday midnight', strtotime('-7 days'));
        $endOfWeek = strtotime('next sunday', $startOfWeek);
        $startDate = date('Y-m-d', $startOfWeek);
        $endDate = date('Y-m-d', $endOfWeek);
    } elseif($filter == 'last_month') {
        // Calculate start and end dates of the last month
        $startDate = date('Y-m-d', strtotime('first day of last month'));
        $endDate = date('Y-m-d', strtotime('last day of last month'));
    }

    // Retrieve filtered orders from the database
    $sql = "SELECT * FROM meal_items WHERE order_date BETWEEN '$startDate' AND '$endDate'";
    $query = mysqli_query($conn, $sql);

    // Initialize total gross amount variable
    $totalGrossAmount = calculateTotalGrossAmount($conn, $startDate, $endDate);
    $totalItemsSold = ($query) ? mysqli_num_rows($query) : 0;

    // Prepare the HTML for displaying filtered data
    ob_start();
    ?>
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
                echo "<tr><td colspan='6'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <h4>Total Gross Amount: <?php echo $totalGrossAmount; ?></h4>
    <h4>Total Items Sold: <?php echo $totalItemsSold; ?></h4>
    <?php
    $html = ob_get_clean();
    echo $html;
}
?>
