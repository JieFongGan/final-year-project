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

$userid = $_GET['userid'];
$sql = "SELECT * FROM user WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $companyName = $row['CompanyName'];
    $status = $row['Status'];
} else {
    echo "No data found.";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $status = $_POST['status'];

    // Update the company details in the database
    $sql = "UPDATE user SET Status = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $userid);

    if ($stmt->execute()) {
        // Create a new connection using the company name
        $connn = new mysqli('localhost', 'root', '', $companyName);
        
        // Update user status in the new connection
        $sql = "UPDATE User SET UserStatus = ? WHERE Username = ?";
        $stmt = $connn->prepare($sql);
        $stmt->bind_param("ss", $status, $userid);

        if ($stmt->execute()) {
            // Redirect back to the previous page or perform any other action
            header('Location: adminuserlist.php');
            exit;
        } else {
            echo "Error updating user details: " . $connn->error;
        }
    } else {
        echo "Error updating company details: " . $conn->error;
    }
}

$conn->close();
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
            <label for="userid">UserID:</label>
            <input type="text" id="userid" name="userid" value="<?php echo $_GET['userid']; ?>" readonly><br><br>

            <label for="companyname">Company Name:</label>
            <input type="text" id="companyname" name="companyname" value="<?php echo $companyName; ?>" readonly><br><br>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Active" <?php if ($status == 'Active')
                    echo 'selected'; ?>>Active</option>
                <option value="Disable" <?php if ($status == 'Disable')
                    echo 'selected'; ?>>Disable</option>
            </select><br><br>

            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
            <a href="adminuserlist.php">Back</a>
        </form>
    </div>
</body>

</html>