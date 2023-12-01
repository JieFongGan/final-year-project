<?php
$pageTitle = "Warehouse";
include("../database/database-connect.php");
include '../contain/header.php';

// Pagination
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int) $_GET['itemsPerPage'] : 10;

// Fetch total number of warehouses
$sqlTotalWarehouses = "SELECT COUNT(*) FROM Warehouse";
$resultTotalWarehouses = $conn->query($sqlTotalWarehouses);

// Check if the query was successful
if ($resultTotalWarehouses) {
    $totalWarehouses = $resultTotalWarehouses->fetch_row()[0];

    // Calculate total pages and handle division by zero
    $totalPages = $totalWarehouses > 0 ? ceil($totalWarehouses / $itemsPerPage) : 1;
} else {
    // Handle query error
    echo "Error fetching total warehouses: " . $conn->error;
    exit();
}

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of warehouses based on the offset and items per page
$sqlSubsetWarehouses = "SELECT * FROM Warehouse LIMIT $itemsPerPage OFFSET $offset";
$resultSubsetWarehouses = $conn->query($sqlSubsetWarehouses);

// Check if the query was successful
if ($resultSubsetWarehouses) {
    $subsetWarehouses = $resultSubsetWarehouses->fetch_all();
} else {
    // Handle query error
    echo "Error fetching subset of warehouses: " . $conn->error;
    exit();
}

if (isset($_POST['Cnew'])) {
    header("Location: warehouse-new.php");
    exit();
}

if (isset($_POST['deleteWarehouse'])) {
    $warehouseIDToDelete = mysqli_real_escape_string($conn, $_POST['deleteWarehouse']);

    $deleteSql = "DELETE FROM Warehouse WHERE WarehouseID = '$warehouseIDToDelete'";
    $deleteResult = $conn->query($deleteSql);

    if ($deleteResult) {
        header("Location: warehouse.php");
        exit();
    } else {
        echo "Error deleting warehouse: " . $conn->error;
    }
}

$conn->close();
?>

<div class="main-content">

    <?php
    $pathtitle = "Warehouse";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="button-and-search">
            <form method="POST">
            <?php if ($userrole !== 'Manager' && $userrole !== 'User'): ?>
                <button name="Cnew">Create New</button>
            <?php endif; ?>
            </form>
            <input type="text" id="searchInput" placeholder="Search on the current list..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table-container" style="width:100%">
                <thead>
                    <tr>
                        <th>WarehouseID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <?php if ($userrole !== 'User'): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($subsetWarehouses)): ?>
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($subsetWarehouses as $warehouse): ?>
                            <tr>
                                <td>
                                    <?= $warehouse[0] ?>
                                </td>
                                <td>
                                    <?= $warehouse[1] ?>
                                </td>
                                <td>
                                    <?= $warehouse[2] ?>
                                </td>
                                <td>
                                    <?= $warehouse[3] ?>
                                </td>
                                <td>
                                    <?= $warehouse[4] ?>
                                </td>
                                <?php if ($userrole !== 'User'): ?>
                                <td>
                                    <form method="GET" action="warehouse-edit.php">
                                        <input type="hidden" name="warehouseID" value="<?= $warehouse[0] ?>">
                                        <button class="edit" type="submit">edit</button>
                                    </form>
                                    <?php if ($userrole !== 'Manager'): ?>

                                    <form method="POST">
                                        <button class="delete" name="deleteWarehouse" type="submit"
                                            onclick="return confirm('Are you sure you want to delete this warehouse?')">delete
                                        </button>
                                        <input type="hidden" name="deleteWarehouse" value="<?= $warehouse[0] ?>">
                                    </form>
                                    <?php endif; ?> 
                                </td>
                                <?php endif; ?>   
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