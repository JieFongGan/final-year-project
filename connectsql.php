<?php

$companyname = $_POST['company_name'];
$email = $_POST['email'];
$phone = $_POST['phone_number'];
$address = $_POST['address'];
$plan = $_POST['plan'];

$conn = mysqli_connect('localhost','root','','allhere');
if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);
}else{
    // Select the latest CompanyID
    $result = mysqli_query($conn, "SELECT CompanyID FROM company ORDER BY CompanyID DESC LIMIT 1");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $latestCompanyID = $row['CompanyID'];
        // Increment the latest CompanyID
        $companyid = 'C' . str_pad((intval(substr($latestCompanyID, 1)) + 1), 3, '0', STR_PAD_LEFT);
    } else {
        // If no existing CompanyID, set the initial value
        $companyid = 'C001';
    }

    $stmt = $conn->prepare("INSERT INTO company (CompanyID, CompanyName, Email, Phone, Address, PlanType)
    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $companyid, $companyname, $email, $phone, $address, $plan);
    $stmt->execute();
    echo "Registration Successfully...";
    $stmt->close();
    $conn->close();
}
?>