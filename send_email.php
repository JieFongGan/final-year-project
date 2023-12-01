<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Assuming you have already established a database connection
$username = $_POST['username'];
$companyName = $_POST['companyName'];

try {
    $checkvalidcompany = new PDO(
        "sqlsrv:server = tcp:allhereserver.database.windows.net,1433; Database = allheredb",
        "sqladmin",
        "#Allhere",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    // Use prepared statements to prevent SQL injection
    $checkvalidcompanyquery = "SELECT CompanyName FROM [user] WHERE CompanyName = :companyName";
    $stmt = $checkvalidcompany->prepare($checkvalidcompanyquery);
    $stmt->bindParam(':companyName', $companyName);
    $stmt->execute();

    // Check the number of rows returned
    if ($stmt->rowCount() == 0) {
        session_start();
        $_SESSION['error_message'] = "Company does not exist";
        header("Location: forgetpassword.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error checking company existence: " . $e->getMessage());
}


$conn = new PDO(
    "sqlsrv:server = tcp:allhereserver.database.windows.net,1433; Database = $companyName",
    "sqladmin",
    "#Allhere",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);

$username = mysqli_real_escape_string($connection, $_POST['username']);
$companyName = mysqli_real_escape_string($connection, $_POST['companyName']);

// Query the database to retrieve the password based on the username
$query = "SELECT email, password FROM user WHERE username = '$username'";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the email and password from the result
    $row = mysqli_fetch_assoc($result);
    $password = $row['password'];
    $email = $row['email'];
    // Send email using PHPMailer
    $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0; // Set to 2 for debugging
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'allherewebapp@gmail.com'; // Replace with your SMTP username
    $mail->Password = 'pplc xcrs ocwx nkpx'; // Replace with your SMTP password
    $mail->SMTPSecure = 'ssl'; 
    $mail->Port = 465; 

    //Recipients
    $mail->setFrom('allherewebapp@gmail.com', 'All Here');
    $mail->addAddress($email); // Add recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reminder';
    $mail->Body = "Your password is: $password";

    $mail->send();
    header('Location: login.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error_message'] = "Failed to send email. Error: {$mail->ErrorInfo}";
    header("Location: forgetpassword.php");
}
} else {
    $_SESSION['error_message'] = "Failed to retrieve password from the database.";
    header("Location: forgetpassword.php");
}

// Close the database connection
mysqli_close($connection);

?>
