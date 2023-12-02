<?php

session_start();

$companyid = '1';
$companyname = validateName($_POST['company_name']);
$companyemail = validateEmail($_POST['company_email']);
$companyphone = validatePhone($_POST['company_phone_number']);
$companyaddress = validateInput($_POST['company_address']);
$username = validateName($_POST['username']);
$password = validatePassword($_POST['password']);
$email = validateEmail($_POST['email']);
$phone = validatePhone($_POST['phone_number']);
$firstname = validateInput($_POST['first_name']);
$lastname = validateInput($_POST['last_name']);
$currentDateTime = date('Y-m-d H:i:s');

// Validate input function
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
        header("Location: register.php");
        exit;
    }
    if (strlen($name) > 255) {
        $_SESSION['error_message'] = "Name cannot exceed 255 characters";
        header("Location: register.php");
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
        header("Location: register.php");
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
        header("Location: register.php");
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
        header("Location: register.php");
        exit;
    }
}

$authCode = validateInput($_POST['auth_code']);

if (empty($authCode)) {
    $_SESSION['error_message'] = "No authentication code found";
    header("Location: register.php");
    exit;
}

// Database connection
try {
    $conn = new PDO(
        "sqlsrv:server = tcp:allhereserver.database.windows.net,1433; Database = master",
        "sqladmin",
        "#Allhere",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the user already exists
$sql = "SELECT UserID FROM [User] WHERE UserID = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $_SESSION['error_message'] = "Username already exists";
    header("Location: register.php");
    exit;
}

// Bind the parameters
$stmt = $conn->prepare("SELECT CompanyName FROM Company WHERE AuthCode = :authCode");
$stmt->bindParam(":authCode", $authCode, PDO::PARAM_STR);

// Execute the statement
$stmt->execute();

// Fetch the result
$companynamestore = $stmt->fetchColumn();

// Check if there are any results
if (!$companynamestore) {
    $_SESSION['error_message'] = "Authentication Code is not available.";
    header("Location: register.php");
    exit;
} else {
    $stmt->closeCursor();
    if (!empty($companynamestore)) {
        $_SESSION['error_message'] = "Company already exists.";
        header("Location: register.php");
        exit;
    } else {
        // Dynamically create the database
        $conn->query("CREATE DATABASE [$companyname]");

        // Switch to the new database
        $conn->query("USE [$companyname]");

        // Create tables
        $conn->query("CREATE TABLE Company (
            CompanyID INT PRIMARY KEY,
            CompanyName VARCHAR(255) NOT NULL,
            Email VARCHAR(255),
            Phone VARCHAR(20),
            Address VARCHAR(255)
        )");

        // Insert values into Company table
        $conn->query("INSERT INTO Company (CompanyID, CompanyName, Email, Phone, Address) VALUES ('$companyid', '$companyname', '$companyemail', '$companyphone', '$companyaddress')");

        $conn->query("CREATE TABLE IF NOT EXISTS User (
        UserID INT AUTO_INCREMENT PRIMARY KEY,
        CompanyID INT,
        Username VARCHAR(50) NOT NULL,
        Password VARCHAR(50) NOT NULL,
        Email VARCHAR(255),
        Phone VARCHAR(20),
        FirstName VARCHAR(50),
        LastName VARCHAR(50),
        UserRole VARCHAR(50),
        LastLoginDate DATETIME,
        UserStatus VARCHAR(20),
        FOREIGN KEY (CompanyID) REFERENCES Company(CompanyID)
        )");

        $conn->query("INSERT INTO User (UserID, CompanyID, Username, Password, Email, Phone, FirstName, LastName, UserRole, LastLoginDate, UserStatus) VALUES ('1', '$companyid', '$username', '$password', '$email', '$phone', '$firstname', '$lastname', 'Admin', Now(), 'Active')");

        $conn->query("CREATE TABLE IF NOT EXISTS Category (
        CategoryID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(50) NOT NULL,
        Description TEXT
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Warehouse (
        WarehouseID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Address VARCHAR(255),
        Contact VARCHAR(20),
        Email VARCHAR(255)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Customer (
        CustomerID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Contact VARCHAR(20),
        Email VARCHAR(255),
        Address VARCHAR(255),
        Remark VARCHAR(255)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Product (
        ProductID INT AUTO_INCREMENT PRIMARY KEY,
        CategoryID INT,
        WarehouseID INT,
        Name VARCHAR(255) NOT NULL,
        Description TEXT,
        Price DECIMAL(10, 2),
        Quantity INT,
        LastUpdatedDate DATETIME,
        FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
        FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Transaction (
        TransactionID INT AUTO_INCREMENT PRIMARY KEY,
        WarehouseID INT,
        CustomerID INT,
        TransactionType VARCHAR(50),
        TransactionDate DATETIME,
        DeliveryStatus VARCHAR(50),
        FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
        FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS TransactionDetail (
            TransactionDetailID INT AUTO_INCREMENT PRIMARY KEY,
            TransactionID INT,
            ProductID INT,
            Quantity INT,
            FOREIGN KEY (TransactionID) REFERENCES Transaction(TransactionID),
            FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
        )");

        // Connect to the database
        $servername = "localhost";
        $user = "root";
        $password = "";
        $dbname = "adminallhere";

        $conn = new mysqli($servername, $user, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert data into the Company table
        $status = "Active";
        // Update the Company table

        $companyname = strtolower($companyname);

        $sql = "UPDATE Company SET CompanyName = '$companyname', Status = '$status' WHERE AuthCode = '$authCode'";

        if ($conn->query($sql) === TRUE) {
            echo "Data updated successfully";
        } else {
            echo "Error updating data: " . $conn->error;
        }

        // Insert data into the User table
        $sql = "INSERT INTO User (UserID, CompanyName, Status) VALUES ('$username', '$companyname', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "Data inserted successfully";
        } else {
            echo "Error inserting data: " . $conn->error;
        }

        $conn->close();
        $_SESSION['companyname'] = $companyname;
        $_SESSION['username'] = $username;
        $_SESSION['userrole'] = "Admin";
        header("Location: layout/homepage.php");
    }
}

?>