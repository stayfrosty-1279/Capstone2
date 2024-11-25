<?php
require_once('includes/database_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
$mail = new PHPMailer();
$activation_result = '';
if ($_REQUEST['email'] == '') {
    header("Location: register.php");
    exit();
} else {
    $fetched_emailaddress = $_REQUEST['email'];
}
$_SESSION['verification_result'] = '';
if (isset($_POST['verificationresend'])) {
    $emailAddress = $_REQUEST['email'];
    $activation_code = time();
    $fullname = '';
    $checkresident = mysqli_query($DB, "SELECT * FROM users WHERE email = '$emailAddress' ");
    if (mysqli_num_rows($checkresident) > 0) {
        while ($residentrecord = mysqli_fetch_assoc($checkresident)) {
            $fullname = $residentrecord['firstName'] . ' ' . $residentrecord['middleName'] . ' ' . $residentrecord['lastName'];
        }
    }

    // Update query
    $updateQuery = "UPDATE users SET Activation_code = ? WHERE email = '$emailAddress'";
    $stmt = mysqli_prepare($DB, $updateQuery);
    mysqli_stmt_bind_param($stmt, 's', $activation_code);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {

        $message = '
                            <html><body>
                            <center><h2><b>NEUCONNECT - Resend Verification Code</b></h2></center>
    <h2>Hello,' . $fullname . ',</h2>
                           
                             <p>Thank you for registering your school with us! As requested, here is your new activation code:</p>
    <h2>Activation Code: <b><span style="color:blue;">' . $activation_code . '</span></b></h2>
    <p><strong>Please note:</strong> Your previous code is now invalid. Use this code to complete verification.</p>
    <p>For assistance, feel free to contact us through our website. Thank you!</p>
  </body>
</html>';

        try {
            $cust_email = $emailAddress;
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

        $activation_result = 'New Verification Code is sent to your email address, please check your inbox.';
    } else {

        $activation_result = 'Failed to send new Verification Code, please contact us for assistance.';
    }
    unset($_POST['verificationresend']);
}

if (isset($_POST['verificationproceed'])) {

    $emailAddress = $_REQUEST['email'];
    $inputted_activationcode = $_POST['verification_code'];

    $check_if_activationcode_inthe_email_is_existing = 0;

    $checkresident = mysqli_query($DB, "SELECT * FROM users WHERE email = '$emailAddress' AND Activation_code = '$inputted_activationcode'");
    if (mysqli_num_rows($checkresident) > 0) {
        while ($residentrecord = mysqli_fetch_assoc($checkresident)) {
            $check_if_activationcode_inthe_email_is_existing++;
        }
    }

    if ($check_if_activationcode_inthe_email_is_existing > 0) {
        // Update query
        $updateQuery = "UPDATE users SET Status = 'Active' WHERE email = '$emailAddress' AND Activation_code = '$inputted_activationcode'";
        $stmt = mysqli_prepare($DB, $updateQuery);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['verification_result'] = 'You account has been successfully verified.';
        }
    } else {

        $activation_result = 'Incorrect Verification Code! please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation</title>

    <link rel="stylesheet" href="style/css/bootstrap.min.css">
    <script defer src="style/js/bootstrap.bundle.js"></script>
    <!-- Icon Font Stylesheet -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="style/fontawesome/css/all.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="images/<?php echo $SYS_logo; ?>">
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
            margin-bottom: 1rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: none;
            color: #102c57;
        }

        .form-control {
            border: none;
            padding: 0.75rem;
            text-align: center;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #102c57;
        }

        .btn {
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            border: none;
        }

        .btn-verify {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
        }

        .btn-resend {
            background: linear-gradient(135deg, #fd7e14, #e65c00);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

        .success-message {
            text-align: center;
            margin: 2rem 0;
        }

        .btn-done {
            background: linear-gradient(135deg, #012952, #1e56a0);
            color: white;
            max-width: 200px;
            margin: 0 auto;
        }
    </style>
</head>

<body>



    <div class="container">
        <h1 class="text-center mb-5" data-aos="fade-down">NEUCONNECT VERIFICATION</h1>
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="wrapper" data-aos="zoom-in">
                    <h2 class="text-center mb-2">Activation Code</h2>

                    <?php if ($activation_result != '') { ?>
                        <div class="error-message">
                            <?php echo $activation_result; ?>
                        </div>
                    <?php } ?>

                    <?php if ($_SESSION['verification_result'] != '') { ?>
                        <div class="success-message">
                            <p class="text-center text-muted lead mb-3">
                                <b><?php echo $_SESSION['verification_result']; ?></b>
                            </p>
                            <a href="index.php" class="btn btn-done">DONE</a>
                        </div>
                        <?php $_SESSION['verification_result'] = ''; ?>
                    <?php } else { ?>
                        <p class="text-center text-muted mb-4">Enter the verification code sent to your email</p>

                        <div class="row">
                            <div class="col-12">
                                <form action="" method="POST">
                                    <input type="text" value="false" hidden id="isUserActivation" name="isUserActivation">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="verification_code"
                                                name="verification_code" placeholder="Enter Your Code" required>
                                        </div>
                                    </div>
                                    <div class="d-grid mb-3">
                                        <button type="submit" name="verificationproceed" class="btn btn-verify">
                                            Verify Code
                                        </button>
                                    </div>
                                </form>

                                <p class="text-center text-muted">
                                    Didn't receive the code?
                                </p>

                                <form action="student_activation.php?email=<?php echo $fetched_emailaddress; ?>" method="POST">
                                    <div class="d-grid">
                                        <button type="submit" name="verificationresend" class="btn btn-resend">
                                            Resend Code
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>