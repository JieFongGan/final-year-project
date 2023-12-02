﻿<?php
ob_start();

session_start();

// Check if the session variable is set
if (isset($_SESSION['companyname'])) {
    $companyname = $_SESSION['companyname'];
    $username = $_SESSION['username'];
    $userrole = $_SESSION['userrole'];
} else {
    header("Location: ../login.php");
    exit();
}

// Replace these values with your Azure SQL Database connection details
$serverName = "tcp:allhereserver.database.windows.net,1433";
$database = $companyname;
$uid = "sqladmin";
$pwd = "#Allhere";

// Check the connection
try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $uid, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed. Please try again later.";
    // Optionally, log the error for debugging purposes
    // error_log($e->getMessage(), 0);
    exit();
}

ob_end_flush();
?>