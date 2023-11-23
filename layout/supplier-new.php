<?php
$pageTitle = "Supplier/New";
include '../contain/header.php';
include("../database/database-connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process form data
    $supplierName = mysqli_real_escape_string($conn, $_POST["supplierName"]);
    $contact = mysqli_real_escape_string($conn, $_POST["contact"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // Additional validation checks
    $errors = [];

    // Validate supplier name
    if (empty($supplierName)) {
        $errors[] = "Supplier name is required.";
    } elseif (strlen($supplierName) > 255) {
        $errors[] = "Supplier name must be 255 characters or less.";
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

    // If there are validation errors, display them and stop further processing
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    } else {
        // Perform database insertion
        $sql = "INSERT INTO Supplier (Name, Contact, Email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $supplierName, $contact, $email);

        if ($stmt->execute()) {
            header("Location: supplier.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

?>
<div class="main-content">
    <?php
    $pathtitle = "Supplier/New";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($errors)) {
                        echo '<div class="error-container">';
                        echo '<p class="error">Please fix the following errors:</p>';
                        echo '<ul>';
                        foreach ($errors as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '<div class="error-container" style="display:none;"></div>';
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="supplierName">Supplier name:</label>
                    <input type="text" id="supplierName" name="supplierName" placeholder="Please enter a supplier name"
                        required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" placeholder="Please enter the contact information" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Please enter the email" required maxlength="255">
                </div>
                <div class="form-group">
                    <button type="submit">Add</button>
                    <button type="button" class="cancel" onclick="window.location.href='supplier.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>
