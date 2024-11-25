<?php
include('includes/database_config.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
$mail = new PHPMailer();
$success_message = '';
if (isset($_POST['resetaccount'])) {
    $account_type = 0; // 0 -> student and 1 -> admin
    $registration_error = '';
    $success_message = '';
    $email_address = $_POST['email_address'];
    $fullname = '';
    $validation = 1;
    $checkcount2 = 0; // It means the user is not registered as a resident if equal to 0
    $checkuser = mysqli_query($DB, "SELECT * FROM users WHERE email = '$email_address'");
    if (mysqli_num_rows($checkuser) > 0) {
        while ($row = mysqli_fetch_assoc($checkuser)) {
            $checkcount2++;
            $fullname = $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName'];
        }
    }

    if ($checkcount2 == 0) {
        $checkingagain = 0;
        $checkuser2 = mysqli_query($DB, "SELECT * FROM school WHERE email_address = '$email_address'");
        if (mysqli_num_rows($checkuser2) > 0) {
            while ($row2 = mysqli_fetch_assoc($checkuser2)) {
                $account_type = 1;
                $checkingagain++;
                $fullname = $row2['full_name'];
            }
        }

        if ($checkingagain == 0) {
            $validation = 0;
            $registration_error .= 'Account is not existing, please check your account`s email address. For more information you can contact or visit the baranggay for your concerns, Thank you!';

            unset($_POST['resetaccount']);
            showSweetAlertFailed("Failed!", $registration_error, "error");
        }
    }

    if ($account_type == 0) {

        if ($validation == 1) {
            $password = generatePassword(8); //generate strong password
            $update_password = md5($password);

            $query = "UPDATE users SET password = '$update_password' WHERE email = '$email_address'";
            $result = mysqli_query($DB, $query);
            if ($result) {
                $message = '
                                    <html>
                                    <body>
                                        <center>
                                            <h2><b>NEUCONNECT</b></h2>
                                        </center>
                                        <p>Dear ' . $fullname . ',</p>
                                        <br>
                                        <p>We hope this message finds you well.</p>
                                        <p>We are writing to inform you that your password has been successfully reset for your NEUCONNECT account.</p>
                                        <br>
                                        <p><b>New Password:</b> <span style="color:blue;">' . $password . '</span></p>
                                        <br>
                                        <p>Please remember to keep your password secure and do not share it with anyone.</p>
                                        <p>If you did not request this password reset, please contact us immediately.</p>
                                        <br>
                                        <p>Feel free to log in to your account using the provided password. You will be prompted to change your password upon login for security reasons.</p>
                                        <br>
                                        <p>If you encounter any issues or have questions, our support team is here to assist you. You can reach us via email or phone.</p>
                                        <br>
                                        <p>Thank you for choosing NEUCONNECT. We appreciate your trust and look forward to serving you.</p>
                                        <br>
                                        <p>Best regards,</p>
                                        <p>The NEUCONNECT Team</p>
                                    </body>
                                    </html>';


                try {
                    $cust_email = $email_address;
                    $cust_name = $fullname;

                    $mail->IsSMTP();
                    $mail->Mailer = "mail"; //when in hosting change this to email
                    $mail->SMTPDebug  = false;
                    $mail->SMTPAuth   = true; //when in hosting change this to false
                    $mail->SMTPSecure = "tls";
                    $mail->Port       = 587; //retain 587 or use 465
                    $mail->Host       = "smtp.gmail.com";
                    $mail->Username   = "finalsemcapstone@gmail.com"; //retain the gmail here.
                    $mail->Password   = "bzgkrupmopizwgog";       //nhnavzxoojdmzwax this is the app password for the email to work.
                    $mail->IsHTML(true);
                    $mail->AddAddress($cust_email, $cust_name);
                    $mail->SetFrom("finalsemcapstone@gmail.com", "NEUCONNECT"); //when in hosting change this the email of web.z
                    $mail->AddReplyTo("finalsemcapstone@gmail.com", "NEUCONNECT"); //when in hosting change this the email of web.z
                    $mail->Subject = 'Account Registration';
                    $EMAIL_MESSAGE = $message;
                    $mail->MsgHTML($EMAIL_MESSAGE);
                    if (!$mail->Send()) {
                        $success_message =  "Error while sending Email!";
                        var_dump($mail);
                    } else {
                        $success_message =  "Email has been sent successfully!";
                    }
                } catch (Exception $e) {
                    echo 'Message could not be sent!';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }

                unset($_POST['resetaccount']);
                showSweetAlertSucced("Success!", "Your new password has been sent to your email successfully.", "success");
            } else {

                $error = "Error resetting your password: " . mysqli_error($DB);
                unset($_POST['resetaccount']);
                showSweetAlertFailed("Failed!", $error, "error");
            }
        }
    } else {

        if ($validation == 1) {
            $password = generatePassword(8); //generate strong password
            $update_password = md5($password);

            $query = "UPDATE school SET password = '$update_password' WHERE email_address = '$email_address'";
            $result = mysqli_query($DB, $query);
            if ($result) {
                $message = '
                                    <html>
                                    <body>
                                        <center>
                                            <h2><b>NEUCONNECT</b></h2>
                                        </center>
                                        <p>Dear ' . $fullname . ',</p>
                                        <br>
                                        <p>We hope this message finds you well.</p>
                                        <p>We are writing to inform you that your password has been successfully reset for your NEUCONNECT account.</p>
                                        <br>
                                        <p><b>New Password:</b> <span style="color:blue;">' . $password . '</span></p>
                                        <br>
                                        <p>Please remember to keep your password secure and do not share it with anyone.</p>
                                        <p>If you did not request this password reset, please contact us immediately.</p>
                                        <br>
                                        <p>Feel free to log in to your account using the provided password. You will be prompted to change your password upon login for security reasons.</p>
                                        <br>
                                        <p>If you encounter any issues or have questions, our support team is here to assist you. You can reach us via email or phone.</p>
                                        <br>
                                        <p>Thank you for choosing NEUCONNECT. We appreciate your trust and look forward to serving you.</p>
                                        <br>
                                        <p>Best regards,</p>
                                        <p>The NEUCONNECT Team</p>
                                    </body>
                                    </html>';


                try {
                    $cust_email = $email_address;
                    $cust_name = $fullname;

                    $mail->IsSMTP();
                    $mail->Mailer = "smtp"; //when in hosting change this to email
                    $mail->SMTPDebug  = false;
                    $mail->SMTPAuth   = true; //when in hosting change this to false
                    $mail->SMTPSecure = "tls";
                    $mail->Port       = 587; //retain 587 or use 465
                    $mail->Host       = "smtp.gmail.com";
                    $mail->Username   = "finalsemcapstone@gmail.com"; //retain the gmail here.
                    $mail->Password   = "bzgkrupmopizwgog";       //nhnavzxoojdmzwax this is the app password for the email to work.
                    $mail->IsHTML(true);
                    $mail->AddAddress($cust_email, $cust_name);
                    $mail->SetFrom("finalsemcapstone@gmail.com", "NEUCONNECT"); //when in hosting change this the email of web.z
                    $mail->AddReplyTo("finalsemcapstone@gmail.com", "NEUCONNECT"); //when in hosting change this the email of web.z
                    $mail->Subject = 'Account Registration';
                    $EMAIL_MESSAGE = $message;
                    $mail->MsgHTML($EMAIL_MESSAGE);
                    if (!$mail->Send()) {
                        $success_message =  "Error while sending Email!";
                        var_dump($mail);
                    } else {
                        $success_message =  "Email has been sent successfully!";
                    }
                } catch (Exception $e) {
                    echo 'Message could not be sent!';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }

                unset($_POST['resetaccount']);
                showSweetAlertSucced("Success!", "Your new password has been sent to your email successfully.", "success");
            } else {

                $error = "Error resetting your password: " . mysqli_error($DB);
                unset($_POST['resetaccount']);
                showSweetAlertFailed("Failed!", $error, "error");
            }
        }
    }
}

function generatePassword($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $index = random_int(0, strlen($characters) - 1);
        $password .= $characters[$index];
    }
    return $password;
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
                     window.location.href = 'forgot.php';
                   });
               }, 1000); // Delay in milliseconds (e.g., 1000ms = 1 second)
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
                         window.location.href = 'forgot.php';
                       });
                   }, 1000); // Delay in milliseconds (e.g., 1000ms = 1 second)
                   </script>";
}
?>
<!DOCTYPE html>
<?php  ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Retrieval</title>
    <link rel="stylesheet" href="style/css/bootstrap.min.css">
    <script defer src="style/js/bootstrap.bundle.js"></script>
    <script src="asset/js/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="asset/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="asset/sweetalert2/dist/sweetalert2.min.css">
    <link href="style/fontawesome/css/all.min.css" rel="stylesheet">

    <!DOCTYPE html>
    <html>

    <head>
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
                border: 1px solid #e0e0e0;
                padding: 0.75rem;
                border-radius: 10px;
            }

            .form-control:focus {
                box-shadow: none;
                border-color: #102c57;
            }

            .btn-orange {
                background: linear-gradient(135deg, #012952, #1e56a0);
                border: none;
                color: white;
                padding: 0.75rem 2rem;
                border-radius: 10px;
                font-weight: 500;
                transition: all 0.3s ease;
                min-width: 200px;
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

            .success-message {
                color: #28a745;
                font-weight: 500;
                margin-bottom: 1rem;
            }

            .form-label {
                color: #102c57;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }
        </style>
    </head>

<body>

    <div class="container">
        <h1 class="text-center mb-5" data-aos="fade-down">NEUCONNECT PASSWORD RESET</h1>
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="wrapper" data-aos="zoom-in">
                    <h2 class="text-center mb-3">Account Retrieval</h2>

                    <p class="text-center text-muted mb-2">
                        Input your account email address and click Reset My Password to retrieve your account access.
                    </p>
                    <p class="text-center text-muted mb-4">
                        A new password will be sent to your email inbox.
                    </p>

                    <?php if ($success_message) { ?>
                        <p class="text-center success-message">
                            <?php echo $success_message; ?>
                        </p>
                    <?php } ?>

                    <form action="" method="post">
                        <input type="text" value="false" hidden id="isForgotPassword" name="isForgotPassword">

                        <div class="form-group mb-4">
                            <label class="form-label" for="email_address">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email"
                                    class="form-control"
                                    id="email_address"
                                    name="email_address"
                                    placeholder="Enter your account email address"
                                    required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-4">
                            <button type="submit"
                                name="resetaccount"
                                id="resetaccount"
                                class="btn btn-orange">
                                Reset My Password
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

</body>

</html>