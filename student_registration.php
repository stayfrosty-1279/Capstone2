<?php
require_once('includes/database_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
$mail = new PHPMailer();

$registration_message = '';
$registration_error = '';
if (isset($_POST['registernow'])) {
    $validation = 1; // 1 means proceed and 0 means not.
    $checkpass = $_POST['password123']; // password
    $confirmpass = $_POST['confirmPassword123']; // confirm password
    $firstName = $_POST['firstName'];
    $middleName = $_POST['Mi'];
    $lastName = $_POST['lastName'];
    $emailaddress = $_POST['email'];
    $password = md5($_POST['password123']);
    $rawpassword = $_POST['password123'];
    $school_id = $_REQUEST['id'];
    $position = 'Officer';
    $fullanme = $firstName . ' ' . $middleName . ' ' . $lastName;
    $activation_code = time();
    $inherit_emailaddress = $emailaddress;
    if ($checkpass == $confirmpass) {


        $path = $_FILES['personal_id']['name'];
        $path_tmp = $_FILES['personal_id']['tmp_name'];

        if ($path != '') {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $file_name = basename($path, '.' . $ext);
            if ($ext != 'JPG' && $ext != 'PNG' && $ext != 'JPEG' && $ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
                $validation = 0;
                $registration_error .= 'You must have to upload jpg, jpeg, gif or png file of Profile Picture<br>';
            }
        } else {
            $validation = 0;
            $registration_error .= 'You must have to select an image<br>';
        }




        $path2 = $_FILES['school_id']['name'];
        $path_tmp2 = $_FILES['school_id']['tmp_name'];

        if ($path2 != '') {
            $ext2 = pathinfo($path2, PATHINFO_EXTENSION);
            $file_name2 = basename($path2, '.' . $ext2);
            if ($ext2 != 'JPG' && $ext2 != 'PNG' && $ext2 != 'JPEG' && $ext2 != 'jpg' && $ext2 != 'png' && $ext2 != 'jpeg' && $ext2 != 'gif') {
                $validation = 0;
                $registration_error .= 'You must have to upload jpg, jpeg, gif or png file of School ID<br>';
            }
        } else {
            $validation = 0;
            $registration_error .= 'You must have to select an image<br>';
        }



        $checkcount = 0; // It means the user is not registered as a resident if equal to 0
        $checkcount2 = 0; // It means the user is not registered as a resident if equal to 0
        $checkuser = mysqli_query($DB, "SELECT * FROM users WHERE firstName = '$firstName' AND lastName = '$lastName' AND email = '$emailaddress'");
        if (mysqli_num_rows($checkuser) > 0) {
            $checkcount2++;
        }

        if ($checkcount2 > 0) {

            $validation = 0;
            $registration_error .= 'Account is already existing. <br>You can contact us for further information, thank you!<br>';
        }

        if ($validation == 1) {
            $id_timestamp = time();
            $id_image = 'Profile-' . $id_timestamp . '-' . $file_name . '-.' . $ext;
            move_uploaded_file($path_tmp, 'assets/uploads/' . $id_image);

            $id_image2 = 'Schoolid-' . $id_timestamp . '-' . $file_name2 . '-.' . $ext2;
            move_uploaded_file($path_tmp2, 'assets/uploads/' . $id_image2);

            try {
                $sql = "INSERT INTO users
                            (
                              firstName,
                              middleName,
                              lastName,
                              email,
                              password,
                              Activation_code,
                              profile_picture,
                              idImage,
                              org_id,
                              Position
                            ) 
                            VALUES
                            (
                              ?,
                              ?,
                              ?,
                              ?,
                              ?,
                              ?,
                              ?,
                              ?,
                              ?,
                              ?
                            )";

                $query = mysqli_prepare($DB, $sql);
                mysqli_stmt_bind_param($query, "ssssssssss", $firstName, $middleName, $lastName, $emailaddress, $password, $activation_code, $id_image, $id_image2, $school_id, $position);

                $idImage = $file_name; // Assuming $file_name holds the value for idImage

                if (mysqli_stmt_execute($query)) {
                    $lastInsertId = mysqli_insert_id($DB);

                    if ($lastInsertId) {

                        //ogbuyghbkabhkmit
                        $message = '
                                <html>
                              <body>
    <center>
        <h2><b>Welcome to NEUCONNECT!</b></h2>
    </center>
    <p>Dear ' . $fullname . ',</p>
    <br>
    <p>We are pleased to inform you that your account has been successfully registered with NEUCONNECT.</p>
    <br>
    <p>As an officer of our organization, you now have access to NEUCONNECT—a secure platform designed exclusively for managing and organizing academic documents with efficiency and confidentiality.</p>
    <br>
    <p><b>Activation Code:</b> <span style="color:blue;">' . $activation_code . '</span></p>
    <br>
    <p>Please use the above activation code to complete your officer registration on our website.</p>
    <br>
    <p><b>Note:</b> This registration feature is reserved solely for authorized officers of the organization. Any registration requests from unauthorized individuals will be declined.</p>
    <br>
    <p>If you have any questions or need further assistance, please do not hesitate to contact our support team.</p>
    <br>
    <p>Thank you for joining NEUCONNECT. We look forward to supporting you with a secure and reliable document management experience!</p>
    <br>
    <p>Best regards,</p>
    <p><strong>The NEUCONNECT Team</strong></p>
</body>

                                </html>';


                        try {
                            $cust_email = $emailaddress;
                            $cust_name = $fullanme;

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

                        unset($checkpass, $confirmpass, $firstName, $middleName, $lastName, $dateOfBirth, $gender, $address, $emailaddress, $contactNumber, $password, $id_image, $id_image2, $position);
                        $registration_message .= "Your account has been successfully registered!";
                        $_SESSION['registration_result'] = $registration_message;

                        header("Location: student_activation.php?email=$inherit_emailaddress");
                        exit();
                    } else {
                        $registration_error .= 'Something happened! Please try again.';
                    }
                } else {
                    $registration_error .= 'An error occurred while registering your account: ' . mysqli_error($DB);
                }

                mysqli_stmt_close($query);
            } catch (PDOException $e) {
                $registration_error .= 'An error occurred while registering your account: ' . $e->getMessage();
            }
        }
    } else {
        $registration_error .= 'Password confirmation is incorrect!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration</title>
    <link rel="stylesheet" href="style/css/bootstrap.min.css">
    <script defer src="style/js/bootstrap.bundle.js"></script>
    <!-- Icon Font Stylesheet -->
    <link href="style/fontawesome/css/all.min.css" rel="stylesheet">


</head>
<?php
include 'includes/modals.php';
?>

<body>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <?php
    include('includes/navBar.php');
    include('javascript/javascripts.php');
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="wrapper" style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); position: relative; overflow: hidden; margin: 2rem 0;">
                    <!-- Gradient top border -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #012952, #1e56a0, #012952);"></div>

                    <h2 class="text-center mb-2" style="color: #102c57; font-weight: 600;">Create Officer Account</h2>

                    <?php if ($registration_message != '' || $registration_error != '') { ?>
                        <?php
                        $labelcolor = "green";
                        if ($registration_message != '') {
                            $labelcolor = 'green';
                        }
                        if ($registration_error != '') {
                            $labelcolor = 'red';
                        }
                        ?>
                        <div class="alert" style="background-color: <?php echo $labelcolor === 'red' ? '#f8d7da' : '#d4edda'; ?>; 
                                            color: <?php echo $labelcolor === 'red' ? '#721c24' : '#155724'; ?>; 
                                            padding: 1rem; border-radius: 10px; margin: 1rem 0; text-align: center;">
                            <?php if ($registration_error != '') {
                                echo $registration_error;
                            } ?>
                        </div>
                    <?php } ?>

                    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <div class="section-title" style="color: #102c57; font-size: 1.1rem; font-weight: 600; margin: 2rem 0 1rem;">
                            Personal Information
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        First Name <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="first-name" name="firstName"
                                        placeholder="Enter First Name"
                                        value="<?php if (isset($_POST['firstName'])) {
                                                    echo $_POST['firstName'];
                                                } ?>"
                                        required
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.75rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Middle Name <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="middle-name" name="Mi"
                                        placeholder="Enter Middle Name"
                                        value="<?php if (isset($_POST['Mi'])) {
                                                    echo $_POST['Mi'];
                                                } ?>"
                                        required
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.75rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Last Name <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="last-name" name="lastName"
                                        placeholder="Enter Last Name"
                                        value="<?php if (isset($_POST['lastName'])) {
                                                    echo $_POST['lastName'];
                                                } ?>"
                                        required
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.75rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>
                        </div>

                        <div class="section-title" style="color: #102c57; font-size: 1.1rem; font-weight: 600; margin: 2rem 0 1rem;">
                            Registration Details
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Upload Profile Image <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="personal_id" name="personal_id" required
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.5rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Upload School/Employee ID <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="school_id" name="school_id" required
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.5rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Email Address <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="email123123" name="email"
                                        placeholder="Enter Email"
                                        value="<?php if (isset($_POST['email'])) {
                                                    echo $_POST['email'];
                                                } ?>"
                                        required onkeyup="validateEmail(this.value)"
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.75rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                    <small id="emailErrorLabel123123" class="error-label"
                                        style="color: #dc3545; display: none; font-size: 0.875rem; margin-top: 0.25rem;">
                                        Please enter a valid email address! Example: brgy.sanjose@gmail.com
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Password <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="password123" name="password123"
                                        placeholder="Enter Password" required oninput="validatePassword()"
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.75rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" style="color: #102c57; margin-bottom: 0.5rem;">
                                        Confirm Password <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="confirmPassword123" name="confirmPassword123"
                                        placeholder="Confirm Password" required oninput="validatePassword()"
                                        style="border-radius: 10px; border: 1px solid #dee2e6; padding: 0.75rem;
                                              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                </div>
                            </div>
                        </div>

                        <div class="password-validation mt-3" style="text-align: center;">
                            <small id="passwordErrorLabelqwe" class="error-label d-block" style="color: #dc3545; display: none;">
                                Passwords must match.
                            </small>
                            <small id="lengthErrorLabelqwe" class="error-label d-block" style="color: #dc3545; display: none;">
                                Password must be at least 8 characters long.
                            </small>
                            <small id="uppercaseErrorLabelqwe" class="error-label d-block" style="color: #dc3545; display: none;">
                                Password must contain at least one uppercase letter.
                            </small>
                            <small id="numberErrorLabelqwe" class="error-label d-block" style="color: #dc3545; display: none;">
                                Password must contain at least one number.
                            </small>
                            <small id="symbolErrorLabelqwe" class="error-label d-block" style="color: #dc3545; display: none;">
                                Password must contain at least one symbol.
                            </small>
                        </div>

                        <div class="text-center my-4">
                            <label style="color: #102c57;">
                                <input type="checkbox" id="termsCheckbox" required
                                    style="margin-right: 0.5rem;">
                                I agree to the <a href="#" id="termsLink"
                                    style="color: #1e56a0; text-decoration: none; transition: all 0.3s ease;">
                                    terms and conditions
                                </a>
                            </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit"
                                name="registernow"
                                class="btn btn-orange"
                                style="background: linear-gradient(135deg, #012952, #1e56a0); 
                   color: white; 
                   padding: 0.75rem; 
                   border-radius: 10px; 
                   font-weight: 500; 
                   transition: all 0.3s ease;
                   width: 100%;
                   border: none;
                   cursor: pointer;">
                                <b>Register Account</b>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(30, 86, 160, 0.25);
            border-color: #1e56a0;
        }

        .invalid-email123123 {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-password {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(1, 41, 82, 0.3);
        }

        .error-label {
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }
    </style>

    <script>
        function validateEmail(email) {
            var emailInput = document.getElementById('email123123');
            var isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            if (!isValidEmail) {
                emailInput.classList.add('invalid-email123123');
                document.getElementById('emailErrorLabel123123').style.display = 'block';
            } else {
                emailInput.classList.remove('invalid-email123123');
                document.getElementById('emailErrorLabel123123').style.display = 'none';
            }
        }

        function validatePassword() {
            var passwordInput = document.getElementById('password123');
            var confirmPasswordInput = document.getElementById('confirmPassword123');

            var passwordErrorLabel = document.getElementById('passwordErrorLabelqwe');
            var lengthErrorLabel = document.getElementById('lengthErrorLabelqwe');
            var uppercaseErrorLabel = document.getElementById('uppercaseErrorLabelqwe');
            var numberErrorLabel = document.getElementById('numberErrorLabelqwe');
            var symbolErrorLabel = document.getElementById('symbolErrorLabelqwe');

            var password = passwordInput.value;
            var confirmPassword = confirmPasswordInput.value;

            var hasMatch = password === confirmPassword;
            var hasValidLength = password.length >= 8;
            var hasUppercase = /[A-Z]/.test(password);
            var hasNumber = /\d/.test(password);
            var hasSymbol = /[!@#$%^&*()]/.test(password);

            if (hasMatch) {
                passwordInput.classList.remove('invalid-password');
                confirmPasswordInput.classList.remove('invalid-password');
                passwordErrorLabel.style.display = 'none';
            } else {
                passwordInput.classList.add('invalid-password');
                confirmPasswordInput.classList.add('invalid-password');
                passwordErrorLabel.style.display = 'block';
            }

            lengthErrorLabel.style.display = hasValidLength ? 'none' : 'block';
            uppercaseErrorLabel.style.display = hasUppercase ? 'none' : 'block';
            numberErrorLabel.style.display = hasNumber ? 'none' : 'block';
            symbolErrorLabel.style.display = hasSymbol ? 'none' : 'block';
        }

        function validateForm() {
            var isValidPassword = validatePassword();
            var errorLabels = document.querySelectorAll('.error-label');
            for (var i = 0; i < errorLabels.length; i++) {
                if (errorLabels[i].style.display !== 'none') {
                    return false;
                }
            }
            return isValidPassword;
        }
    </script>


</body>

</html>