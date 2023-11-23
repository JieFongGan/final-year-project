<?php
$pageTitle = "Supplier/Edit";
include '../contain/header.php';
include("../database/database-connect.php");

if (isset($_GET['supplierID'])) {
    $supplierID = $_GET['supplierID'];

    // Fetch supplier information based on the supplier ID
    $sql = "SELECT * FROM Supplier WHERE SupplierID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $supplierID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $supplierData = $result->fetch_assoc();
    } else {
        // Handle the case where no supplier is found with the given ID
        echo "Supplier not found.";
        exit();
    }

    $stmt->close();
} else {
    // Handle the case where supplier ID is not set
    echo "Supplier ID not specified.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the supplier ID is set in the form
    if (isset($_POST['supplierID'])) {
        $supplierID = $_POST['supplierID'];

        // Retrieve other form data
        $supplierName = $_POST['supplierName'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        // Update the supplier in the database
        $updateSql = "UPDATE Supplier SET 
                      Name = ?, 
                      Contact = ?, 
                      Email = ? 
                      WHERE SupplierID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssi", $supplierName, $contact, $email, $supplierID);
        $updateStmt->execute();

        // Check if the update was successful
        if ($updateStmt->affected_rows > 0) {
            echo "Supplier updated successfully.";
            header("Location: supplier.php");
            exit();
        } else {
            echo "Error updating supplier: " . $updateStmt->error;
        }

        $updateStmt->close();
    }
}

?>

<div class="main-content">
    <?php
    $pathtitle = "Supplier/Edit";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="supplierID">Supplier ID:</label>
                    <input type="text" id="supplierID" name="supplierID" value="<?= $supplierData['SupplierID'] ?>"
                        readonly>
                </div>
                <div class="form-group">
                    <label for="supplierName">Supplier Name:</label>
                    <input type="text" id="supplierName" name="supplierName" value="<?= $supplierData['Name'] ?>"
                        placeholder="Supplier name" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" value="<?= $supplierData['Contact'] ?>"
                        placeholder="Contact information">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $supplierData['Email'] ?>"
                        placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="hidden" name="supplierID" value="<?= $supplierData['SupplierID'] ?>">
                    <button type="submit">Update</button>
                    <button type="button" class="cancel" onclick="window.location.href='supplier.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>
