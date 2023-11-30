<?php
$pageTitle = "Settings/Users";
include("../database/database-connect.php");
include '../contain/header.php';

// Fetch all users
$sqlAllUsers = "SELECT * FROM User";
$resultAllUsers = $conn->query($sqlAllUsers);

if (!$resultAllUsers) {
    echo "Error fetching all users: " . $conn->error;
    exit();
}

$allUsers = $resultAllUsers->fetch_all(MYSQLI_ASSOC);

// Delete user logic
if (isset($_POST['deleteUser'])) {
    $userIDToDelete = $_POST['deleteUser'];

    // Get the username of the user to be deleted
    $sqlGetUsername = "SELECT Username FROM User WHERE UserID = $userIDToDelete";
    $resultGetUsername = $conn->query($sqlGetUsername);

    if (!$resultGetUsername) {
        echo "Error fetching username: " . $conn->error;
        exit();
    }

    $usernameToDelete = $resultGetUsername->fetch_assoc()['Username'];

    // Create a new connection for the other database
    $connn = new mysqli('localhost', 'root', '', 'adminallhere');

    // Check the new connection
    if ($connn->connect_error) {
        die("Connection failed: " . $connn->connect_error);
    }

    // Use a prepared statement to prevent SQL injection
    $sqlDeleteOtherTable = "DELETE FROM user WHERE UserID = ?";
    $stmt = $connn->prepare($sqlDeleteOtherTable);

    // Bind parameters
    $stmt->bind_param('s', $usernameToDelete);

    // Execute the statement
    $stmt->execute();

    // Check for errors
    if ($stmt->error) {
        echo "Error deleting from other table: " . $stmt->error;
        exit();
    }

    // Close the statement
    $stmt->close();

    // Perform the deletion
    $sqlDeleteUser = "DELETE FROM User WHERE UserID = $userIDToDelete";
    $resultDeleteUser = $conn->query($sqlDeleteUser);
    if (!$resultDeleteUser) {
        echo "Error deleting user: " . $conn->error;
        exit();
    }

    // Redirect to the same page to refresh the user list
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Settings/Users";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="button-and-search">
            <button name="createUser"><a href="settings-user-create.php"
                    style="text-decoration: none; color: white;">Create new user</a></button>
            <input type="text" id="searchInput" placeholder="Search on current list..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table-container" style="width:100%">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>UserRole</th>
                        <th>LastLoginDate</th>
                        <th>UserStatus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($allUsers)): ?>
                        <tr>
                            <td colspan="10">No data available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td>
                                    <?= $user['UserID'] ?>
                                </td>
                                <td>
                                    <?= $user['Username'] ?>
                                </td>
                                <td>
                                    <?= $user['Email'] ?>
                                </td>
                                <td>
                                    <?= $user['Phone'] ?>
                                </td>
                                <td>
                                    <?= $user['FirstName'] ?>
                                </td>
                                <td>
                                    <?= $user['LastName'] ?>
                                </td>
                                <td>
                                    <?= $user['UserRole'] ?>
                                </td>
                                <td>
                                    <?= $user['LastLoginDate'] ?>
                                </td>
                                <td>
                                    <?= $user['UserStatus'] ?>
                                </td>
                                <td>
                                    <form method="GET" action="settings-user-edit.php">
                                        <input type="hidden" name="userID" value="<?= $user['UserID'] ?>">
                                        <button class="edit" type="submit">edit</button>
                                    </form>

                                    <form method="POST">
                                        <button class="delete" name="deleteUser" type="submit"
                                            onclick="return confirm('Are you sure you want to remove this user?')">remove
                                        </button>
                                        <input type="hidden" name="deleteUser" value="<?= $user['UserID'] ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>

</html>