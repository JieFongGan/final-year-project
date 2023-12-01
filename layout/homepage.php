<?php
$pageTitle = "Homepage";
include("../database/database-connect.php");
include '../contain/header.php';


// Fetch relevant data for the dashboard
$totalInventoryQuery = $conn->query("SELECT COUNT(*) AS totalProducts FROM Product");
$totalInventoryResult = $totalInventoryQuery->fetch(PDO::FETCH_ASSOC);

$pendingTransactionsQuery = $conn->query("SELECT COUNT(*) AS totalPendingTransactions FROM Transaction 
                                            WHERE DeliveryStatus = 'Pending'");
$pendingTransactionsResult = $pendingTransactionsQuery->fetch(PDO::FETCH_ASSOC);

$processedTransactionsQuery = $conn->query("SELECT COUNT(*) AS totalProcessedTransactions FROM Transaction
                                            WHERE DeliveryStatus IN ('Processing', 'Shipped')");
$processedTransactionsResult = $processedTransactionsQuery->fetch(PDO::FETCH_ASSOC);

$userCountQuery = $conn->query("SELECT COUNT(*) AS userCount FROM User");
$userCountResult = $userCountQuery->fetch(PDO::FETCH_ASSOC);

$warehouseCountQuery = $conn->query("SELECT COUNT(*) AS warehouseCount FROM Warehouse");
$warehouseCountResult = $warehouseCountQuery->fetch(PDO::FETCH_ASSOC);

$categoryCountQuery = $conn->query("SELECT COUNT(*) AS categoryCount FROM Category");
$categoryCountResult = $categoryCountQuery->fetch(PDO::FETCH_ASSOC);

$latestTransactionsQuery = $conn->query("SELECT * FROM Transaction ORDER BY TransactionDate DESC LIMIT 5");
$latestTransactionsResult = $latestTransactionsQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="main-content">

    <?php
    $pathtitle = "Homepage";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <br>

        <h2 class="dash-title">Overview</h2>

        <div class="dash-cards">
            <div class="card-single">
                <div class="card-body">
                    <span class="ti-briefcase"></span>
                    <div>
                        <h5>Total Inventory</h5>
                        <h4>
                            <?php echo $totalInventoryResult['totalProducts']; ?>
                        </h4>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="../layout/inventory.php">View all</a>
                </div>
            </div>

            <div class="card-single">
                <div class="card-body">
                    <span class="ti-reload"></span>
                    <div>
                        <h5>Pending</h5>
                        <h4>
                            <?php echo $pendingTransactionsResult['totalPendingTransactions']; ?> transactions
                        </h4>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="../layout/transaction.php">View all</a>
                </div>
            </div>

            <div class="card-single">
                <div class="card-body">
                    <span class="ti-check-box"></span>
                    <div>
                        <h5>Processing/Shipped</h5>
                        <h4>
                            <?php echo $processedTransactionsResult['totalProcessedTransactions']; ?> transactions
                        </h4>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="../layout/transaction.php">View all</a>
                </div>
            </div>
        </div>

        <section class="recent">
            <div class="activity-grid">
                <div class="activity-card">
                    <h3>Recent activity</h3>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Last Updated Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($latestTransactionsQuery)) {
                                    $statusClass = ($row['DeliveryStatus'] == 'Pending' || $row['DeliveryStatus'] == 'Processing') ? 'warning' : 'success';
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['TransactionType']; ?>
                                        </td>
                                        <td>
                                            <?php echo date('d M, Y', strtotime($row['TransactionDate'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo $row['DeliveryStatus']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="summary">
                    <div class="summary-card">
                        <div class="summary-single">
                            <span class="ti-id-badge"></span>
                            <div>
                                <h5>
                                    <?php echo $userCountResult['userCount']; ?>
                                </h5>
                                <small>Number of user</small>
                            </div>
                        </div>
                        <div class="summary-single">
                            <span class="ti-calendar"></span>
                            <div>
                                <h5>
                                    <?php echo $warehouseCountResult['warehouseCount']; ?>
                                </h5>
                                <small>Number of warehouse</small>
                            </div>
                        </div>
                        <div class="summary-single">
                            <span class="ti-face-smile"></span>
                            <div>
                                <h5>
                                    <?php echo $categoryCountResult['categoryCount']; ?>
                                </h5>
                                <small>Category create</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

</body>

</html>