<?php
// Replace these values with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "easywire";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}

?>
