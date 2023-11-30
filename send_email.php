<?php

// Assuming you have already established a database connection
$username = $_POST['username'];
$companyName = $_POST['companyName'];

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = $companyName;

// Create connection
$connection = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

// Query the database to retrieve the password based on the username
$query = "SELECT email, password FROM user WHERE username = '$username'";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the email and password from the result
    $row = mysqli_fetch_assoc($result);
    $password = $row['password'];
    $email = $row['email'];

    // Send email to the retrieved email address
    $to = $email;
    $subject = "Password Reminder";
    $message = "Your password is: $password";
    $headers = "From: allherecompany@gmail.com";

    if (mail($to, $subject, $message, $headers)) {
        header('Location: login.php');
    } else {
        echo "Failed to send email.";
    }
} else {
    echo "Failed to retrieve password from the database.";
}

// Close the database connection
mysqli_close($connection);

?>
