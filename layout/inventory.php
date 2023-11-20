<?php
$pageTitle = "Inventory";
include '../contain/header.php';

// Sample data array
$employees = array(
    array("John Doe", "Developer", "New York", 30, "2020-01-15", "$90,000"),
    array("Jane Smith", "Designer", "San Francisco", 28, "2018-05-20", "$80,000"),
    array("Bob Johnson", "Manager", "Chicago", 35, "2019-08-10", "$100,000"),
    array("Alice Williams", "Analyst", "Los Angeles", 26, "2021-02-28", "$75,000"),
    array("Charlie Brown", "Tester", "Seattle", 32, "2017-11-05", "$85,000"),
    array("Eva Martinez", "Developer", "Miami", 29, "2019-04-12", "$95,000"),
    array("David Clark", "Designer", "Austin", 31, "2016-09-22", "$82,000"),
    array("Grace Lee", "Manager", "Houston", 34, "2020-11-18", "$105,000"),
    array("Henry Turner", "Analyst", "Denver", 27, "2018-07-03", "$78,000"),
    array("Sophia Baker", "Tester", "Portland", 33, "2017-03-14", "$88,000")
);

// Pagination
$itemsPerPage = 5;
$totalItems = count($employees);
$totalPages = ceil($totalItems / $itemsPerPage);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Get a subset of employees based on the offset and items per page
$subsetEmployees = array_slice($employees, $offset, $itemsPerPage);

// Check if the form is submitted
if (isset($_POST['Edit'])) {
    // Redirect to the desired page
    header("Location: inventory-edit.php");
    exit();
}

if (isset($_POST['Cnew'])) {
    // Redirect to the desired page
    header("Location: inventory-new.php");
    exit();
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
                <button name="Cnew">Create New</button>
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
                    <?php foreach ($subsetEmployees as $employee): ?>
                        <tr>
                            <td>
                                <?= $employee[0] ?>
                            </td>
                            <td>
                                <?= $employee[1] ?>
                            </td>
                            <td>
                                <?= $employee[2] ?>
                            </td>
                            <td>
                                <?= $employee[3] ?>
                            </td>
                            <td>
                                <?= $employee[4] ?>
                            </td>
                            <td>
                                <?= $employee[5] ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <button class="edit" name="Edit" type="submit">edit</button>
                                    <button class="delete">delete</button>
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