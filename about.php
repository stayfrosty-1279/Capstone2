<?php
include('includes/database_config.php');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEUConnect</title>
    <!-- Bootstrap ICON CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- FONT FAMILY -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS LINK -->
    <link rel="stylesheet" href="style/style.css">

    <link rel="icon" type="image/png" href="assets/uploads/<?php echo $organization['org_logo']; ?>">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .about-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 80%;
            margin-top: 15vh;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #102c57;
        }

        .header-title {
            color: #102c57;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .content-section {
            margin-bottom: 2rem;
        }

        .content-title {
            color: #102c57;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .content-text {
            color: #333;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .org-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .org-item {
            text-align: center;
            padding: 0.5rem;
            transition: transform 0.3s ease;
        }

        .org-item:hover {
            transform: translateY(-5px);
        }

        .org-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 0.5rem;
        }

        .social-media {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            gap: 1rem;
            max-width: 300px;
        }

        .social-icon {
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .social-icon:hover {
            transform: translateY(-5px);
        }

        .social-icon img {
            width: 50px;
            height: 50px;
        }

        .org-name {
            font-weight: bold;
            margin-top: 0.5rem;
            color: #102c57;
        }
    </style>
</head>

<body>
    <?php include 'includes/modals.php'; ?>

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

    <div class="about-container">
        <div class="header-container">
            <h2 class="header-title">ABOUT NEUCONNECT</h2>
        </div>

        <div class="content-section">
            <div class="content-text">
                <b>NEUCONNECT</b> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </div>
        </div>

        <div class="content-section">
            <h3 class="content-title">Organizations</h3>
            <div class="org-grid">
                <?php
                $counter = 0;
                $checkresident22 = mysqli_query($DB, "SELECT * FROM organization");
                if (mysqli_num_rows($checkresident22) > 0) {
                    while ($data22 = mysqli_fetch_assoc($checkresident22)) {
                        $counter++;
                ?>
                        <div class="org-item">
                            <img class="org-logo" src="assets/uploads/<?php echo $data22['org_logo']; ?>" alt="Logo">
                     
                        </div>
                <?php 
                    }
                } else {
                    echo '<div class="content-text">No organizations found.</div>';
                }
                ?>
            </div>
        </div>

        <div class="content-section">
            <h3 class="content-title">Our Social Media Accounts</h3>
            <div class="social-media">
                <a class="social-icon" href="#" target="_blank">
                    <img src="https://img.icons8.com/color/96/facebook-new.png" alt="facebook">
                </a>
                <a class="social-icon" href="#" target="_blank">
                    <img src="https://img.icons8.com/fluency/96/instagram-new.png" alt="instagram">
                </a>
                <a class="social-icon" href="#" target="_blank">
                    <img src="https://img.icons8.com/3d-fluency/94/twitter-circled.png" alt="twitter">
                </a>
                <a class="social-icon" href="#" target="_blank">
                    <img src="https://img.icons8.com/fluency/96/youtube-music.png" alt="youtube">
                </a>
            </div>
        </div>
    </div>
</body>

</html>