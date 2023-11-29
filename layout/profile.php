<?php
$pageTitle = "Profile";
include '../contain/header.php';
include("../database/database-connect.php");

// Retrieve the userID from the URL parameter
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Fetch user data based on the userID
    $userDataQuery = mysqli_query($conn, "SELECT * FROM User WHERE UserID = $userID");
    $userData = mysqli_fetch_assoc($userDataQuery);

    // Rest of your code for the profile page
} else {
    // Handle the case where userID is not provided
    echo "User ID not provided.";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a function to sanitize user input, apply it here
    $userRole = $_POST['userRole'];
    $phone = $_POST['phone'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    // File upload handling
    if ($_FILES['profileImage']['error'] == 0) {
        $uploadDir = 'uploads/'; // Specify the directory where you want to store the uploaded files
        $uploadFile = $uploadDir . basename($_FILES['profileImage']['name']);

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
            // File was uploaded successfully, you can save the file path in the database
            $filePath = $uploadFile;
            // Add the file path to the updateUserProfile function
            $success = updateUserProfile($userID, $userRole, $phone, $firstName, $lastName, $filePath);

            if ($success) {
                // Redirect to the profile page or any other appropriate page after successful update
                header('Location: profile.php');
                exit();
            } else {
                // Handle the update failure, you can display an error message or log the error
                echo "Failed to update the profile.";
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        // Handle other form data here if needed
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