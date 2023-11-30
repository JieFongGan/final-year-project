<?php
$pageTitle = "Report";
include '../contain/header.php';
include("../database/database-connect.php");
?>

<div class="main-content">

    <?php
    $pathtitle = "Report";
    include '../contain/horizontal-bar.php';
    ?>

    <main>
        <ul class="settings-container">
            <li class="settings-item">
                <a class="settings-link" href="report-weekly.php">
                    <div class="settings-header">Weekly</div>
                    <span class="settings-icon">&#9656;</span>
                </a>
            </li>
            <li class="settings-item">
                <a class="settings-link" href="report-monthly.php">
                    <div class="settings-header">Monthly</div>
                    <span class="settings-icon">&#9656;</span>
                </a>
            </li>
            <li class="settings-item">
                <a class="settings-link" href="report-yearly.php">
                    <div class="settings-header">Yearly</div>
                    <span class="settings-icon">&#9656;</span>
                </a>
            </li>
        </ul>
    </main>

</div>
</body>

</html>