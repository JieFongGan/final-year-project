<?php
$pageTitle = "Customers";
include '../contain/header.php';
include("../database/database-connect.php");

// Pagination
$itemsPerPage = 10;

// Fetch total number of customers
$sqlTotalCustomers = "SELECT COUNT(*) FROM Customer";
$resultTotalCustomers = $conn->query($sqlTotalCustomers);
$totalCustomers = $resultTotalCustomers->fetch_row()[0];

$totalPages = ceil($totalCustomers / $itemsPerPage);

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
            <input type="text" id="searchInput" placeholder="Search on current list..." onkeyup="searchTable()">
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