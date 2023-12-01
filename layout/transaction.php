<?php
$pageTitle = "Transactions";
include("../database/database-connect.php");
include '../contain/header.php';

// Pagination
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int) $_GET['itemsPerPage'] : 10;

// Fetch total number of transactions
$sqlTotalTransactions = "SELECT COUNT(*) FROM Transaction";
$resultTotalTransactions = $conn->query($sqlTotalTransactions);

// Check if the query was successful
if ($resultTotalTransactions) {
    $totalTransactions = $resultTotalTransactions->fetch_row()[0];

    // Calculate total pages and handle division by zero
    $totalPages = $totalTransactions > 0 ? ceil($totalTransactions / $itemsPerPage) : 1;
} else {
    // Handle query error
    echo "Error fetching total transactions: " . $conn->error;
    exit();
}

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of transactions based on the offset and items per page
$sqlSubsetTransactions = "SELECT * FROM Transaction ORDER BY TransactionDate DESC LIMIT $itemsPerPage OFFSET $offset";
$resultSubsetTransactions = $conn->query($sqlSubsetTransactions);

// Check if the query was successful
if ($resultSubsetTransactions) {
    $subsetTransactions = $resultSubsetTransactions->fetch_all();
} else {
    // Handle query error
    echo "Error fetching subset of transactions: " . $conn->error;
    exit();
}

if (isset($_POST['Cnew'])) {
    header("Location: transaction-new.php");
    exit();
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
                    <?php if (empty($subsetTransactions)): ?>
                        <tr>
                            <td colspan="7">No data available</td>
                        </tr>
                    <?php else: ?>
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
                                        <button class="detail" type="submit">Detail</button>
                                    </form>
                                    <form method="GET" action="transaction-edit.php">
                                        <input type="hidden" name="transactionID" value="<?= $transaction[0] ?>">
                                        <button class="edit" type="submit">Edit</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <form method="GET" class="pagination-form">
            <label for="itemsPerPage">Items per page:</label>
            <select id="itemsPerPage" name="itemsPerPage" onchange="this.form.submit()">
                <option value="10" <?= $itemsPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $itemsPerPage == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $itemsPerPage == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $itemsPerPage == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </form>

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