<?php
// Start the session
session_start();

// Check if the session variable is set
if (isset($_SESSION['companyname'])) {
    $companyname = $_SESSION['companyname'];
    $username = $_SESSION['username'];
    
} else {
    header("Location: ../final-year-project/login.php");
}

// Replace these values with your actual database connection details
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$database = $companyname;

// Create a connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully!";
}

?>
