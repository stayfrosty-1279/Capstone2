<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fbfbf9;;">

    <div class="container-fluid px-5">

        <a class="navbar-brand" href="index.php">
            <img src="assets/uploads/<?php echo $organization['org_logo']; ?>" alt="Organization Logo">
            <span class="flex-grow-1"><?php echo $organization['org_name']; ?></span>
        </a>




        <ul class="navbar-nav">
            <?php if (!isset($_SESSION['logged']) || $_SESSION['logged'] != 'True'): ?>
                <li class="nav-item">
                    <a href="student_registration.php?id=<?php echo htmlspecialchars($_REQUEST['id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link">
                        <span class="button-style">SIGN UP</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="nav-link">
                        <span class="button-style">SIGN IN</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="index.php" class="nav-link <?php echo (basename(htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8')) == 'index.php') ? 'active' : ''; ?>">
                    HOME
                </a>
            </li>
            <li class="nav-item">
                <a href="#about" class="nav-link">ABOUT US</a>
            </li>
            <li class="nav-item">
                <a href="#officers" class="nav-link">OFFICERS</a>
            </li>
            <li class="nav-item">
                <a href="#events" class="nav-link">EVENTS</a>
            </li>

            <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True'): ?>
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if ($_SESSION['Profile_image'] == ''): ?>
                            <img src="assets/uploads/profile_icon.png" alt="Profile" class="profile-img">
                        <?php else: ?>
                            <img src="assets/uploads/<?php echo htmlspecialchars($_SESSION['Profile_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Profile" class="profile-img">
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfile">
                                <i class="bi bi-person-gear me-2"></i>Edit Account
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Log Out
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>


</nav>