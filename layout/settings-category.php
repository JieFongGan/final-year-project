<?php
$pageTitle = "Settings/category";
include '../contain/header.php';
include("../database/database-connect.php");

$sql = "SELECT * FROM Category";
$result = $conn->query($sql);

$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = array(
            'CategoryID' => $row['CategoryID'],
            'Name' => $row['Name'],
            'Description' => $row['Description']
        );
    }
}

/// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['deleteCategory'])) {
        $categoryIDToDelete = $_POST['deleteCategory'];
        $deleteSql = "DELETE FROM Category WHERE CategoryID = '$categoryIDToDelete'";
        $deleteResult = $conn->query($deleteSql);

        if ($deleteResult) {
            // Refresh the categories list after successful deletion
            header("Location: settings-category.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['newCategory'])) {
        $newCategory = mysqli_real_escape_string($conn, $_POST["newCategory"]);
        $description = mysqli_real_escape_string($conn, $_POST["description"]);

        // Check if the category already exists
        if (!in_array($newCategory, array_column($categories, 'Name'))) {
            // Insert the new category into the database
            $insertSql = "INSERT INTO Category (Name, Description) VALUES ('$newCategory', '$description')";
            $insertResult = $conn->query($insertSql);

            if ($insertResult) {
                // Refresh the categories list after successful insertion
                header("Location: settings-category.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Category already exists!";
        }
    }
}

// Close the database connection
$conn->close();
?>

<div class="main-content">
    <?php
    $pathtitle = "Settings/category";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <div class="form-container">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="form-group">
                    <label for="newCategory">New Category Name :</label>
                    <input type="text" id="newCategory" name="newCategory" placeholder="Enter a new category" required>
                </div>
                <div class="form-group">
                    <label for="description">New Category Description:</label>
                    <textarea id="description" name="description" placeholder="Please enter the description"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Add Category</button>
                </div>
            </form>

            <h2>All Categories</h2>
            <ul class="category-list">
                <?php foreach ($categories as $category): ?>
                    <li>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="category-details">
                                <strong class="category-name">
                                    <?= $category['Name'] ?>
                                </strong>
                                <?php if (!empty($category['Description'])): ?>
                                    <p class="category-description">
                                        <?= $category['Description'] ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="deleteCategory" value="<?= $category['CategoryID'] ?>">
                            <button type="submit" class="delete-button"
                                onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    </main>
</div>
</body>

</html>