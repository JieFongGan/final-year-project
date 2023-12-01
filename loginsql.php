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
    if (strlen($password) >= 6 && preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
        return $password;
    } else {
        $_SESSION['error_message'] = "Password must be at least 6 characters long and contain at least one uppercase letter, one lowercase letter, and one number";
        header("Location: login.php");
        exit;
    }
}



if ($username && $password) {
    $sql = "SELECT CompanyName, Status FROM user WHERE UserID = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyname = $row['CompanyName'];
        $Status = $row['Status'];

        if ($Status == "Disable") {
            $_SESSION['error_message'] = "Account has been terminated";
            header("Location: login.php");
            exit;
        }
        $sql = "SELECT Status FROM company WHERE CompanyName = '$companyname'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $Status = $row['Status'];
        if ($Status == 'Disable') {
            $_SESSION['error_message'] = "Company has been terminated";
            header("Location: login.php");
            exit;
        }

        $cone = new mysqli($servername, $dbusername, $dbpassword, $companyname);

        if ($cone->connect_error) {
            die("Connection failed: " . $cone->connect_error);
        }

        $sql = "SELECT Username, Password, UserRole FROM user WHERE Username = '$username'";

        $result = $cone->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['Password'];
            $userrole = $row['UserRole'];

            if ($password === $storedPassword) {
                // Update LastLoginDate
                $updateSql = "UPDATE user SET LastLoginDate = NOW() WHERE Username = '$username'";
                $cone->query($updateSql);

                $_SESSION['companyname'] = $companyname;
                $_SESSION['username'] = $username;
                $_SESSION['userrole'] = $userrole;
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Incorrect password";
                header("Location: login.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Username not found";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Username not found";
        header("Location: login.php");
        exit;
    }
}

$conn->close();
?>
                
                