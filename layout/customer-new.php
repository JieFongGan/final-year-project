<?php
$pageTitle = "Customer/New";
include '../contain/header.php';
include("../database/database-connect.php");

// Initialize errors array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process form data
    $customerName = trim($_POST["customerName"]);
    $contact = trim($_POST["contact"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $remark = trim($_POST["remark"]); // Added for the new remark column

    // Additional validation checks
    validateCustomerData($customerName, $contact, $email, $address, $remark, $errors);

    // If there are validation errors, display them and stop further processing
    if (!empty($errors)) {
        displayErrors($errors);
    } else {
        // Perform database insertion using prepared statement
        $sql = "INSERT INTO Customer (Name, Contact, Email, Address, Remark) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $customerName, $contact, $email, $address, $remark);

        if ($stmt->execute()) {
            header("Location: customer.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Function to validate customer data
function validateCustomerData($customerName, $contact, $email, $address, $remark, &$errors)
{
    // Validate customer name
    if (empty($customerName)) {
        $errors[] = "Customer name is required.";
    } elseif (strlen($customerName) > 255) {
        $errors[] = "Customer name must be 255 characters or less.";
    }

    // Validate contact
    if (strlen($contact) > 20) {
        $errors[] = "Contact must be 20 characters or less.";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($email) > 255) {
        $errors[] = "Email must be 255 characters or less.";
    }

    // Validate address
    if (strlen($address) > 255) {
        $errors[] = "Address must be 255 characters or less.";
    }

    // Validate remark
    if (strlen($remark) > 255) {
        $errors[] = "Remark must be 255 characters or less.";
    }
}

// Function to display errors
function displayErrors($errors)
{
    echo '<div class="main-content">';
    echo '<main>';
    echo '<div class="form-container">';
    echo '<form action="" method="post">';
    echo '<div class="form-group">';
    echo '<div class="error-container">';
    echo '<p class="error">Please fix the following errors:</p>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

?>

<div class="main-content">
    <?php
    $pathtitle = "Customer/New";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($errors)) {
                        displayErrors($errors);
                    } else {
                        echo '<div class="error-container" style="display:none;"></div>';
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="customerName">Customer name:</label>
                    <input type="text" id="customerName" name="customerName" placeholder="Please enter a customer name"
                        required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" placeholder="Please enter the contact information"
                        required maxlength="20">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Please enter the email" required
                        maxlength="255">
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Please enter the address" required
                        maxlength="255">
                </div>
                <div class="form-group">
                    <label for="remark">Remark:</label>
                    <input type="text" id="remark" name="remark" placeholder="Please enter a remark" required
                        maxlength="255">
                </div>
                <div class="form-group">
                    <button type="submit">Add</button>
                    <button type="button" class="cancel" onclick="window.location.href='customer.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>