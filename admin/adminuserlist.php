<?php
try {
    $conn = new PDO(
        "sqlsrv:server=tcp:allhereserver.database.windows.net,1433;Database=allheredb",
        "sqladmin",
        "#Allhere",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    // Fetch data from the database with search conditions
    $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
    $query = "SELECT CompanyName, UserID, Status FROM [dbo].[user] WHERE 
                CompanyName LIKE :keyword OR
                UserID LIKE :keyword OR
                Status LIKE :keyword";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':keyword', $searchKeyword, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    echo "<br>SQL Query: " . $query;
    echo "<br>SQLSTATE Error Code: " . $e->getCode();
    die();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Your existing styles go here -->
</head>

<body>
    <div class="sidebar">
        <ul>
            <li><a href="admincomplist.php">Company List</a></li>
            <li><a href="adminuserlist.php">User List</a></li>
            <li><a href="adlogin.php">Log Out</a></li>
        </ul>
    </div>

    <div class="container">
        <div class="content">
            <div class="search-container">
                <form action="adminuserlist.php" method="GET">
                    <input type="text" id="searchInput" name="search" placeholder="Search by Company Name, User ID, or Status" value="<?php echo htmlspecialchars($searchKeyword); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>User ID</th>
                            <th>Status</th>
                            <th>Modify</th>
                        </tr>
                    </thead>

                    <?php
                    // Display the fetched data
                    $number = 1;
                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td>" . $number . "</td>";
                        echo "<td>" . htmlspecialchars($row['CompanyName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                        echo "<td>";
                        echo "<button style='display: inline-block;'><a href='aduseredit.php?userid=" . urlencode(htmlspecialchars($row['UserID'])) . "' style='color: inherit; text-decoration: none;'>Edit</a></button>";
                        echo "&nbsp;";
                        echo "</td>";
                        echo "</tr>";
                        $number++;
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
