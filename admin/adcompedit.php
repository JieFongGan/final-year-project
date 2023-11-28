<?php

    // Connect to the database
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "adminallhere";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $authCode = $_GET['authcode'];
    $sql = "SELECT * FROM company WHERE AuthCode = '" . $authCode . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyName = $row['CompanyName'];
        $status = $row['Status'];
        $planType = $row['PlanType'];

    } else {
        echo "No data found.";
    }

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $companyName = $_POST['companyname'];
    $status = $_POST['status'];

    // Update the company details in the database
    $sql = "UPDATE company SET CompanyName = '$companyName', Status = '$status' WHERE AuthCode = '$authCode'";
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the previous page or perform any other action
        header('Location: admincomplist.php');
        exit;
    } else {
        echo "Error updating company details: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Company</title>
    <style>
        /* Add your CSS styling here */
        .container {
            /* Add your container styles */
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        /* Add more CSS styles as needed */
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        input[type="submit"],
        input[type="reset"],
        a {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 3px;
        }
        input[type="submit"]:hover,
        input[type="reset"]:hover,
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container" style="margin: 0 auto; width: 50%;">
        <h2>Edit Company Details</h2>
        <form method="POST" action="">
            <label for="authcode">AuthCode:</label>
            <input type="text" id="authcode" name="authcode" value="<?php echo $_GET['authcode']; ?>" readonly><br><br>

            <label for="plantype">PlanType:</label>
            <select id="plantype" name="plantype">
                <option value="BasicPlan" <?php if ($planType == 'BasicPlan') echo 'selected'; ?>>Basic Plan</option>
                <option value="NormalPlan" <?php if ($planType == 'NormalPlan') echo 'selected'; ?>>Normal Plan</option>
                <option value="ProPlan" <?php if ($planType == 'ProPlan') echo 'selected'; ?>>Pro Plan</option>
            </select><br><br>

            <label for="companyname">Company Name:</label>
            <input type="text" id="companyname" name="companyname" value="<?php echo $companyName; ?>"><br><br>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Available" <?php if ($status == 'Available') echo 'selected'; ?>>Available</option>
                <option value="Closed" <?php if ($status == 'Closed') echo 'selected'; ?>>Closed</option>
                <option value="Disable" <?php if ($status == 'Disable') echo 'selected'; ?>>Disable</option>
            </select><br><br>

            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
            <a href="admincomplist.php">Back</a>
        </form>
    </div>
</body>
</html>
