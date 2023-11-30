<?php
$pageTitle = "Profile";
include '../contain/header.php';
include("../database/database-connect.php");

// Fetch user data based on the username
$userDataQuery = mysqli_query($conn, "SELECT * FROM User WHERE Username = '$username'");
$userData = mysqli_fetch_assoc($userDataQuery);

// Define variables for error messages
$currentPasswordError = $newPasswordError = $retypePasswordError = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $retypePassword = $_POST['retypePassword'];

    // Validate if the current password matches the stored hashed password
    if (password_verify($currentPassword, $userData['Password'])) {
        // Check if the new password and retype password match
        if ($newPassword === $retypePassword) {
            // Assuming you have a function to hash the password, apply it here
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Create a connection
            $conn = new mysqli($servername, $dbusername, $dbpassword, $database);

            $sql = "UPDATE user SET Password = '$hashedPassword' WHERE Username = '$username'";
            // Update the user password in the database
            $updatePasswordQuery = mysqli_query($conn, $sql);
            if ($updatePasswordQuery === TRUE) {
                // Password updated successfully
                echo "Password updated successfully.";
                header("Location: ../index.php");
                exit();
            }else {
                // Handle the update failure
                echo "Failed to update the password.";
            }
        } else {
            // Passwords do not match
            $retypePasswordError = "New password and retype password do not match.";
        }
    } else {
        // Current password is incorrect
        $currentPasswordError = "Current password is incorrect.";
    }
}
?>

<div class="main-content">

    <?php
    $pathtitle = "Profile";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="currentPassword">Current password:</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                    <span class="error">
                        <?= $currentPasswordError ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="newPassword">New password:</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="retypePassword">Retype new password:</label>
                    <input type="password" id="retypePassword" name="retypePassword" required>
                    <span class="error">
                        <?= $retypePasswordError ?>
                    </span>
                </div>
                <div class="form-group">
                    <input type="hidden" name="userID" value="<?= $userData['UserID'] ?>">
                    <button type="submit">Change Password</button>
                    <button type="button" class="cancel" onclick="window.location.href='../index.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>

</div>
</body>

</html>