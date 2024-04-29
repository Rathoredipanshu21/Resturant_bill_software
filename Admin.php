<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        .container-fluid {
            display: flex;
            flex-direction: row;
            height: 100vh;
        }

        .sidebar {
            background-color: #343a40;
            width: 250px;
            padding-top: 20px;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            overflow-x: hidden;
            transition: all 0.3s ease;
            height: 100%;
            
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
            transition: all 0.3s ease;
            padding: 20px;
        }

        .sidebar a:hover {
            background-color: #23282c;
        }

        .sidebar i {
            margin-right: 10px;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .content-container {
            text-align: center;
            max-width: 400px;
        }

        .content h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        #admin-text {
            text-align: center;
            background-color: #343a40;
            color: #fff;
            height: 20vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="sidebar">
        <a href="AdminPage.php"><i class="fas fa-box-open"></i> Add Item</a>
        <a href="index.php"><i class="fas fa-clipboard-list"></i>Create Bill</a>
        <a href="Display_Bill.php"><i class="fas fa-clipboard-list"></i> Bill Number</a>
        <a href="filter.html"><i class="fas fa-box-open"></i>Filter Date</a>

        <!-- Add more options here -->
    </div>
    <div class="content">
      
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    const contentSections = document.querySelectorAll('.content .card');

    sidebarLinks.forEach((link, index) => {
        link.addEventListener('click', () => {
            // Hide all content sections
            contentSections.forEach(section => {
                section.style.display = 'none';
            });

            // Show the selected content section
            contentSections[index].style.display = 'block';
        });
    });
</script>

</body>
</html>
