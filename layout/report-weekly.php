<?php
$pageTitle = "Weekly Report";
include '../contain/header.php';
include '../database/database-connect.php';

// SQL query for Weekly Sales Report
$sql = "
SELECT
    YEAR(TransactionDate) AS Year,
    WEEK(TransactionDate) AS Week,
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
    Year, Week
ORDER BY
    Year, Week;
";

$result = $conn->query($sql);

// Check if the result set is empty
if ($result->num_rows > 0) {
    $weeklyReport = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $weeklyReport = []; // Set an empty array if there are no results
}

$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Weekly Report";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <!-- Display Weekly Report Data -->
        <h2>Weekly Report</h2>

        <?php if (!empty($weeklyReport)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Week</th>
                        <th>Total Transactions</th>
                        <th>Total Transaction Details</th>
                        <th>Total Items Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($weeklyReport as $row): ?>
                        <tr>
                            <td><?= $row['Year'] ?></td>
                            <td><?= $row['Week'] ?></td>
                            <td><?= $row['TotalTransactions'] ?></td>
                            <td><?= $row['TotalTransactionDetails'] ?></td>
                            <td><?= $row['TotalItemsSold'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No data available for the weekly report.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
