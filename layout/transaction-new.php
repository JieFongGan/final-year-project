<?php
session_start(); // Start the session

$pageTitle = "Transaction/New";
include '../contain/header.php';
include("../database/database-connect.php");

// Fetch warehouse data
$warehouseSql = "SELECT WarehouseID, Name FROM Warehouse";
$warehouseResult = $conn->query($warehouseSql);

// Fetch customer data
$customerSql = "SELECT CustomerID, Name, Remark FROM Customer";
$customerResult = $conn->query($customerSql);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Store data in session
    $_SESSION['selectedWarehouse'] = $_POST['productWarehouse'];
    $_SESSION['selectedTransactionType'] = $_POST['transactionType'];
    $_SESSION['selectedCustomer'] = $_POST['selectedCustomer'];

    // Redirect to the second page
    header("Location: transaction-newProduct.php");
    exit();
}
?>

<div class="main-content">
    <?php
    $pathtitle = "Transaction/New";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="productWarehouse">To/From Warehouse:</label>
                    <select id="productWarehouse" name="productWarehouse">
                        <option value="" disabled selected>Please select a warehouse</option>
                        <?php
                        if ($warehouseResult && $warehouseResult->num_rows > 0) {
                            while ($warehouse = $warehouseResult->fetch_assoc()) {
                                echo '<option value="' . $warehouse["WarehouseID"] . '">' . $warehouse["Name"] . '</option>';
                            }
                        } else {
                            echo "0 results";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="transactionType">Transaction Type:</label>
                    <select id="transactionType" name="transactionType">
                        <option value="" disabled selected>Please select a transaction type</option>
                        <option value="Sales">Sales</option>
                        <option value="Purchase">Purchase</option>
                    </select>
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
                                            echo '<tr>
                                                <td>' . $customer["CustomerID"] . '</td>
                                                <td>' . $customer["Name"] . '</td>
                                                <td>' . $customer["Remark"] . '</td>
                                                <td><input type="radio" name="selectedCustomer" value="' . $customer["CustomerID"] . '"></td>
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

                    <div class="form-group">
                        <button type="submit">Next</button>
                        <button type="button" class="cancel"
                            onclick="window.location.href='transaction.php'">Cancel</button>
                    </div>
            </form>
        </div>
    </main>
</div>

</body>

</html>