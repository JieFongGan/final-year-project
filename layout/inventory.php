<?php
$pageTitle = "Inventory";
include '../database/database-connect.php';
include '../contain/header.php';

$sql = "SELECT Name, ProductID, WarehouseID, Description, Price, Quantity FROM Product";
$result = $conn->query($sql);

$products = array();

if ($result->rowCount() > 0) {
    // Fetch data from the result set
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $products[] = array(
            $row["Name"],
            $row["ProductID"],
            $row["WarehouseID"],
            $row["Description"],
            $row["Price"],
            $row["Quantity"]
        );
    }
}

// Pagination
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 10;
$totalItems = count($products);
$totalPages = ceil($totalItems / $itemsPerPage);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Get a subset of products based on the offset and items per page
$subsetProducts = array_slice($products, $offset, $itemsPerPage);

if (isset($_POST['Cnew'])) {
    header("Location: inventory-new.php");
    exit();
}

if (isset($_POST['deleteProduct'])) {
    $productIDToDelete = $_POST['deleteProduct'];

    $deleteSql = "DELETE FROM Product WHERE ProductID = :productID";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bindParam(':productID', $productIDToDelete, PDO::PARAM_STR);

    if ($deleteStmt->execute()) {
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error: " . $deleteStmt->errorInfo()[2];
    }
}

?>

<div class="main-content">

    <?php
    $pathtitle = "Inventory";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="button-and-search">
            <form method="POST">
            <?php if ($userrole !== 'User'): ?>
                <button name="Cnew">Create New</button>
            <?php endif; ?>
            </form>
            <input type="text" id="searchInput" placeholder="Search on current list..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table-container" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>ProductID</th>
                        <th>WarehouseID</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($subsetProducts)): ?>
                        <tr>
                            <td colspan="7">No data available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($subsetProducts as $product): ?>
                            <tr>
                                <td>
                                    <?= $product[0] ?>
                                </td>
                                <td>
                                    <?= $product[1] ?>
                                </td>
                                <td>
                                    <?= $product[2] ?>
                                </td>
                                <td>
                                    <?= $product[3] ?>
                                </td>
                                <td>
                                    <?= $product[4] ?>
                                </td>
                                <td>
                                    <?= $product[5] ?>
                                </td>
                                <td>
                                    <form method="GET" action="inventory-edit.php">
                                        <input type="hidden" name="productID" value="<?= $product[1] ?>">
                                        <button class="edit" type="submit">edit</button>
                                    </form>

                                    <?php if ($userrole !== 'User'): ?>
                                    <form method="POST">
                                        <button class="delete" name="deleteProduct" type="submit"
                                            onclick="return confirm('Are you sure you want to delete this product?')">delete
                                        </button>
                                        <input type="hidden" name="deleteProduct" value="<?= $product[1] ?>">
                                    </form>
                                    <?php endif; ?>
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