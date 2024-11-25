<?php
include('includes/database_config.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<?php

function showSweetAlertSucced3($title, $message, $type)
{
    echo "
               <script>
               setTimeout(function() {
                   Swal.fire({
                       title: '$title',
                       text: '$message',
                       icon: '$type',
                       confirmButtonText: 'OK'
                   }).then(function() {
                     window.location.href = 'index.php';
                   });
               }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
               </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEUCONNECT Home Page</title>
	
	
    <!-- Bootstrap ICON CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- FONT FAMILY -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fade CDN -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">


    <!-- CSS LINK -->
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" type="image/jpg" href="images/NEU.png">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>

    </style>

</head>
<?php
include 'includes/modals.php';
?>

<body>

    <?php


    include('javascript/javascripts.php');
    ?>



    <!-- Top Info Bar -->
    <div class="container-fluid bg-primary px-5 d-none d-lg-block">
        <div class="row gx-0 align-items-center" style="height: 45px;">
            <div class="col-lg-8 text-center text-lg-start mb-lg-0">
                <div class="d-flex flex-wrap link-text">
                    <a href="https://neu.edu.ph/main/#" class="text-light me-4 text-decoration-none"><i class="bi bi-geo-alt-fill me-2"></i>9 Central Ave, New Era, Quezon City, 1107 Metro Manila</a>
                    <a href="https://neu.edu.ph/main/#" class="text-light me-4 text-decoration-none"><i class="bi bi-telephone-fill me-2"></i>(02) 8981 4221</a>
                    <a href="https://neu.edu.ph/main/#" class="text-light me-0 text-decoration-none"><i class="bi bi-envelope-at-fill me-2"></i>info@neu.edu.ph</a>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/navBar.php'); ?>


    <!-- Header Section -->
    <div class="header-bg">
        <div class="container">
            <div class="white-box" data-aos="fade-right">
                <h1 class="header-title">NEUConnect:</h1>
                <h3 class="header-subtitle">Directory of Student Organizations within the Campus</h3>
            </div>
        </div>
    </div>


    <!-- Central Student Council -->
    <div class="container mt-5">
        <h2 class="section-title">Head Organization</h2>
        <div class="row">
            <?php
            $ssg_query = mysqli_query($DB, "SELECT * FROM organization 
                WHERE org_status = 'Active' AND org_name = 'Central Student Council'");

            if (mysqli_num_rows($ssg_query) > 0) {
                while ($row = mysqli_fetch_assoc($ssg_query)) {
            ?>
                    <div class="col-md-6 mx-auto">
                        <a href="view_organization.php?id=<?php echo $row['org_id']; ?>" class="text-decoration-none">
                            <div class="card csc-card">

                                <div class="card-body text-center">
                                    <img src="assets/uploads/<?php echo $row['org_logo']; ?>" alt="<?php echo $row['org_name']; ?>" class="img-fluid">
                                    <h4 class="card-title text-center mb-4"><?php echo $row['org_name']; ?></h4>
                                    <div class="badge-container">
                                        <span class="org-badge csc-badge">
                                            <span class="shine-1"></span>
                                            <span class="shine-2"></span>
                                            Student Governing Body
                                        </span>
                                        <?php if (!empty($row['org_department'])): ?>
                                            <span class="org-badge department-badge"><?php echo $row['org_department']; ?></span>
                                        <?php endif; ?>
                                        <span class="org-badge type-badge"><?php echo $row['org_type']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <br>
    <div class="b-example-divider"></div>

    <!-- Head Organizations Section -->
    <div class="container mt-5">
        <h2 class="section-title">Mother Organizations</h2>
        <div class="row justify-content-center"> <!-- Added justify-content-center here -->
            <?php
            $mother_org_query = mysqli_query($DB, "SELECT * FROM organization 
            WHERE org_status = 'Active' 
            AND org_ismother = 'Yes'
            AND org_name != 'Central Student Council'
            ORDER BY org_name ASC");

            if (mysqli_num_rows($mother_org_query) > 0) {
                while ($row = mysqli_fetch_assoc($mother_org_query)) {
            ?>
                    <div class="col-md-4 mb-4">
                        <a href="view_organization.php?id=<?php echo $row['org_id']; ?>" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="assets/uploads/<?php echo $row['org_logo']; ?>" alt="<?php echo $row['org_name']; ?>" class="img-fluid">
                                    <h5 class="card-title"><?php echo $row['org_name']; ?></h5>
                                    <div class="badge-container">
                                        <?php if (!empty($row['org_department'])): ?>
                                            <span class="org-badge department-badge"><?php echo $row['org_department']; ?></span>
                                        <?php endif; ?>
                                        <span class="org-badge type-badge"><?php echo $row['org_type']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <div class="b-example-divider"></div>

    <!-- Regular Organizations Section -->
    <div class="container mt-5">
        <h2 class="section-title">Organizations</h2>
        <div class="row">
            <?php
            $org_query = mysqli_query($DB, "SELECT * FROM organization 
                WHERE org_status = 'Active' 
                AND org_ismother = 'No'
                AND org_name != 'Central Student Council'
                ORDER BY org_name ASC");

            if (mysqli_num_rows($org_query) > 0) {
                while ($row = mysqli_fetch_assoc($org_query)) {
            ?>
                    <div class="col-md-4 mb-4">
                        <a href="view_organization.php?id=<?php echo $row['org_id']; ?>" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="assets/uploads/<?php echo $row['org_logo']; ?>" alt="<?php echo $row['org_name']; ?>" class="img-fluid">
                                    <h5 class="card-title"><?php echo $row['org_name']; ?></h5>
                                    <div class="badge-container">
                                        <?php if (!empty($row['org_department'])): ?>
                                            <span class="org-badge department-badge"><?php echo $row['org_department']; ?></span>
                                        <?php endif; ?>
                                        <span class="org-badge type-badge"><?php echo $row['org_type']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>


    <script>
        window.addEventListener('load', function() {
            document.body.classList.add('fade-in');
        });

        AOS.init({
            once: true,
            duration: 3000,
            delay: 500
        });
    </script>
</body>

</html>