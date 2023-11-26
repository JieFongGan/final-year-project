<?php
$pageTitle = "Warehouse";
include '../contain/header.php';
include("../database/database-connect.php");

// Pagination
$itemsPerPage = 10;

// Fetch total number of warehouses
$sqlTotalWarehouses = "SELECT COUNT(*) FROM Warehouse";
$resultTotalWarehouses = $conn->query($sqlTotalWarehouses);
$totalWarehouses = $resultTotalWarehouses->fetch_row()[0];

$totalPages = ceil($totalWarehouses / $itemsPerPage);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of warehouses based on the offset and items per page
$sqlSubsetWarehouses = "SELECT * FROM Warehouse LIMIT $itemsPerPage OFFSET $offset";
$resultSubsetWarehouses = $conn->query($sqlSubsetWarehouses);
$subsetWarehouses = $resultSubsetWarehouses->fetch_all();

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
        echo "Error: " . $conn->error;
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
                <button name="Cnew">Create New</button>
            </form>
            <input type="text" id="searchInput" placeholder="Search on current list..." onkeyup="searchTable()">
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($subsetWarehouses as $warehouse): ?>
                        <tr>
                            <td><?= $warehouse[0] ?></td>
                            <td><?= $warehouse[1] ?></td>
                            <td><?= $warehouse[2] ?></td>
                            <td><?= $warehouse[3] ?></td>
                            <td><?= $warehouse[4] ?></td>
                            <td>
                                <form method="GET" action="warehouse-edit.php">
                                    <input type="hidden" name="warehouseID" value="<?= $warehouse[0] ?>">
                                    <button class="edit" type="submit">edit</button>
                                </form>

                                <form method="POST">
                                    <button class="delete" name="deleteWarehouse" type="submit"
                                        onclick="return confirm('Are you sure you want to delete this warehouse?')">delete
                                    </button>
                                    <input type="hidden" name="deleteWarehouse" value="<?= $warehouse[0] ?>">
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
