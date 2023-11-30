<?php
function generateRandomAuthCode($existingAuthCodes) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 7;
    $authCode = '';

    do {
        $authCode = '';
        for ($i = 0; $i < $length; $i++) {
            $authCode .= $characters[rand(0, strlen($characters) - 1)];
        }
    } while (in_array($authCode, $existingAuthCodes));

    return $authCode;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adminallhere";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing AuthCodes from the database
$sql = "SELECT AuthCode FROM company";
$result = $conn->query($sql);
$existingAuthCodes = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a random seven-character string that does not exist in the database
    $authCode = generateRandomAuthCode(array_column($existingAuthCodes, 'AuthCode'));

    // Insert new record into the company table
    $sql = "INSERT INTO `company` (`CompanyName`, `Status`, `AuthCode`) VALUES (NULL, NULL, '$authCode')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: admincomplist.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>New Company AuthCode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select,
        button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
<h2>Generate Code</h2>
<form action="" method="post">
    <br>
    <button type="submit">Generate and Save Codes</button>
</form>

<button onclick="goBack()">Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</div>
</body>
</html>