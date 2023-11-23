<?php
$pageTitle = "Suppliers";
include '../contain/header.php';
include("../database/database-connect.php");

// Pagination
$itemsPerPage = 10;

// Fetch total number of suppliers
$sqlTotalSuppliers = "SELECT COUNT(*) FROM Supplier";
$resultTotalSuppliers = $conn->query($sqlTotalSuppliers);
$totalSuppliers = $resultTotalSuppliers->fetch_row()[0];

$totalPages = ceil($totalSuppliers / $itemsPerPage);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of suppliers based on the offset and items per page
$sqlSubsetSuppliers = "SELECT * FROM Supplier LIMIT $itemsPerPage OFFSET $offset";
$resultSubsetSuppliers = $conn->query($sqlSubsetSuppliers);
$subsetSuppliers = $resultSubsetSuppliers->fetch_all();

if (isset($_POST['Cnew'])) {
    header("Location: supplier-new.php");
    exit();
}

if (isset($_POST['deleteSupplier'])) {
    $supplierIDToDelete = mysqli_real_escape_string($conn, $_POST['deleteSupplier']);

    $deleteSql = "DELETE FROM Supplier WHERE SupplierID = '$supplierIDToDelete'";
    $deleteResult = $conn->query($deleteSql);

    if ($deleteResult) {
        header("Location: supplier.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<div class="main-content">

    <?php
    $pathtitle = "Suppliers";
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
                        <th>SupplierID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($subsetSuppliers as $supplier): ?>
                        <tr>
                            <td><?= $supplier[0] ?></td>
                            <td><?= $supplier[1] ?></td>
                            <td><?= $supplier[2] ?></td>
                            <td><?= $supplier[3] ?></td>
                            <td>
                                <form method="GET" action="supplier-edit.php">
                                    <input type="hidden" name="supplierID" value="<?= $supplier[0] ?>">
                                    <button class="edit" type="submit">edit</button>
                                </form>

                                <form method="POST">
                                    <button class="delete" name="deleteSupplier" type="submit"
                                        onclick="return confirm('Are you sure you want to delete this supplier?')">delete
                                    </button>
                                    <input type="hidden" name="deleteSupplier" value="<?= $supplier[0] ?>">
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
