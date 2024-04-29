<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];

    $check_sql = "SELECT * FROM orders WHERE product_name = '$product_name' AND product_price = '$product_price'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>alert("Product already exists!");</script>';
    } else {


        $sql = "INSERT INTO orders (product_name, product_price) VALUES ('$product_name', '$product_price')";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Item added successfully");</script>';
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
        }

        h2 {
            color: #007bff;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 0;
        }

        button[type="submit"] {
            background-color: #007bff;
            border-color: #007bff;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Admin Dashboard</h2>
        <div class="card">
            <div class="card-header">
                Add New Item
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" class="form-control" id="product_name" name="product_name">
                    </div>
                    <div class="form-group">
                        <label for="product_price">Product Price:</label>
                        <input type="number" class="form-control" id="product_price" name="product_price" min="0" step="0.01">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
