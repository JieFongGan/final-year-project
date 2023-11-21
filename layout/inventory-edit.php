<?php
$pageTitle = "Inventory/edit";
include '../contain/header.php';
?>

<div class="main-content">

    <?php
    $pathtitle = "Inventory/edit";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
    <div class="form-container">
            <form action="/submit-inventory" method="post">
                <div class="form-group">
                    <label for="productId">Product ID:</label>
                    <input type="text" id="productID" name="productID" placeholder="Product name">
                </div>
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productName" name="productName" placeholder="Product name">
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category">
                        <option value="" selected disabled>Please select a category</option>
                        <option value="new">New</option>
                        <option value="new">New</option>
                        <option value="new">New</option>
                        <option value="new">New</option>
                        <!-- Other options -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="productWarehouse">Warehouse:</label>
                    <input type="text" id="productWarehouse" name="productWarehouse" placeholder="Warehouse">
                </div>
                <div class="form-group">
                    <label for="productDescription">Description:</label>
                    <textarea id="productDescription" name="productDescription" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="productPrice">Price (RM):</label>
                    <input type="number" id="productPrice" name="productPrice" placeholder="Price" oninput="validateNumberInput(this)">
                </div>
                <div class="form-group">
                    <label for="productQuantity">Quantity:</label>
                    <input type="number" id="productQuantity" name="productQuantity" placeholder="Quantity" oninput="validateNumberInput(this)">
                </div>
                <div class="form-group">
                    <label for="productSupplier">Supplier:</label>
                    <input type="text" id="productSupplier" name="productSupplier" placeholder="Supplier">
                </div>
                <div class="form-group">
                    <button type="submit">Add</button>
                    <button type="button" class="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </main>

</div>
</body>

</html>