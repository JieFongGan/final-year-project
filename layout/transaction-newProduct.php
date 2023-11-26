<?php
session_start();
$pageTitle = "Transactions/New-product";
include '../contain/header.php';
include("../database/database-connect.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $quantities = $_POST['quantities'] ?? [];
    $selectedProducts = $_POST['selectedProducts'] ?? [];

    // Assuming you have a transactions table in your database
    $insertTransactionSql = "INSERT INTO Transaction (WarehouseID, TransactionType, CustomerID, TransactionDate, DeliveryStatus) VALUES (?, ?, ?, NOW(), 'Pending')";
    $stmt = $conn->prepare($insertTransactionSql);
    $stmt->bind_param("iss", $_SESSION['selectedWarehouse'], $_SESSION['selectedTransactionType'], $_SESSION['selectedCustomer']);
    $stmt->execute();
    $stmt->close();

    // Get the ID of the last inserted transaction
    $lastTransactionId = $conn->insert_id;

    // Insert selected products and quantities into a transaction details table
    $insertDetailsSql = "INSERT INTO TransactionDetail (TransactionID, ProductID, Quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertDetailsSql);

    foreach ($selectedProducts as $key => $productId) {
        $quantity = $quantities[$key] ?? 0; // Assuming $quantities is indexed the same as $selectedProducts
        $stmt->bind_param("iii", $lastTransactionId, $productId, $quantity);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Redirect to the next page or display a success message
    header("Location: transaction.php");
    exit();
}

if (!isset($_SESSION['selectedWarehouse']) || !isset($_SESSION['selectedTransactionType']) || !isset($_SESSION['selectedCustomer'])) {
    // Redirect to the first page if session variables are not set
    header("Location: transaction-new.php");
    exit();
}

// Fetch selected warehouse
$selectedWarehouse = $_SESSION['selectedWarehouse'];

// Fetch product data based on the selected warehouse
$productSql = "SELECT ProductID, Name, Price FROM Product WHERE WarehouseID = ?";
$stmt = $conn->prepare($productSql);
$stmt->bind_param("i", $selectedWarehouse);
$stmt->execute();
$productResult = $stmt->get_result();
$stmt->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Transaction/New-product";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <form action="" method="post">
            <div class="table-responsive" id="productSection">
                <div class="form-group">
                    <h3>Products</h3>
                    <table id="productTable" class="table-container" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                            <td>' . $product["ProductID"] . '</td>
                            <td>' . $product["Name"] . '</td>
                            <td>' . $product["Price"] . '</td>
                            <td><input type="number" name="quantities[]" value="0" min="0"></td>
                            <td><input type="checkbox" name="selectedProducts[]" value="' . $product["ProductID"] . '"></td>
                          </tr>';
                                }
                            } else {
                                echo "<tr><td colspan='4'>No products found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button type="submit">Add</button>
                    <button type="button" class="cancel" onclick="window.location.href='transaction.php'">Cancel</button>
                </div>
            </div>
        </form>
    </main>
</div>

</body>

</html>
