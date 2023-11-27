<?php
$pageTitle = "Transaction/Edit";
include '../contain/header.php';
include("../database/database-connect.php");

// Assuming you have a database connection and a query to fetch customer data
$customerSql = "SELECT CustomerID, Name, Remark FROM Customer";
$customerResult = $conn->query($customerSql);

// Check if the query was successful
if (!$customerResult) {
    die("Error retrieving customer data: " . $conn->error);
}

if (isset($_GET['transactionID'])) {
    $transactionID = $_GET['transactionID'];

    // Fetch transaction information based on the transaction ID
    $sql = "SELECT * FROM Transaction WHERE TransactionID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transactionID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transactionData = $result->fetch_assoc();
    } else {
        // Handle the case where no transaction is found with the given ID
        echo "Transaction not found.";
        exit();
    }

    $stmt->close();
} else {
    // Handle the case where transaction ID is not set
    echo "Transaction ID not specified.";
    exit();
}

// Check if the form is submitted
$isFormSubmitted = ($_SERVER["REQUEST_METHOD"] == "POST");

if ($isFormSubmitted) {
    // Check if the transaction ID is set in the form
    if (isset($_POST['transactionID'])) {
        $transactionID = $_POST['transactionID'];

        // Retrieve other form data
        $customerID = $_POST['customerID'];
        $deliveryStatus = implode(', ', $_POST['deliveryStatus']);

        // Update the transaction in the database
        $updateSql = "UPDATE Transaction SET 
                      CustomerID = ?, 
                      DeliveryStatus = ? 
                      WHERE TransactionID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssi", $customerID, $deliveryStatus, $transactionID);
        $updateStmt->execute();

        // Check if the update was successful
        if ($updateStmt->affected_rows > 0) {
            header("Location: transaction.php");
            exit();
        } else {
            echo "Error updating transaction: " . $updateStmt->error;
        }

        $updateStmt->close();
    }
}
?>

<div class="main-content">
    <?php
    $pathtitle = "Transaction/Edit";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="transactionID">Transaction ID:</label>
                    <input type="text" id="transactionID" name="transactionID"
                        value="<?= $transactionData['TransactionID'] ?>" readonly>
                </div>
                <div class="form-group">
                    <div class="table-responsive" id="customerSection">
                        <div class="form-group">
                            <h3>Customers</h3>
                            <table id="customerTable" class="table-container" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="customerTableBody">
                                    <?php
                                    if ($customerResult && $customerResult->num_rows > 0) {
                                        while ($customer = $customerResult->fetch_assoc()) {
                                            $isChecked = ($customer['CustomerID'] == $transactionData['CustomerID']) ? 'checked' : '';
                                            echo '<tr>
                                <td>' . $customer["CustomerID"] . '</td>
                                <td>' . $customer["Name"] . '</td>
                                <td>' . $customer["Remark"] . '</td>
                                <td><input type="radio" name="customerID" value="' . $customer["CustomerID"] . '" ' . $isChecked . '></td>
                            </tr>';
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No customers found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="deliveryStatus">Delivery Status:</label>
                    <div class="styled-select">
                        <select id="deliveryStatus" name="deliveryStatus[]" required>
                            <option value="" disabled>Please select a delivery Status</option>
                            <option value="Pending" <?= (in_array('Pending', explode(', ', $transactionData['DeliveryStatus']))) ? 'selected' : '' ?>>Pending</option>
                            <option value="In Progress" <?= (in_array('In Progress', explode(', ', $transactionData['DeliveryStatus']))) ? 'selected' : '' ?>>In Progress</option>
                            <option value="Completed" <?= (in_array('Completed', explode(', ', $transactionData['DeliveryStatus']))) ? 'selected' : '' ?>>Completed</option>
                            <option value="Completed" <?= (in_array('Completed', explode(', ', $transactionData['DeliveryStatus']))) ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit">Edit</button>
                    <button type="button" class="cancel"
                        onclick="window.location.href='transaction.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>