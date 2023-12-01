<?php
session_start();
function validateInput($data)
{
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateName($name)
{
    if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
        $_SESSION['error_message'] = "Name can only contain alphabets and numbers";
        header("Location: settings-user-create.php");
        exit;
    }
    if (strlen($name) > 255) {
        $_SESSION['error_message'] = "Name cannot exceed 255 characters";
        header("Location: settings-user-create.php");
        exit;
    }
    return $name;
}


// Validate email function
function validateEmail($email)
{
    $pattern = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
    if (preg_match($pattern, $email)) {
        return $email;
    } else {
        $_SESSION['error_message'] = "Invalid email format";
        header("Location: settings-user-create.php");
        exit;
    }
}

// Validate phone function
function validatePhone($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);
    if (strlen($phone) >= 10 && strlen($phone) <= 15) {
        return $phone;
    } else {
        $_SESSION['error_message'] = "Invalid phone number";
        header("Location: settings-user-create.php");
        exit;
    }
}


// Validate password function
function validatePassword($password)
{
    if (strlen($password) >= 6 && preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
        return $password;
    } else {
        $_SESSION['error_message'] = "Password must be at least 6 characters long and contain at least one uppercase letter, one lowercase letter, and one number";
        header("Location: settings-user-create.php");
        exit;
    }
}

//Create user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyid = 1;
    $newusername = validateName($_POST['username']);
    $password = validatePassword($_POST['password']);
    $email = validateEmail($_POST['email']);
    $phone = validatePhone($_POST['phone']);
    $firstname = validateInput($_POST['firstName']);
    $lastname = validateInput($_POST['lastName']);
    $currentDateTime = date('Y-m-d H:i:s');
    $userrole = validateInput($_POST['userrole']);
    $UserStatus = "Active";

    $companyname = $_SESSION['companyname'];

    // Replace these values with your actual database connection details
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $database = $companyname;

    // Create a connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $database);
    $connn = new mysqli("localhost", "root", "", "adminallhere");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        //echo "Connected successfully!";
    }


    // Check if username already exists
    $sql = "SELECT * FROM user WHERE Username = '$newusername'";
    $sqle = "SELECT * FROM user WHERE UserID = '$newusername'";
    $results = mysqli_query($connn, $sqle);

    if (mysqli_num_rows($results) > 0) {
        $_SESSION['error_message'] = "Username already exists";
        header("Location: settings-user-create.php");
        exit;
    }


    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Username already exists";
        header("Location: settings-user-create.php");
        exit;
    } else {
        // Get the biggest UserID and increment it by 1
        $sql = "SELECT MAX(UserID) AS max_id FROM user";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $newUserID = $row['max_id'] + 1;

        // Create user
        $sql = "INSERT INTO User (UserID, CompanyID, Username, Password, Email, Phone, FirstName, LastName, UserStatus, UserRole, LastLoginDate) VALUES ('$newUserID', '$companyid', '$newusername', '$password', '$email', '$phone', '$firstname', '$lastname', '$UserStatus', '$userrole', NOW())";
        if ($conn->query($sql) === TRUE) {

            if ($connn->connect_error) {
                die("Connection");
            } else {
                $sql = "INSERT INTO user (UserID, CompanyName, Status) Values ('$newusername', '$companyname', 'Active')";
                if ($connn->query($sql) === TRUE) {
                    // Redirect back to the previous page or perform any other action
                    header('Location: settings-user.php');
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . $connn->error;
                }
            }

        } else {
            echo "Error creating user: " . $conn->error;
        }
    }
}
?>