<?php
$pageTitle = "Transactions";
include '../contain/header.php';
include("../database/database-connect.php");

// Pagination
$itemsPerPage = 10;

// Fetch total number of transactions
$sqlTotalTransactions = "SELECT COUNT(*) FROM Transaction";
$resultTotalTransactions = $conn->query($sqlTotalTransactions);
$totalTransactions = $resultTotalTransactions->fetch_row()[0];

$totalPages = ceil($totalTransactions / $itemsPerPage);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of transactions based on the offset and items per page
$sqlSubsetTransactions = "SELECT * FROM Transaction LIMIT $itemsPerPage OFFSET $offset";
$resultSubsetTransactions = $conn->query($sqlSubsetTransactions);
$subsetTransactions = $resultSubsetTransactions->fetch_all();

if (isset($_POST['Cnew'])) {
    header("Location: transaction-new.php");
    exit();
}

if (isset($_POST['deleteTransaction'])) {
    $transactionIDToDelete = mysqli_real_escape_string($conn, $_POST['deleteTransaction']);

    // Delete transaction details first
    $deleteDetailsSql = "DELETE FROM TransactionDetail WHERE TransactionID = '$transactionIDToDelete'";
    $deleteDetailsResult = $conn->query($deleteDetailsSql);

    // Check if details deletion was successful before deleting the transaction
    if ($deleteDetailsResult) {
        $deleteTransactionSql = "DELETE FROM Transaction WHERE TransactionID = '$transactionIDToDelete'";
        $deleteTransactionResult = $conn->query($deleteTransactionSql);

        if ($deleteTransactionResult) {
            header("Location: transaction.php");
            exit();
        } else {
            echo "Error deleting transaction: " . $conn->error;
        }
    } else {
        echo "Error deleting transaction details: " . $conn->error;
    }
}

$conn->close();
?>

<div class="main-content">

    <?php
    $pathtitle = "Transactions";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="button-and-search">
            <form method="POST">
                <button name="Cnew">Create New</button>
            </form>
            <input type="text" id="searchInput" placeholder="Search on current list..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table-container" style="width:100%">
                <thead>
                    <tr>
                        <th>TransactionID</th>
                        <th>WarehouseID</th>
                        <th>CustomerID</th>
                        <th>TransactionType</th>
                        <th>TransactionDate</th>
                        <th>DeliveryStatus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($subsetTransactions as $transaction): ?>
                        <tr>
                            <td>
                                <?= $transaction[0] ?>
                            </td>
                            <td>
                                <?= $transaction[1] ?>
                            </td>
                            <td>
                                <?= $transaction[2] ?>
                            </td>
                            <td>
                                <?= $transaction[3] ?>
                            </td>
                            <td>
                                <?= $transaction[4] ?>
                            </td>
                            <td>
                                <?= $transaction[5] ?>
                            </td>
                            <td>
                                <form method="GET" action="transaction-detailProduct.php">
                                    <input type="hidden" name="transactionID" value="<?= $transaction[0] ?>">
                                    <button class="edit" type="submit">Detail</button>
                                </form>

                                <form method="GET" action="transaction-edit.php">
                                    <input type="hidden" name="transactionID" value="<?= $transaction[0] ?>">
                                    <button class="edit" type="submit">Edit</button>
                                </form>

                                <form method="POST">
                                    <button class="delete" name="deleteTransaction" type="submit"
                                        onclick="return confirm('Are you sure you want to delete this transaction?')">delete
                                    </button>
                                    <input type="hidden" name="deleteTransaction" value="<?= $transaction[0] ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="pagination" class="pagination">
            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                <a href="?page=<?= $page ?>" <?= $page == $current_page ? 'class="active"' : '' ?>>
                    <?= $page ?>
                </a>
            <?php endfor; ?>
        </div>
    </main>

</div>
</body>

</html>