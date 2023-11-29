<?php

session_start();

$companyid = '1';
$companyname = validateInput($_POST['company_name']);
$companyemail = validateEmail($_POST['company_email']);
$companyphone = validatePhone($_POST['company_phone_number']);
$companyaddress = validateInput($_POST['company_address']);
$username = validateInput($_POST['username']);
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

// Validate email function
function validateEmail($email)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    } else {
        return false;
    }
}

// Validate phone function
function validatePhone($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);
    if (strlen($phone) >= 10 && strlen($phone) <= 15) {
        return $phone;
    } else {
        return false;
    }
}

// Validate password function
function validatePassword($password)
{
    if (strlen($password) >= 6) {
        return $password;
    } else {
        return false;
    }
}

$authCode = validateInput($_POST['auth_code']);

if (empty($authCode)) {
    echo "No auth code found.";
    header("Location: register.php");
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "adminallhere";
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed:" . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "SELECT CompanyName, PlanType FROM company WHERE AuthCode = ?";
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("s", $authCode);

// Execute the statement
$stmt->execute();

// Store the result
$stmt->store_result();

// Bind the result variables
$stmt->bind_result($companynamestore, $plantypestore);

// Check if there are any results
if ($stmt->num_rows == 0) {
    echo "Authentication Code is not available.";
    header("Location: register.php");
    $stmt->close();
    $conn->close();
    exit;
} else {
    $stmt->close();
    if (!empty($companynamestore)) {
        echo "Company already exists.";
        header("Location: register.php");
        $conn->close();
        exit;
    } else {
        $planType = $plantypestore;
        $conn->query("CREATE DATABASE IF NOT EXISTS $companyname");
        $conn->query("USE $companyname");

        // Create tables
        $conn->query("CREATE TABLE IF NOT EXISTS Company (
                CompanyID INT PRIMARY KEY,
                CompanyName VARCHAR(255) NOT NULL,
                Email VARCHAR(255),
                Phone VARCHAR(20),
                Address VARCHAR(255)
            )");

        // Insert values into Company table
        $conn->query("INSERT INTO Company (CompanyID, CompanyName, Email, Phone, Address) VALUES ('$companyid', '$companyname', '$companyemail', '$companyphone', '$companyaddress')");

        $conn->query("CREATE TABLE IF NOT EXISTS User (
        UserID INT PRIMARY KEY,
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

        $conn->query("INSERT INTO User (UserID, CompanyID, Username, Password, Email, Phone, FirstName, LastName, UserRole, LastLoginDate, UserStatus) VALUES ('1', '$companyid', '$username', '$password', '$email', '$phone', '$firstname', '$lastname', 'Admin', '$currentDateTime', 'Available')");

        $conn->query("CREATE TABLE IF NOT EXISTS Category (
        CategoryID INT PRIMARY KEY,
        Name VARCHAR(50) NOT NULL,
        Description TEXT
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Warehouse (
        WarehouseID INT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Address VARCHAR(255),
        Contact VARCHAR(20),
        Email VARCHAR(255)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Supplier (
        SupplierID INT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Contact VARCHAR(20),
        Email VARCHAR(255)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Customer (
        CustomerID INT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Contact VARCHAR(20),
        Email VARCHAR(255),
        Address VARCHAR(255)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Product (
        ProductID INT PRIMARY KEY,
        CategoryID INT,
        WarehouseID INT,
        Name VARCHAR(255) NOT NULL,
        Description TEXT,
        Price DECIMAL(10, 2),
        Quantity INT,
        SupplierID INT,
        LastUpdatedDate DATETIME,
        FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
        FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
        FOREIGN KEY (SupplierID) REFERENCES Supplier(SupplierID)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS salesOrder (
        OrderID INT PRIMARY KEY,
        CustomerID INT,
        OrderDate DATETIME,
        TotalAmount DECIMAL(10, 2),
        DeliveryStatus VARCHAR(50),
        FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS PurchaseOrder (
        OrderID INT PRIMARY KEY,
        SupplierID INT,
        OrderDate DATETIME,
        TotalAmount DECIMAL(10, 2),
        DeliveryStatus VARCHAR(50),
        FOREIGN KEY (SupplierID) REFERENCES Supplier(SupplierID)
        )");

        $conn->query("CREATE TABLE IF NOT EXISTS Transaction (
        TransactionID INT PRIMARY KEY,
        WarehouseID INT,
        OrderID INT,
        ProductID INT,
        Quantity INT,
        TransactionType VARCHAR(50),
        TransactionDate DATETIME,
        FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
        FOREIGN KEY (OrderID) REFERENCES salesOrder(OrderID),
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
        $status = "Available";
        // Update the Company table
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
        header("Location: index.php");
    }
}

?>