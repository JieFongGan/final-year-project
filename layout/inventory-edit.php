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
                    <label for="productName">Product name:</label>
                    <input type="text" id="productName" name="productName" placeholder="Please enter a type name">
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
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Please enter the description"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price (RM):</label>
                    <input type="text" id="price" name="price" placeholder="Please enter a price">
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