<?php
require_once('../includes/database_config.php');
?>
<style>
    .sidebar-dark-orange .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-orange .nav-sidebar>.nav-item>.nav-link.active {
        background-color: white;
        color: black;
    }
</style>
<?php

$myorg = $_SESSION['org_id'];
$org_name = '';
$org_logo = '';
$residentqeury = mysqli_query($DB, "SELECT * FROM organization WHERE org_id='$myorg'");
if (mysqli_num_rows($residentqeury) > 0) {
    while ($row = mysqli_fetch_assoc($residentqeury)) {
        $org_name = $row['org_name'];
        $org_logo = $row['org_logo'];
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-orange elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <?php if ($_SESSION['priviledges'] == 'Super Admin') { ?>
            <img src="images/NEU.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-bold">ADMIN PANEL</span>
        <?php } else { ?>
            <img src="../assets/uploads/<?php echo $org_logo; ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
			<br>
           
        <?php } ?>
    </a>
    <!-- Sidebar --> 
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <?php if ($_SESSION['priviledges'] == 'Super Admin') { ?>
            <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True') { ?>
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image mt-2">
                        <style>
                            .circle-img {
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                object-fit: cover;
                            }
                        </style>
                        <?php if ($_SESSION['Profile_image'] == '') { ?>
                            <img src="../assets/uploads/profile_icon.png" alt="Circle Image" class="circle-img">
                        <?php } else { ?>
                            <img src="../assets/uploads/<?php echo $_SESSION['Profile_image']; ?>" class="img-circle elevation-2" alt="User Image">
                        <?php } ?>
                    </div>
                    <div class="info">
                  <?php
if (isset($_SESSION['middleName']) && !empty($_SESSION['middleName'])) {
    $middleName = $_SESSION['middleName'];
    $firstLetter = substr($middleName, 0, 1) . '. ';
} else {
    $firstLetter = '';
}
?>
<a href="#" data-toggle="modal" data-target="#editAdminProfile" class="d-block">
    <?php echo $_SESSION['firstName'] . ' ' . $firstLetter . $_SESSION['lastName']; ?>
</a>
                        <small class="text-dark d-block" style="color:black;">Administrator</small>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <a href="dashboard.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'dashboard.php')
                                                                echo 'active'; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <?php if ($_SESSION['priviledges'] == "Super Admin") { ?>

                    <hr style="border-bottom:2px solid #d9d9d9; width:100%;">


                    <li class="nav-item ">
                        <a href="organization.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'organization.php')
                                                                        echo 'active'; ?>">
                            <i class="nav-icon fa fa-building"></i>
                            <p>
                                Manage Organizations
                            </p>
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a href="amenities.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'amenities.php')
                                                                    echo 'active'; ?>">
                            <i class="nav-icon fa fa-list"></i>
                            <p>
                                Manage Bookings
                            </p>
                        </a>
                    </li>
                    <hr style="border-bottom:2px solid #d9d9d9; width:100%;">

                    <li class="nav-item">
                        <a href="manage_users.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'manage_users.php')
                                                                        echo 'active'; ?>">
                            <i class="fa fa-users nav-icon"></i>
                            <p>Manage Officers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_staff.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'manage_staff.php')
                                                                        echo 'active'; ?>">
                            <i class="fa fa-users nav-icon"></i>
                            <p>Manage Admins</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_coordinators.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'manage_coordinators.php')
                                                                                echo 'active'; ?>">
                            <i class="fa fa-users nav-icon"></i>
                            <p>Manage Coordinators</p>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['priviledges'] == "Coordinator") { ?>

                    <hr style="border-bottom:2px solid #d9d9d9; width:100%;">
                    <li class="nav-item">
                        <a href="events.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'events.php')
                                                                    echo 'active'; ?>">
                            <i class="far fa-calendar nav-icon"></i>
                            <p>Manage Events</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="participants.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'participants.php')
                                                                        echo 'active'; ?>">
                            <i class="fa fa-users nav-icon"></i>
                            <p>Manage Participants</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_users.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'manage_users.php')
                                                                        echo 'active'; ?>">
                            <i class="far fa-user nav-icon"></i>
                            <p>Manage Officers</p>
                        </a>
                    </li>

                    <hr style="border-bottom:2px solid #d9d9d9; width:100%;">

                    <li class="nav-item ">
                        <a href="files.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'files.php')
                                                                echo 'active'; ?>">
                            <i class="nav-icon fa fa-file"></i>
                            <p>
                                Manage Files
                            </p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a href="sliders.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'sliders.php')
                                                                    echo 'active'; ?>">
                            <i class="nav-icon fa fa-image"></i>
                            <p>
                                Manage Sliders
                            </p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a href="org_settings.php" class="nav-link <?php if (basename(htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8")) == 'org_settings.php')
                                                                        echo 'active'; ?>">
                            <i class="nav-icon fa fa-clone"></i>
                            <p>
                                Manage Page
                            </p>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>