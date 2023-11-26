<?php
$pageTitle = "Settings/Users";
include '../contain/header.php';
include("../database/database-connect.php");

// Pagination
$itemsPerPage = 10;

// Fetch total number of users
$sqlTotalUsers = "SELECT COUNT(*) FROM User";
$resultTotalUsers = $conn->query($sqlTotalUsers);
$totalUsers = $resultTotalUsers->fetch_row()[0];

$totalPages = ceil($totalUsers / $itemsPerPage);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($totalPages, $current_page));

// Calculate the offset
$offset = ($current_page - 1) * $itemsPerPage;

// Fetch a subset of users based on the offset and items per page using prepared statement
$sqlSubsetUsers = "SELECT * FROM User LIMIT ? OFFSET ?";
$stmtSubsetUsers = $conn->prepare($sqlSubsetUsers);
$stmtSubsetUsers->bind_param("ii", $itemsPerPage, $offset);
$stmtSubsetUsers->execute();
$resultSubsetUsers = $stmtSubsetUsers->get_result();
$subsetUsers = $resultSubsetUsers->fetch_all(MYSQLI_ASSOC);
$stmtSubsetUsers->close();

if (isset($_POST['createUser'])) {
    header("Location: user-new.php");
    exit();
}

if (isset($_POST['deleteUser'])) {
    // Using prepared statement to prevent SQL injection
    $userIDToDelete = $_POST['deleteUser'];
    $deleteSql = "DELETE FROM User WHERE UserID = ?";
    $stmtDeleteUser = $conn->prepare($deleteSql);
    $stmtDeleteUser->bind_param("i", $userIDToDelete);
    $stmtDeleteUser->execute();

    if ($stmtDeleteUser->affected_rows > 0) {
        header("Location: user.php");
        exit();
    } else {
        echo "Error: " . $stmtDeleteUser->error;
    }

    $stmtDeleteUser->close();
}

$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Settings/Users";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="button-and-search">
            <form method="POST">
                <button name="createUser">Invite</button>
            </form>
            <input type="text" id="searchInput" placeholder="Search on current list..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table-container" style="width:100%">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>UserRole</th>
                        <th>LastLoginDate</th>
                        <th>UserStatus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($subsetUsers as $user): ?>
                        <tr>
                            <td><?= $user['UserID'] ?></td>
                            <td><?= $user['Username'] ?></td>
                            <td><?= $user['Email'] ?></td>
                            <td><?= $user['Phone'] ?></td>
                            <td><?= $user['FirstName'] ?></td>
                            <td><?= $user['LastName'] ?></td>
                            <td><?= $user['UserRole'] ?></td>
                            <td><?= $user['LastLoginDate'] ?></td>
                            <td><?= $user['UserStatus'] ?></td>
                            <td>
                                <form method="GET" action="settings-user-edit.php">
                                    <input type="hidden" name="userID" value="<?= $user['UserID'] ?>">
                                    <button class="edit" type="submit">edit</button>
                                </form>

                                <form method="POST">
                                    <button class="delete" name="deleteUser" type="submit"
                                        onclick="return confirm('Are you sure you want to delete this user?')">delete
                                    </button>
                                    <input type="hidden" name="deleteUser" value="<?= $user['UserID'] ?>">
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
