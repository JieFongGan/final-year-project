<!DOCTYPE html>
<html>

<head>
    <title>Company List</title>
    <style>
        /* CSS beautification */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
        }

        .container {
            width: 800px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
        }

        .sidebar {
            width: 200px;
            padding: 20px;
            background-color: #f2f2f2;
            transition: width 0.3s ease-in-out;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        /* Side menu */
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: #333;
        }

        .sidebar a:hover {
            color: #666;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Button styling */
        button {
            padding: 10px 20px;
            background-color: blue;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: blue;
        }

        /* Animation */
        .sidebar:hover {
            width: 250px;
        }

        /* Additional CSS */
        .container {
            animation: slide-in 0.5s ease-in-out;
        }

        @keyframes slide-in {
            0% {
                opacity: 0;
                transform: translateX(-100px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .content {
            animation: fade-in 0.5s ease-in-out;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <ul>
            <li><a href="#">Company List</a></li>
            <li><a href="adlogin.php">Log Out</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="content">
            <button>Create</button>
            <br><br>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Plan Type</th>
                        <th>Auth Code</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <?php
                // Connect to the database
                $servername = "localhost";
                $dbusername = "root";
                $dbpassword = "";
                $dbname = "adminallhere";

                $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch data from the database
                $query = "SELECT CompanyName, Status, PlanType, AuthCode FROM company";
                $result = mysqli_query($conn, $query);

                // Display the fetched data
                while ($row = mysqli_fetch_assoc($result)) {

                    $number = 1;

                    // Pagination
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    $offset = ($page - 1) * $recordsPerPage;

                    // Fetch data from the database with pagination
                    $query = "SELECT CompanyName, Status, PlanType, AuthCode FROM company LIMIT $offset, $recordsPerPage";
                    $result = mysqli_query($conn, $query);

                    // Display the fetched data
                    $number = ($page - 1) * $recordsPerPage + 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $number . "</td>";
                        echo "<td>" . $row['CompanyName'] . "</td>";
                        echo "<td>" . $row['Status'] . "</td>";
                        echo "<td>" . $row['PlanType'] . "</td>";
                        echo "<td>" . $row['AuthCode'] . "</td>";
                        echo "<td>";
                        echo "<button><a href='adedit.php?authcode=" . $row['AuthCode'] . "' style='color: inherit; text-decoration: none;'>Edit</a></button>";
                        echo "&nbsp;";
                        echo "<button>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                        $number++;
                    }

                    // Delete button functionality
                    if (isset($_POST['delete'])) {
                        $authCode = $_POST['authCode'];

                        // Delete the record from the database
                        $deleteQuery = "DELETE FROM company WHERE AuthCode = '$authCode'";
                        $deleteResult = mysqli_query($conn, $deleteQuery);

                        if ($deleteResult) {
                            echo "Record deleted successfully.";
                        } else {
                            echo "Error deleting record: " . mysqli_error($conn);
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>

</table>
</div>
</div>
</body>

</html>