<header>
    <div class="directory-tag">
        <p>
            <?php echo $pathtitle; ?>
        </p>
    </div>

    <div class="social-icons">
        <div class="social-icon">
            <img src="../img/user-profile.png" alt="Social Icon" id="social-icon">
            <ul class="dropdown">
                <li><a href="../layout/profile.php?userID=<?php echo $userID; ?>">Profile</a></li>
                <li><a href="../layout/chpassword.php?userID=<?php echo $userID; ?>">Change Password</a></li>
                <li><a href="#">log out</a></li>
            </ul>
        </div>
    </div>
</header>