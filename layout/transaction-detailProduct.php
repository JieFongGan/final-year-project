<?php
ob_start(); // Start output buffering
$pageTitle = "Transactions/Detail";
include("../database/database-connect.php");
include '../contain/header.php';

// Check if the form is submitted
$isFormSubmitted = ($_SERVER["REQUEST_METHOD"] == "POST");

// Initialize variables
$transactionData = [];
$productSql = "SELECT td.TransactionDetailID, td.ProductID, p.Name, p.Price, td.Quantity 
               FROM TransactionDetail td
               JOIN Product p ON td.ProductID = p.ProductID
               WHERE td.TransactionID = ?";
$productStmt = $conn->prepare($productSql);

// Check if transaction ID is set in the URL
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

        // Fetch product details for the specific transaction
        $productStmt->bind_param("i", $transactionID);
        $productStmt->execute();
        $productResult = $productStmt->get_result();
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
?>

<div class="main-content">
    <?php
    $pathtitle = "Transaction/Detail";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <form action="" method="post">
            <div class="form-container">
                <div class="form-group">
                    <label for="transactionID">Transaction ID:</label>
                    <input type="text" id="transactionID" name="transactionID"
                        value="<?= $transactionData['TransactionID'] ?>" readonly>
                </div>
                <div class="form-group">
                    <h3>Transaction Details</h3>
                    <table id="productTable" class="table-container" style="width:100%">
                        <thead>
                            <tr>
                                <th>TransactionDetail ID</th>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <?php
                            if ($productResult && $productResult->num_rows > 0) {
                                while ($product = $productResult->fetch_assoc()) {
                                    echo '<tr>
                        <td>' . $product["TransactionDetailID"] . '</td>
                        <td>' . $product["ProductID"] . '</td>
                        <td>' . $product["Name"] . '</td>
                        <td>' . $product["Price"] . '</td>
                        <td>' . $product["Quantity"] . '</td>
                    </tr>';
                                }
                            } else {
                                echo "<tr><td colspan='5'>No products found for this transaction</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button type="button" class="cancel"
                        onclick="window.location.href='transaction.php'">Cancel</button>
                </div>
            </div>
        </form>
    </main>
</div>

<?php
ob_end_flush(); // Flush the output buffer and turn off output buffering
?>

</body>

</html>