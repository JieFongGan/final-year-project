<?php
$pageTitle = "Inventory/edit";
include '../contain/header.php';
include("../database/database-connect.php");

// Check if the product ID is set in the URL
if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Fetch product information based on the product ID
    $sql = "SELECT * FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productData = $result->fetch_assoc();
    } else {
        // Handle the case where no product is found with the given ID
        echo "Product not found.";
        exit();
    }

    $stmt->close();
} else {
    // Handle the case where product ID is not set
    echo "Product ID not specified.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the product ID is set in the form
    if (isset($_POST['productID'])) {
        $productID = $_POST['productID'];

        // Retrieve other form data
        $productName = $_POST['productName'];
        $categoryID = $_POST['category'];
        $warehouseID = !empty($_POST['productWarehouse']) ? $_POST['productWarehouse'] : null;
        $description = $_POST['productDescription'];
        $price = $_POST['productPrice'];
        $quantity = $_POST['productQuantity'];

        // Update the product in the database
        $updateSql = "UPDATE Product SET 
                      Name = ?, 
                      CategoryID = ?, 
                      WarehouseID = ?, 
                      Description = ?, 
                      Price = ?, 
                      Quantity = ?, 
                      LastUpdatedDate = NOW() 
                      WHERE ProductID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("siisdii", $productName, $categoryID, $warehouseID, $description, $price, $quantity, $productID);
        $updateStmt->execute();

        // Check if the update was successful
        if ($updateStmt->affected_rows > 0) {
            echo "Product updated successfully.";
            header("Location: inventory.php");
            exit();
        } else {
            echo "Error updating product: " . $updateStmt->error;
        }

        $updateStmt->close();
    }
}

?>

<div class="main-content">
    <?php
    $pathtitle = "Inventory/edit";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <div class="form-container">
            <form action="" method="post">
                <div class="form-group">
                    <label for="productId">Product ID:</label>
                    <input type="text" id="productID" name="productID" value="<?= $productData['ProductID'] ?>"
                        readonly>
                </div>
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productName" name="productName" value="<?= $productData['Name'] ?>"
                        placeholder="Product name">
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category">
                        <option value="" disabled>Please select a category</option>
                        <?php
                        $categorySql = "SELECT CategoryID, Name FROM Category";
                        $categoryResult = $conn->query($categorySql);

                        if ($categoryResult->num_rows > 0) {
                            while ($category = $categoryResult->fetch_assoc()) {
                                $selected = ($category['CategoryID'] == $productData['CategoryID']) ? 'selected' : '';
                                ?>
                                <option value="<?= $category["CategoryID"] ?>" <?= $selected ?>>
                                    <?= $category["Name"] ?>
                                </option>
                                <?php
                            }
                        } else {
                            echo "0 results";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="productWarehouse">Warehouse:</label>
                    <select id="productWarehouse" name="productWarehouse">
                        <option value="" disabled>Please select a warehouse</option>
                        <?php
                        $warehouseSql = "SELECT WarehouseID, Name FROM Warehouse";
                        $warehouseResult = $conn->query($warehouseSql);

                        if ($warehouseResult->num_rows > 0) {
                            while ($warehouse = $warehouseResult->fetch_assoc()) {
                                $selected = ($warehouse['WarehouseID'] == $productData['WarehouseID']) ? 'selected' : '';
                                ?>
                                <option value="<?= $warehouse["WarehouseID"] ?>" <?= $selected ?>>
                                    <?= $warehouse["Name"] ?>
                                </option>
                                <?php
                            }
                        } else {
                            echo "0 results";
                        }
                        
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="productDescription">Description:</label>
                    <textarea id="productDescription" name="productDescription"
                        placeholder="Description"><?= $productData['Description'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="productPrice">Price (RM):</label>
                    <input type="number" id="productPrice" name="productPrice" value="<?= $productData['Price'] ?>"
                        placeholder="Price" oninput="validateNumberInput(this)">
                </div>
                <div class="form-group">
                    <label for="productQuantity">Quantity:</label>
                    <input type="number" id="productQuantity" name="productQuantity"
                        value="<?= $productData['Quantity'] ?>" placeholder="Quantity"
                        oninput="validateNumberInput(this)">
                </div>
                <div class="form-group">
                    <input type="hidden" name="productID" value="<?= $productData['ProductID'] ?>">
                    <button type="submit">Update</button>
                    <button type="button" class="cancel" onclick="window.location.href='inventory.php'">Cancel</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>

</html>