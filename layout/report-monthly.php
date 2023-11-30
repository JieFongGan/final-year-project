<?php
$pageTitle = "Monthly Report";
include("../database/database-connect.php");
include '../contain/header.php';

// SQL query for Monthly Sales Report
$sql = "
SELECT
    DATE_FORMAT(TransactionDate, '%Y-%m') AS Month,
    COUNT(DISTINCT t.TransactionID) AS TotalTransactions,
    COUNT(td.TransactionDetailID) AS TotalTransactionDetails,
    SUM(td.Quantity) AS TotalItemsSold
FROM
    Transaction t
JOIN
    TransactionDetail td ON t.TransactionID = td.TransactionID
WHERE
    t.TransactionType = 'Sales'   -- Filter for sales transactions
GROUP BY
    Month
ORDER BY
    Month;
";

$result = $conn->query($sql);

// Check if the result set is empty
if ($result->num_rows > 0) {
    $monthlyReport = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $monthlyReport = []; // Set an empty array if there are no results
}

$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Monthly Report";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <!-- Display Monthly Report Data -->
        <h2>Monthly Report</h2>

        <?php if (!empty($monthlyReport)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Transactions</th>
                        <th>Total Transaction Details</th>
                        <th>Total Items Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyReport as $row): ?>
                        <tr>
                            <td><?= $row['Month'] ?></td>
                            <td><?= $row['TotalTransactions'] ?></td>
                            <td><?= $row['TotalTransactionDetails'] ?></td>
                            <td><?= $row['TotalItemsSold'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No data available for the monthly report.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
