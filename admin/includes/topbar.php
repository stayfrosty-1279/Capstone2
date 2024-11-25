<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-orange navbar-light accent-orange" style="background-color:lightgreen;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <?php if ($_SESSION['priviledges'] == 'Super Admin') { ?>
                <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True') {
                    // Prepare middle name display
                    $middleNameDisplay = '';
                    if (!empty($_SESSION['middleName'])) {
                        $middleNameDisplay = substr($_SESSION['middleName'], 0, 1) . '. ';
                    }
                ?>
                    <a class="nav-link" data-toggle="dropdown" href="#" style="color:black;">
                        <?php echo $_SESSION['firstName'] . ' ' . $middleNameDisplay . $_SESSION['lastName']; ?>

                        <style>
                            .circle-img {
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                margin-top: -10px;
                                object-fit: cover;
                                margin-left: 10px;
                            }
                        </style>

                        <?php if (empty($_SESSION['Profile_image'])) { ?>
                               <img src="../assets/uploads/profile_icon.png" alt="Circle Image" class="circle-img">
                        <?php } else { ?>
                            <img src="../assets/uploads/<?php echo $_SESSION['Profile_image']; ?>" alt="Circle Image" class="circle-img">
                        <?php } ?>
                    </a>

                    <div class="dropdown-menu dropdown-menu dropdown-menu-right">
                        <span class="dropdown-item dropdown-header" style="color:black;">
                            <h6><?php echo $_SESSION['firstName'] . ' ' . $middleNameDisplay . $_SESSION['lastName']; ?></h6>
                        </span>
                        <hr>
                        <a href="#" class="dropdown-item active-orange" data-toggle="modal" data-target="#editProfile">Edit Profile</a>
                        <hr>
                        <a href="logout.php" class="dropdown-item active-orange">Logout</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True') {
                    // Prepare middle name display for non-Super Admin
                    $middleNameDisplay = '';
                    if (!empty($_SESSION['middleName'])) {
                        $middleNameDisplay = substr($_SESSION['middleName'], 0, 1) . '. ';
                    }
                ?>
                    <a class="nav-link" data-toggle="dropdown" href="#" style="color:black;">
                        <?php echo $_SESSION['firstName'] . ' ' . $middleNameDisplay . $_SESSION['lastName']; ?>

                        <style>
                            .circle-img {
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                margin-top: -10px;
                                object-fit: cover;
                                margin-left: 10px;
                            }
                        </style>

                        <?php if (empty($_SESSION['Profile_image'])) { ?>
                            <img src="../assets/uploads/profile_icon.png" alt="Circle Image" class="circle-img">
                        <?php } else { ?>
                            <img src="../assets/uploads/<?php echo $_SESSION['Profile_image']; ?>" alt="Circle Image" class="circle-img">
                        <?php } ?>
                    </a>

                    <div class="dropdown-menu dropdown-menu dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">
                            <h6><?php echo $_SESSION['firstName'] . ' ' . $middleNameDisplay . $_SESSION['lastName']; ?></h6>
                        </span>
                        <hr>
                        <a href="#" class="dropdown-item active-orange" data-toggle="modal" data-target="#editProfile">Edit Profile</a>
                        <hr>
                        <a href="logout.php" class="dropdown-item active-orange">Logout</a>
                    </div>
                <?php } ?>
            <?php } ?>
        </li>
    </ul>
</nav>
<!-- /.navbar -->