<?php
$pageTitle = "Settings/User-edit";
include '../contain/header.php';
include("../database/database-connect.php");

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

        // Retrieve UserRole from the form data
        $userRole = $_POST['userRole'];

        // Update the user's UserRole in the database
        $updateSql = "UPDATE User SET UserRole = ? WHERE UserID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $userRole, $userID);
        $updateStmt->execute();

        // Check if the update was successful
        if ($updateStmt->affected_rows > 0) {
            echo "User role updated successfully.";
            header("Location: settings-user.php");
            exit();
        } else {
            echo "Error updating user role: " . $updateStmt->error;
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
                    <label for="userRole">User Role:</label>
                    <input type="text" id="userRole" name="userRole" value="<?= $userData['UserRole'] ?>"
                        placeholder="User role" required>
                </div>
                <div class="form-group">
                    <input type="hidden" name="userID" value="<?= $userData['UserID'] ?>">
                    <button type="submit">Update</button>
                    <button type="button" class="cancel" onclick="window.location.href='user.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>
