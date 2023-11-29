<?php
// Start the session
session_start();

// Check if the session variable is set
if (isset($_SESSION['companyname'])) {
    $companyname = $_SESSION['companyname'];
    // Use $companyname here
} else {
    echo "Session variable 'companyname' is not set.";
}

// Replace these values with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = $companyname;

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}

?>
