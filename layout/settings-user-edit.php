<?php
$pageTitle = "Settings/User-edit";
include("../database/database-connect.php");
include '../contain/header.php';

if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Fetch user information based on the user ID
    $sql = "SELECT * FROM User WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        // Handle the case where no user is found with the given ID
        echo "User not found.";
        exit();
    }

    $stmt->close();
} else {
    // Handle the case where user ID is not set
    echo "User ID not specified.";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user ID is set in the form
    if (isset($_POST['userID'])) {
        $userID = $_POST['userID'];

        // Retrieve UserRole and UserStatus from the form data
        $userRole = $_POST['userRole'];
        $userStatus = $_POST['userStatus'];

        // Update the user's UserRole and UserStatus in the database
        $updateSql = "UPDATE User SET UserRole = ?, UserStatus = ? WHERE UserID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssi", $userRole, $userStatus, $userID);
        $updateStmt->execute();

        // Check if the update was successful
        if ($updateStmt->affected_rows > 0) {
            // Create a new connection using the company name
            
            $connn = new mysqli("localhost", "root", "", "adminallhere");
            
            // Update user status in the new connection
            $sql = "UPDATE User SET Status = ? WHERE UserID = ?";
            $stmt = $connn->prepare($sql);
            $stmt->bind_param("ss", $userStatus, $userData['Username']);

            // Check if the second update was successful
            if ($stmt->execute()) {
                // Redirect back to the previous page
                header('Location: settings-user.php');
                exit();
            } else {
                echo "Error updating user status: " . $connn->error;
            }
        } else {
            echo "Error updating user details: " . $conn->error;
        }

        $updateStmt->close();
    }
}

?>

<div class="main-content">
    <?php
    $pathtitle = "Settings/User-edit";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="userID">User ID:</label>
                    <input type="text" id="userID" name="userID" value="<?= $userData['UserID'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= $userData['Username'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="userRole">User Role:</label>
                    <select id="userRole" name="userRole">
                        <option value="User" <?php if ($userData['UserRole'] == 'User')
                            echo 'selected'; ?>>User</option>
                        <option value="Manager" <?php if ($userData['UserRole'] == 'Manager')
                            echo 'selected'; ?>>Manager
                        </option>
                        <option value="Admin" <?php if ($userData['UserRole'] == 'Admin')
                            echo 'selected'; ?>>Admin
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="userStatus">User Status:</label>
                    <select id="userStatus" name="userStatus">
                        <option value="Active" <?php if ($userData['UserStatus'] == 'Active')
                            echo 'selected'; ?>>Active
                        </option>
                        <option value="Disable" <?php if ($userData['UserStatus'] == 'Disable')
                            echo 'selected'; ?>>
                            Disable</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="hidden" name="userID" value="<?= $userData['UserID'] ?>">
                    <button type="submit">Update</button>
                    <button type="button" class="cancel"
                        onclick="window.location.href='settings-user.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>