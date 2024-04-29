<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant & Motel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .result {
            color: red;
        }

        td {
            text-align: center;
        }
    </style>
</head>
<body>
    <section class="mt-3">
        <div class="container-fluid">
            <h4 class="text-center" style="color: green">Restaurant & Motel</h4>
            <h6 class="text-center">Address</h6>
            <div class="row">
                <div class="col-md-5 mt-4">
                    <table class="table" style="background-color: #f5f5f5;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Meal Items</th>
                                <th style="width: 31%">Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">1</td>
                                <td style="width: 60%">
                                    <select name="vegitable" id="vegitable" class="form-control">
                                        <?php 
                                        include("db.php");
                                        $sql = "SELECT * FROM orders";
                                        $query = mysqli_query($conn,$sql);
                                        while($row = mysqli_fetch_assoc($query)){
                                        ?> 
                                        <option id="<?php echo $row['id']; ?>" value="<?php echo $row['product_name']; ?>" data-price="<?php echo $row['product_price']; ?>" class="vegitable custom-select">
                                            <?php echo $row['product_name']; ?>
                                        </option>
                                        <?php  }?>
                                    </select>
                                </td>
                                <td style="width: 1%">
                                    <input type="number" id="qty" min="0" value="1" class="form-control">
                                </td>
                                <td>
                                    <p id="price"></p>
                                </td>
                                <td><button id="add" class="btn btn-primary">Add</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div role="alert" id="errorMsg" class="mt-5">
                        <!-- Error msg  -->
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button id="clearLastBill" class="btn btn-danger mr-2">Clear Last Bill</button>
                        <button id="printBill" class="btn btn-success">Print Receipt</button>
                    </div>
                </div>
                <div class="col-md-7 mt-4" style="background-color: #f5f5f5;">
                    <div class="p-4">
                        <div class="text-center">
                            <h4>Receipt</h4>
                        </div>
                        <span class="mt-4">Time: </span><span class="mt-4" id="time"></span><br>
                        <span>Date: </span><span id="date"></span> <!-- Display the date -->
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <span id="day"></span > Bill No : <span id="billNo"></span>
                            </div>
                        </div>
                        <div class="row">
                            <table id="receipt_bill" class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="new"></tbody>
                                <tr>
                                    <td colspan="3" class="text-right text-dark">
                                        <h5><strong>Sub Total: ₹</strong></h5>
                                        <p><strong>Tax (5%): ₹</strong></p>
                                    </td>
                                    <td class="text-center text-dark">
                                        <h5><strong><span id="subTotal">0.00</span></strong></h5>
                                        <h5><strong><span id="taxAmount">0.00</span></strong></h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right text-dark">
                                        <h5><strong>Gross Total: ₹</strong></h5>
                                    </td>
                                    <td class="text-center text-danger">
                                        <h5 id="totalPayment">0.00</h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            var billNumber = 1; // Initialize bill number
            var isPrinted = false; // Flag to check if receipt is printed

            $('#vegitable').change(function() {
                var price = parseFloat($(this).find(':selected').data('price'));
                $('#price').text(price.toFixed(2));
            });

            var billItems = {};  // Object to store all bill items

            // Function to calculate and update the totals
            function updateTotals() {
                var subTotal = 0;
                $.each(billItems, function(key, item) {
                    subTotal += item.total;
                });

                var taxAmount = subTotal * 0.05;
                var totalPayment = subTotal + taxAmount;

                $('#subTotal').text(subTotal.toFixed(2));
                $('#taxAmount').text(taxAmount.toFixed(2));
                $('#totalPayment').text(totalPayment.toFixed(2));
            }

            // Function to clear the last bill
            function clearLastBill() {
                $('#new').empty(); // Clear the bill table
                billItems = {};    // Clear the items object
                updateTotals();    // Recalculate totals
                // Reset bill number for a new bill
                billNumber++;
                $('#billNo').text(billNumber); // Update bill number in HTML
            }

            $('#add').on('click', function() {
                if (!isPrinted) {
                    // If receipt is not printed, use the current bill number
                    $('#billNo').text(billNumber);
                }
                
                var name = $('#vegitable').val();
                var qty = parseInt($('#qty').val());
                var price = parseFloat($('#price').text());

                if (qty <= 0) {
                    var errorMsg = '<span class="alert alert-danger ml-5">Minimum Qty should be 1 or More than 1</span>';
                    $('#errorMsg').html(errorMsg).fadeOut(9000);
                    return; // Exit function if qty is not valid
                }

                if (billItems.hasOwnProperty(name)) {
                    // If item already exists in the bill, update quantity and total
                    billItems[name].qty += qty;
                    billItems[name].total += price * qty;
                } else {
                    // If item doesn't exist in the bill, add it
                    billItems[name] = {
                        qty: qty,
                        price: price,
                        total: price * qty
                    };
                }

                // Clear the table body and re-populate it with updated bill items
                $('#new').empty();
                var count = 1; // Reset the count variable to start numbering from 1
                $.each(billItems, function(key, item) {
                    var tableRow = '<tr><td>' + count + '</td><td>' + key + '</td><td>' + item.qty + '</td><td>' + item.price.toFixed(2) + '</td><td><strong>' + item.total.toFixed(2) + '</strong></td></tr>';
                    $('#new').append(tableRow);
                    count++;
                });

                updateTotals(); // Update totals whenever an item is added or updated
            });

            $('#clearLastBill').on('click', function() {
                clearLastBill();
            });

            $('#printBill').on('click', function() {
                // Hide unnecessary content before printing
                $('#errorMsg').hide();
                $('#clearLastBill').hide();
                
                // Add the current date to the receipt content
                var currentDate = new Date().toLocaleDateString();
                $('#date').text(currentDate);
                
                // Print the receipt content
                window.print();

                // Restore the original date after printing
                $('#date').empty();
                
                // Show the hidden content after printing
                $('#errorMsg').show();
                $('#clearLastBill').show();

                // Save bill data to the "bill" table only if receipt is not printed
                if (!isPrinted) {
                    saveBillData();
                    isPrinted = true; // Set the flag to indicate receipt is printed
                }
            });

            function saveBillData() {
                // Collect bill data
                var billData = {
                    billNumber: billNumber,
                    items: billItems
                };

                // Send bill data to the server for saving
                $.ajax({
                    type: "POST",
                    url: "save_bill.php", // Change this URL to the PHP script that handles saving the bill data
                    data: billData,
                    success: function(response) {
                        console.log("Bill data saved successfully:", response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving bill data:", error);
                    }
                });
            }




            function displayClock() {
    var now = new Date();
    var time = now.toLocaleTimeString();
    var date = now.toLocaleDateString();

    // Format the date to match the format stored in the database (YYYY-MM-DD)
    var formattedDate = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
    
    $('#time').text(time);
    $('#date').text(formattedDate); // Display the formatted date
    setTimeout(displayClock, 1000); 
}
displayClock(); // Start the clock immediately


            function displayClock() {
                var now = new Date();
                var time = now.toLocaleTimeString();
                var date = now.toLocaleDateString();
                $('#time').text(time);
                $('#date').text(date); // Display the date
                setTimeout(displayClock, 1000); 
            }
            displayClock(); // Start the clock immediately
        });

    </script>

</body>
</html>
