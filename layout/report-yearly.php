<?php
$pageTitle = "Yearly Report";
include '../contain/header.php';
include '../database/database-connect.php';

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
$yearlyReport = $result->fetch_all(MYSQLI_ASSOC);
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
    </main>
</div>
</body>
</html>
