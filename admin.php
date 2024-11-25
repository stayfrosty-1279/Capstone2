<?php
require_once('includes/database_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
$mail = new PHPMailer();
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>

<?php
require_once('includes/database_config.php');
if (isset($_SESSION['priviledges']) && $_SESSION['priviledges'] == 'Super Admin' && isset($_SESSION['logged']) && $_SESSION['logged'] == 'True') {
    header('Location: admin/dashboard.php');
    exit();
}

if (isset($_POST['adminlogin'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $checkresident = mysqli_query($DB, "SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
    if (mysqli_num_rows($checkresident) > 0) {
        while ($residentrecord = mysqli_fetch_assoc($checkresident)) {
            $_SESSION['logged'] = 'True'; //if false it means log out;
            $_SESSION['priviledges'] = $residentrecord['role'];
            $_SESSION['user_id'] = $residentrecord['admin_ID'];
            $_SESSION['firstName'] = $residentrecord['firstName'];
            $_SESSION['middleName'] = $residentrecord['middleName'];
            $_SESSION['lastName'] = $residentrecord['lastName'];
            $_SESSION['fullname'] = $_SESSION['firstName'] . ' ' . $_SESSION['middleName'] . '. ' . $_SESSION['lastName'];
            $_SESSION['username'] = $residentrecord['username'];
            $_SESSION['password'] = $residentrecord['password'];
            $_SESSION['Profile_image'] = $residentrecord['profileImage'];
            $_SESSION['org_id'] = $residentrecord['org_id'];
        }
    } else {

        showSweetAlertFailed2("Login Failed!", "Incorrect username / Password!", "error");
    }

    if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True') {

        showSweetAlertSucced2("Success", "Signed IN successfully!", "success");
    }
}
function showSweetAlertFailed2($title, $message, $type)
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
                         window.location.href = 'logout.php';
                       });
                   }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
                   </script>";
}
function showSweetAlertSucced2($title, $message, $type)
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
                     window.location.href = 'admin/dashboard.php';
                   });
               }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
               </script>";
}
function showSweetAlertSucced($title, $message, $type)
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
function showSweetAlertFailed($title, $message, $type)
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEUCONNECT Admin Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #102c57, #1e56a0);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .wrapper {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #012952, #1e56a0, #012952);
        }

        h1 {
            color: white;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #102c57;
            font-weight: 600;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: none;
            color: #102c57;
        }

        .form-control {
            border: none;
            padding: 0.75rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #102c57;
        }

        .btn-orange {
            background: linear-gradient(135deg, #012952, #1e56a0);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(1, 41, 82, 0.3);
        }

        .link-secondary {
            color: #102c57;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .link-secondary:hover {
            color: #1e56a0;
        }

        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 0.75rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5" data-aos="fade-down">NEUCONNECT ADMINISTRATOR PORTAL</h1>
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="wrapper" data-aos="zoom-in">
                    <h2 class="text-center mb-2">Welcome Back</h2>
                    <p class="text-center text-muted mb-4">Sign in to your administrator account</p>
                    
                    <?php 
                    if (isset($_SESSION['admin_loginresult']) && $_SESSION['admin_loginresult'] != '') { 
                    ?>
                        <div class="error-message mb-4">
                            <?php 
                            echo $_SESSION['admin_loginresult'];
                            $_SESSION['admin_loginresult'] = ''; 
                            ?>
                        </div>
                    <?php 
                    } 
                    ?>
					
					
					
					
			
					
					
					
					
					
					
					
					
					
					
					
					
					
					

                    <form action="" method="post">
                        <div class="form-group mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" placeholder="Username" name="username" required>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                            </div>
                        </div>
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-orange" name="adminlogin">
                                Sign In
                            </button>
                        </div>
                        <div class="text-center">
                            <a class="link-secondary" href="index.php">
                                <i class="fas fa-arrow-left me-2"></i>Back to Homepage
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>