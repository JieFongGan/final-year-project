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
        <!-- Sidebar content -->
    </div>

    <div class="container">
        <div class="content">
            <div class="search-container">
                <!-- Search form -->
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
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
