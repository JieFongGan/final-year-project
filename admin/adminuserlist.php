<!DOCTYPE html>
<html>

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
                <form action="adminuserlist.php" method="GET">
                    <input type="text" id="searchInput" name="search" 
                    placeholder="Search by Company Name, User ID, or Status">
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
                    $conn = new PDO(
                        "sqlsrv:server = tcp:allhereserver.database.windows.net,1433; Database = allheredb",
                        "sqladmin",
                        "#Allhere",
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );

                    $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
                    $query = "SELECT CompanyName, Status, UserID FROM [user] WHERE 
                                CompanyName LIKE '%$searchKeyword%' OR
                                Status LIKE '%$searchKeyword%' OR
                                UserID LIKE '%$searchKeyword%'";
                    $result = $conn->query($query);

                    // Display the fetched data
                    $number = 1;
                    foreach ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
