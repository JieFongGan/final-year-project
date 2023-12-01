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
$conn = new PDO(
    "sqlsrv:server = tcp:allhereserver.database.windows.net,1433; Database = $companyname",
    "sqladmin",
    "#Allhere",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);

?>
