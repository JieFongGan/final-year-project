<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$companyname = $_SESSION['companyname'];
$username = $_SESSION['username'];
$userrole = $_SESSION['userrole'];

// Replace these values with your Azure SQL Database connection details
$serverName = "tcp:allhereserver.database.windows.net,1433";
$database = $companyname;
$uid = "sqladmin";
$pwd = "#Allhere";

// Create a connection
$conn = new PDO("sqlsrv:server=$serverName;Database=$database", $uid, $pwd);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check the connection
try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $uid, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
