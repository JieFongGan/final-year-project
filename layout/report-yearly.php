<?php
$pageTitle = "Yearly Report";
include("../database/database-connect.php");
include '../contain/header.php';

// SQL query for Yearly Sales Report
$sql = "
SELECT
    YEAR(TransactionDate) AS Year,
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
    Year
ORDER BY
    Year;
";

$result = $conn->query($sql);

// Check if the result set is empty
if ($result->num_rows > 0) {
    $yearlyReport = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $yearlyReport = []; // Set an empty array if there are no results
}

$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Yearly Report";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <!-- Display Yearly Report Data -->
        <h2>Yearly Report</h2>

        <?php if (!empty($yearlyReport)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Total Transactions</th>
                        <th>Total Transaction Details</th>
                        <th>Total Items Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($yearlyReport as $row): ?>
                        <tr>
                            <td><?= $row['Year'] ?></td>
                            <td><?= $row['TotalTransactions'] ?></td>
                            <td><?= $row['TotalTransactionDetails'] ?></td>
                            <td><?= $row['TotalItemsSold'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No data available for the yearly report.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>