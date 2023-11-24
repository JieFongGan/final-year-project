<?php

    function generateRandomAuthCode($existingAuthCodes)
    {
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
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "adminallhere";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Select all AuthCodes from the company table
    $sql = "SELECT AuthCode FROM company";
    $result = $conn->query($sql);

    // Store existing AuthCodes in an array
    $existingAuthCodes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $existingAuthCodes[] = $row["AuthCode"];
        }
    }

    // Generate a random seven-character string that does not exist in the database

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the selected plan type
        $planType = $_POST["plan_type"];
    
        // Generate a random seven-character string that does not exist in the database
        $authCode = generateRandomAuthCode($existingAuthCodes);
    
        // Prepare and execute the SQL statement to insert data into the company table
        $sql = "INSERT INTO `company` (`CompanyName`, `Status`, `PlanType`, `AuthCode`) VALUES (NULL, NULL, '$planType', '$authCode')";
    
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
            header("Location: admincomplist.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }


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
        <h2>Plan Type</h2>
        <form action="" method="post">
            <label for="plan_type">Plan Type:</label>
            <select name="plan_type" id="plan_type">
                <option value="BasicPlan">Basic Plan</option>
                <option value="NormalPlan">Normal Plan</option>
                <option value="ProPlan">Pro Plan</option>
            </select>
            <br><br>
            <button type="submit">Generate and Save Codes</button>
        </form>
    </div>

    

</body>

</html>