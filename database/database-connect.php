<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variable is set
if (isset($_SESSION['companyname'])) {
    $companyname = $_SESSION['companyname'];
    $username = $_SESSION['username'];
    $userrole = $_SESSION['userrole'];
} else {
    header("Location: ../login.php");
}

// Replace these values with your Azure SQL Database connection details
$serverName = "your-server-name.database.windows.net";
$database = $companyname;
$uid = "sqladmin";
$pwd = "#Allhere";

// Check the connection
try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database;Encrypt=true;TrustServerCertificate=false", $uid, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
