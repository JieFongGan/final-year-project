<?php
$pageTitle = "Settings";
include '../contain/header.php';
?>

<div class="main-content">

    <?php
    $pathtitle = "Settings";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <ul class="settings-container">
            <li class="settings-item">
                <a class="settings-link" href="settings-category.php">
                    <div class="settings-header">Inventory Category</div>
                    <span class="settings-icon">&#9656;</span>
                </a>
            </li>
            <li class="settings-item">
                <a class="settings-link" href="settings-user.php">
                    <div class="settings-header">User</div>
                    <span class="settings-icon">&#9656;</span>
                </a>
            </li>
        </ul>
    </main>

</div>
</body>

</html>