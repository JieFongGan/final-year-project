<?php
$pageTitle = "Profile";
include '../contain/header.php';
include("../database/database-connect.php");
    // Fetch user data based on the username
    $userDataQuery = mysqli_query($conn, "SELECT * FROM User WHERE Username = '$username'");
    echo $username;
    $userData = mysqli_fetch_assoc($userDataQuery);


    // Update user data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_POST['userID'];
        $userRole = $_POST['userRole'];
        $phone = $_POST['phone'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        $sql = "UPDATE User SET Email = '$userRole', Phone = '$phone', FirstName = '$firstName', LastName = '$lastName' WHERE UserID = '$userID'";
        if ($conn->query($sql) === TRUE) {
            // Redirect back to the previous page or perform any other action
            header('Location: ../index.php');
            exit;
        } else {
            echo "Error updating user details: " . $conn->error;
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
                    <label for="userID">Username:</label>
                    <input type="text" id="userID" name="userID" value="<?= $userData['Username'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="userRole">Email:</label>
                    <input type="text" id="userRole" name="userRole" value="<?= $userData['Email'] ?>"
                        placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?= $userData['Phone'] ?>" placeholder="Phone"
                        required>
                </div>
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" value="<?= $userData['FirstName'] ?>"
                        placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" value="<?= $userData['LastName'] ?>"
                        placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <input type="hidden" name="userID" value="<?= $userData['UserID'] ?>">
                    <button type="submit">Edit</button>
                    <button type="button" class="cancel"
                        onclick="window.location.href='../index.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>

</div>
</body>

</html>