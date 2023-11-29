<?php

session_start();

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "adminallhere";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = validateInput($_POST['username']);
$password = validatePassword($_POST['password']);

function validateInput($data)
{
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validatePassword($password)
{
    if (strlen($password) >= 6) {
        return $password;
    } else {
        return false;
    }
}

if ($username && $password) {
    $sql = "SELECT CompanyName FROM user WHERE UserID = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyname = $row['CompanyName'];

        $cone = new mysqli($servername, $dbusername, $dbpassword, $companyname);

        if ($cone->connect_error) {
            die("Connection failed: " . $cone->connect_error);
        }

        $sql = "SELECT Username, Password FROM user WHERE Username = '$username'";

        $result = $cone->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['Password'];

            if ($password === $storedPassword) {
                $_SESSION['companyname'] = $companyname;
                header("Location: index.php");
                exit;
            } else {
                header("Location: login.php");
                exit;
            }
        } else {
            header("Location: login.php");
            exit;
        }

    } else {
        header("Location: login.php");
        exit;
    }
}

$conn->close();
?>