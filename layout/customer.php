<?php
$pageTitle = "Customers";
include '../contain/header.php';
include("../database/database-connect.php");

// Pagination
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 10;

// Fetch total number of customers
$sqlTotalCustomers = "SELECT COUNT(*) FROM Customer";
$resultTotalCustomers = $conn->query($sqlTotalCustomers);

// Check if the query was successful
if ($resultTotalCustomers) {
    $totalCustomers = $resultTotalCustomers->fetch_row()[0];

    // Calculate total pages and handle division by zero
    $totalPages = $totalCustomers > 0 ? ceil($totalCustomers / $itemsPerPage) : 1;
} else {
    // Handle query error
    echo "Error fetching total customers: " . $conn->error;
    exit();
}

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of customers based on the offset and items per page using prepared statement
$sqlSubsetCustomers = "SELECT * FROM Customer LIMIT ? OFFSET ?";
$stmtSubsetCustomers = $conn->prepare($sqlSubsetCustomers);
$stmtSubsetCustomers->bind_param("ii", $itemsPerPage, $offset);
$stmtSubsetCustomers->execute();
$resultSubsetCustomers = $stmtSubsetCustomers->get_result();
$subsetCustomers = $resultSubsetCustomers->fetch_all(MYSQLI_ASSOC);
$stmtSubsetCustomers->close();

if (isset($_POST['Cnew'])) {
    header("Location: customer-new.php");
    exit();
}

if (isset($_POST['deleteCustomer'])) {
    // Using prepared statement to prevent SQL injection
    $customerIDToDelete = $_POST['deleteCustomer'];
    $deleteSql = "DELETE FROM Customer WHERE CustomerID = ?";
    $stmtDeleteCustomer = $conn->prepare($deleteSql);
    $stmtDeleteCustomer->bind_param("i", $customerIDToDelete);
    $stmtDeleteCustomer->execute();

    if ($stmtDeleteCustomer->affected_rows > 0) {
        header("Location: customer.php");
        exit();
    } else {
        echo "Error: " . $stmtDeleteCustomer->error;
    }

    $stmtDeleteCustomer->close();
}

$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Customers";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="button-and-search">
            <form method="POST">
                <button name="Cnew">Create New</button>
            </form>
            <input type="text" id="searchInput" placeholder="Search on the current list..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table-container" style="width:100%">
                <thead>
                    <tr>
                        <th>CustomerID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($subsetCustomers as $customer): ?>
                        <tr>
                            <td><?= $customer['CustomerID'] ?></td>
                            <td><?= $customer['Name'] ?></td>
                            <td><?= $customer['Contact'] ?></td>
                            <td><?= $customer['Email'] ?></td>
                            <td><?= $customer['Address'] ?></td>
                            <td><?= $customer['Remark'] ?></td>
                            <td>
                                <form method="GET" action="customer-edit.php">
                                    <input type="hidden" name="customerID" value="<?= $customer['CustomerID'] ?>">
                                    <button class="edit" type="submit">edit</button>
                                </form>

                                <form method="POST">
                                    <button class="delete" name="deleteCustomer" type="submit"
                                        onclick="return confirm('Are you sure you want to delete this customer?')">delete
                                    </button>
                                    <input type="hidden" name="deleteCustomer" value="<?= $customer['CustomerID'] ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                <a href="?page=<?= $page ?>&itemsPerPage=<?= $itemsPerPage ?>" <?= $page == $current_page ? 'class="active"' : '' ?>>
                    <?= $page ?>
                </a>
            <?php endfor; ?>
        </div>
    </main>

</div>
</body>

</html>
