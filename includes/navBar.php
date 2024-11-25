<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fbfbf9;;">

    <div class="container-fluid px-5">
        <a class="navbar-brand" href="#">
            <img src="images/NEU.png" alt="NEU Logo" style="height: 50px;">
            <span class="ms-2">New Era University</span>
        </a>


        <ul class="navbar-nav">
            <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True'): ?>


                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'my_files.php') echo 'active'; ?>" href="my_files.php">MY FILES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'org_files.php') echo 'active'; ?>" href="org_files.php">ORGANIZATION FILES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'active'; ?>" href="about.php">ABOUT</a>
                </li>


                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <?php if ($_SESSION['Profile_image'] == ''): ?>
                            <img src="assets/uploads/profile_icon.png" alt="Profile" class="profile-img">
                        <?php else: ?>
                            <img src="assets/uploads/<?php echo $_SESSION['Profile_image']; ?>" alt="Profile" class="profile-img">
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfile">
                                <i class="bi bi-person-gear me-2"></i>EDIT ACCOUNT
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>LOG OUT
                            </a>
                        </li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'active'; ?>" href="about.php">ABOUT</a>
                </li>

            <?php endif; ?>
        </ul>

    </div>
</nav>