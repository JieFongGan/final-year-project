<?php
include("database/database-connect.php");

// Fetch relevant data for the dashboard
$totalInventoryQuery = mysqli_query($conn, "SELECT COUNT(*) AS totalProducts FROM Product");
$totalInventoryResult = mysqli_fetch_assoc($totalInventoryQuery);

$pendingTransactionsQuery = mysqli_query($conn, "SELECT COUNT(*) AS totalPendingTransactions FROM Transaction 
                                                WHERE DeliveryStatus = 'Pending'");
$pendingTransactionsResult = mysqli_fetch_assoc($pendingTransactionsQuery);

$processedTransactionsQuery = mysqli_query($conn, "SELECT COUNT(*) AS totalProcessedTransactions FROM Transaction                                                  WHERE DeliveryStatus IN ('Processing', 'Shipped')");
$processedTransactionsResult = mysqli_fetch_assoc($processedTransactionsQuery);

$userCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS userCount FROM User");
$userCountResult = mysqli_fetch_assoc($userCountQuery);

$warehouseCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS warehouseCount FROM Warehouse");
$warehouseCountResult = mysqli_fetch_assoc($warehouseCountQuery);

$categoryCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS categoryCount FROM Category");
$categoryCountResult = mysqli_fetch_assoc($categoryCountQuery);

$latestTransactionsQuery = mysqli_query($conn, "SELECT * FROM Transaction ORDER BY TransactionDate DESC LIMIT 5");

// userid
$predefinedUserID = 4;
$_SESSION['user_id'] = $predefinedUserID;
$userID = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <script src="script/script.js"></script>
</head>

<body>

    <input type="checkbox" id="sidebar-toggle">
    <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="brand">
                <span class="ti-unlink"></span>
                <span>Allhere</span>
            </h3>
            <label for="sidebar-toggle" class="ti-menu-alt"></label>
        </div>

        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="index.php">
                        <span class="ti-home"></span>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="layout/inventory.php">
                        <span class="ti-package"></span>
                        <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="layout/transaction.php">
                        <span class="ti-shopping-cart"></span>
                        <span>transaction</span>
                    </a>
                </li>
                <li>
                    <a href="layout/warehouse.php">
                        <span class="ti-truck"></span>
                        <span>Warehouse</span>
                    </a>
                </li>
                <li>
                    <a href="layout/customer.php">
                        <span class="ti-agenda"></span>
                        <span>Customer</span>
                    </a>
                </li>
                <li>
                    <a href="layout/report.php">
                        <span class="ti-pie-chart"></span>
                        <span>Report</span>
                    </a>
                </li>
                <li>
                    <a href="layout/settings.php">
                        <span class="ti-settings"></span>
                        <span>Setting</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">

        <header>
            <div class="directory-tag">
                <p>Home</p>
            </div>

            <div class="social-icons">
                <div class="social-icon">
                    <img src="img/user-profile.png" alt="Social Icon" id="social-icon">
                    <ul class="dropdown">
                        <li><a href="layout/profile.php?userID=<?php echo $userID; ?>">Profile</a></li>
                        <li><a href="layout/chpassword.php?userID=<?php echo $userID; ?>">Change Password</a></li>
                        <li><a href="#">Log out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main>

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
                        <a href="layout/inventory.php">View all</a>
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
                        <a href="layout/transaction.php">View all</a>
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
                        <a href="layout/transaction.php">View all</a>
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