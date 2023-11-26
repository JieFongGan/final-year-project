<?php
$pageTitle = "Inventory/New";
include '../contain/header.php';
include("../database/database-connect.php");

// Fetch all categories from the database
$sql = "SELECT * FROM Category";
$result = $conn->query($sql);

$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['Name'];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process form data
    $productName = mysqli_real_escape_string($conn, $_POST["productName"]);
    $categoryID = mysqli_real_escape_string($conn, $_POST["category"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);

    // Additional validation checks
    $errors = [];

    if (empty($productName)) {
        $errors[] = "Product name is required.";
    }

    if (empty($categoryID)) {
        $errors[] = "Category is required.";
    }

    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a valid positive number.";
    }

    // If there are validation errors, display them and stop further processing
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    } else {
        // Perform database insertion
        $sql = "INSERT INTO Product (Name, CategoryID, Description, Price, LastUpdatedDate) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssd", $productName, $categoryID, $description, $price);

        // Execute the statement and handle errors
        if ($stmt->execute()) {
            // After successful insertion, redirect to a success page or back to the inventory page
            header("Location: inventory.php");
            exit();
        } else {
            // Handle insertion error
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    }
}

// HTML code
?>
<div class="main-content">
    <?php
    $pathtitle = "Inventory/New";
    include '../contain/horizontal-bar.php';
    ?>
    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($errors)) {
                        echo '<div class="error-container">';
                        echo '<p class="error">Please fix the following errors:</p>';
                        echo '<ul>';
                        foreach ($errors as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '<div class="error-container" style="display:none;"></div>';
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="productName">Product name:</label>
                    <input type="text" id="productName" name="productName" placeholder="Please enter a type name"
                        required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="" selected disabled>Please select a category</option>
                        <?php
                        // Fetch category data from the database
                        $categorySql = "SELECT * FROM Category";
                        $categoryResult = $conn->query($categorySql);

                        if ($categoryResult->num_rows > 0) {
                            while ($categoryRow = $categoryResult->fetch_assoc()) {
                                echo "<option value='" . $categoryRow['CategoryID'] . "'>" . $categoryRow['Name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Please enter the description"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price (RM):</label>
                    <input type="text" id="price" name="price" placeholder="Please enter a price"
                        oninput="validateNumberInput(this)" required>
                </div>
                <div class="form-group">
                    <button type="submit">Add</button>
                    <button type="button" class="cancel" onclick="window.location.href='inventory.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>