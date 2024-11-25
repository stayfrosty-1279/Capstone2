<?php
require_once('database_config.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<?php

if (isset($_POST['login'])) {
    $org_id = $_REQUEST['id'];
    $checker = 0;
    $checker2 = 0;
    $_SESSION['loginresult'] = '';
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($DB, "SELECT * FROM users WHERE email = ? AND password = ? AND status = 'Active' AND admin_approval = 'Approved'");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $checkresident = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkresident) > 0) {
        while ($data = mysqli_fetch_assoc($checkresident)) {
            if ($data['org_id'] == $org_id) {
                $checker++;
                $_SESSION['logged'] = 'True';
                $_SESSION['user_id'] = $data['user_id'];

                // Fetch the most up-to-date user data
                $user_stmt = mysqli_prepare($DB, "SELECT * FROM users WHERE user_id = ?");
                mysqli_stmt_bind_param($user_stmt, "i", $data['user_id']);
                mysqli_stmt_execute($user_stmt);
                $user_result = mysqli_stmt_get_result($user_stmt);
                $current_data = mysqli_fetch_assoc($user_result);

                // Set session variables with current data
                $_SESSION['firstName'] = $current_data['firstName'];
                $_SESSION['middleName'] = $current_data['middleName'];
                $_SESSION['lastName'] = $current_data['lastName'];
                $_SESSION['fullname'] = $current_data['firstName'] . ' ' . $current_data['middleName'] . '. ' . $current_data['lastName'];
                $_SESSION['dateOfBirth'] = $current_data['dateOfBirth'];
                $_SESSION['gender'] = $current_data['gender'];
                $_SESSION['address'] = $current_data['address'];
                $_SESSION['contactNumber'] = $current_data['contactNumber'];
                $_SESSION['idImage'] = $current_data['idImage'];
                $_SESSION['email'] = $current_data['email'];
                $_SESSION['password'] = $current_data['password'];
                $_SESSION['dateRegistered'] = $current_data['dateRegistered'];
                $_SESSION['Activation_code'] = $current_data['Activation_code'];
                $_SESSION['Profile_image'] = $current_data['profile_picture'];
                $_SESSION['Position'] = $current_data['Position'];
                $_SESSION['org_id'] = $current_data['org_id'];

                $_SESSION['loginresult'] = '';
                unset($_POST['login']);
                header('Location: index.php');
                exit();
            }
        }
    }

    if ($checker == 0) {
        $checkresident = mysqli_query($DB, "SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
        if (mysqli_num_rows($checkresident) > 0) {
            while ($residentrecord = mysqli_fetch_assoc($checkresident)) {
                if ($residentrecord['org_id'] == $org_id) {
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
                    $_SESSION['rc_signature'] = $residentrecord['rc_signature'];
                    $_SESSION['org_id'] = $residentrecord['org_id'];


                    $_SESSION['loginresult'] = '';
                    unset($_POST['login']);
                    header('Location: admin/dashboard.php');
                    exit();
                } else {
                    $checker2 = 1;
                }
            }
        }
    }


    if ($checker == 0 && $checker2 == 0) {
        unset($_POST['login']);
        $_SESSION['loginresult'] = 'Incorrect credentials, please try again!';
        header('Location: view_organization.php?id=' . $org_id);
        exit();
    }

    if ($checker == 0 && $checker2 == 1) {
        unset($_POST['login']);
        $_SESSION['loginresult'] = 'You can`t user your credentials on this organization`s page.';
        header('Location: view_organization.php?id=' . $org_id);
        exit();
    }
}

if (isset($_POST['Updateprofile'])) {
    $_SESSION['Updateprofile_result'] = '';
    $firstName = $_POST['firstName'];
    $middleName = $_POST['MI'];
    $lastName = $_POST['lastName'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $gender = $_SESSION['gender'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contactas'];
    $passwordchecker = $_POST['password'];
    $password = md5($_POST['password']);
    $emailadd = $_SESSION['email'];

    $path = $_FILES['profileimage']['name'];
    $path_tmp = $_FILES['profileimage']['tmp_name'];

    if ($passwordchecker != '') {
        if ($path != '') {
            try {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = basename($path, '.' . $ext);

                $profile_timestamp = time();
                $profile_image = 'Profile-' . $profile_timestamp . '-' . $file_name . '-.' . $ext;
                move_uploaded_file($path_tmp, 'assets/uploads/' . $profile_image);

                $sql = "UPDATE users SET
                              profile_picture = '$profile_image',
                              firstName = '$firstName',
                              middleName = '$middleName',
                              lastName = '$lastName',
                              dateOfBirth = '$dateOfBirth',
                              gender = '$gender',
                              address = '$address',
                              contactNumber = '$contactNumber',
                              password = '$password'
                            WHERE email = '$emailadd'";

                $query = mysqli_query($DB, $sql);
            } catch (Exception $e) {
                // Catch any exceptions and display the error message
                echo "Error: " . $e->getMessage();
            }
            $_SESSION['firstName'] = $firstName;
            $_SESSION['middleName'] = $middleName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['dateOfBirth'] = $dateOfBirth;
            $_SESSION['gender'] = $gender;
            $_SESSION['address'] = $address;
            $_SESSION['contactNumber'] = $contactNumber;
            $_SESSION['Profile_image'] = $profile_image;
            $_SESSION['password'] = $password;


            unset($_POST['Updateprofile']);
            $_SESSION['Updateprofile_result'] = 'Your profile has been updated successfully!';
            showSweetAlertSucced("Success!", "Your profile has been updated successfully!", "success");
        } else {
            try {
                $sql = "UPDATE users
                        SET
                              firstName = '$firstName',
                              middleName = '$middleName',
                              lastName = '$lastName',
                              dateOfBirth = '$dateOfBirth',
                              gender = '$gender',
                              address = '$address',
                              contactNumber = '$contactNumber',
                              password = '$password'
                            WHERE email = '$emailadd'";

                $query = mysqli_query($DB, $sql);
            } catch (Exception $e) {
                // Catch any exceptions and display the error message
                echo "Error: " . $e->getMessage();
            }


            $_SESSION['firstName'] = $firstName;
            $_SESSION['middleName'] = $middleName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['dateOfBirth'] = $dateOfBirth;
            $_SESSION['gender'] = $gender;
            $_SESSION['address'] = $address;
            $_SESSION['contactNumber'] = $contactNumber;
            $_SESSION['password'] = $password;

            unset($_POST['Updateprofile']);
            $_SESSION['Updateprofile_result'] = 'Your profile has been updated successfully!';
            showSweetAlertSucced("Success!", "Your profile has been updated successfully!", "success");
        }
    } else {
        if ($path != '') {
            try {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = basename($path, '.' . $ext);

                $profile_timestamp = time();
                $profile_image = 'Profile-' . $profile_timestamp . '-' . $file_name . '-.' . $ext;
                move_uploaded_file($path_tmp, 'assets/uploads/' . $profile_image);

                $sql = "UPDATE users SET
                              profile_picture = '$profile_image',
                              firstName = '$firstName',
                              middleName = '$middleName',
                              lastName = '$lastName',
                              dateOfBirth = '$dateOfBirth',
                              gender = '$gender',
                              address = '$address',
                              contactNumber = '$contactNumber'
                            WHERE email = '$emailadd'";

                $query = mysqli_query($DB, $sql);
            } catch (Exception $e) {
                // Catch any exceptions and display the error message
                echo "Error: " . $e->getMessage();
            }
            $_SESSION['firstName'] = $firstName;
            $_SESSION['middleName'] = $middleName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['dateOfBirth'] = $dateOfBirth;
            $_SESSION['gender'] = $gender;
            $_SESSION['address'] = $address;
            $_SESSION['contactNumber'] = $contactNumber;
            $_SESSION['Profile_image'] = $profile_image;


            unset($_POST['Updateprofile']);
            $_SESSION['Updateprofile_result'] = 'Your profile has been updated successfully!';
            showSweetAlertSucced("Success!", "Your profile has been updated successfully!", "success");
        } else {
            try {
                $sql = "UPDATE users SET 
                              firstName = '$firstName',
                              middleName = '$middleName',
                              lastName = '$lastName',
                              dateOfBirth = '$dateOfBirth',
                              gender = '$gender',
                              address = '$address',
                              contactNumber = '$contactNumber'
                            WHERE email = '$emailadd'";

                $query = mysqli_query($DB, $sql);
            } catch (Exception $e) {
                // Catch any exceptions and display the error message
                echo "Error: " . $e->getMessage();
            }

            $_SESSION['firstName'] = $firstName;
            $_SESSION['middleName'] = $middleName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['dateOfBirth'] = $dateOfBirth;
            $_SESSION['gender'] = $gender;
            $_SESSION['address'] = $address;
            $_SESSION['contactNumber'] = $contactNumber;

            unset($_POST['Updateprofile']);
            $_SESSION['Updateprofile_result'] = 'Your profile has been updated successfully!';
            showSweetAlertSucced("Success!", "Your profile has been updated successfully!", "success");
        }
    }
}

if (isset($_POST['submitButton'])) {
    $validation = 1;
    $path = $_FILES['pdffile']['name'];
    $path_tmp = $_FILES['pdffile']['tmp_name'];
    $uploaded_date = date('Y-m-d');

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'pdf') {
            $validation = 0;
            showSweetAlertFailed("Invalid File!", "You must have to upload PDF only!", "error");
        }
    } else {
        $validation = 0;
        showSweetAlertFailed("Invalid File!", "You must have to upload PDF File!", "error");
    }

    if ($validation != 0) {

        $id_timestamp = time();
        $id_image = 'PDF-' . $id_timestamp . '.' . $ext;
        move_uploaded_file($path_tmp, 'Repository/' . $id_image);

        $user_id =  $_SESSION['user_id'];

        $sql = "INSERT INTO pdf_files
                            (
                              user_id,
                              pdf_file,
                              uploaded_date
                            ) 
                            VALUES
                            (
                              ?,
                              ?,
                              ?
                            )";

        $query = mysqli_prepare($DB, $sql);
        mysqli_stmt_bind_param($query, "sss", $user_id, $id_image, $uploaded_date);

        $idImage = $file_name; // Assuming $file_name holds the value for idImage

        if (mysqli_stmt_execute($query)) {
            $lastInsertId = mysqli_insert_id($DB);

            if ($lastInsertId) {
                showSweetAlertSucced("Success!", "Your PDF has been saved successfully!", "success");
            }
        }
    } else {

        showSweetAlertFailed("Upload Failed!", "Something Happened!", "error");
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
<style>
    .modal2 {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content2 {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        border-radius: 10px;
    }

    .close2 {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close2:hover,
    .close2:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<?php if (isset($_SESSION['loginresult']) && $_SESSION['loginresult'] != '') { ?>
    <div class="modal fade auto-open-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <?php } else { ?>
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <?php } ?>
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); position: relative; margin-top: 15%;">
                <!-- Gradient top border -->
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #012952, #1e56a0, #012952);"></div>

                <div class="modal-header border-0 pt-4 px-4">
                    <h2 class="modal-title" id="loginModalLabel" style="color: #102c57; font-weight: 600;">Welcome Back</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <p class="text-muted text-center px-4 mb-4">Sign in to your account</p>

                <?php if (isset($_SESSION['loginresult']) && $_SESSION['loginresult'] != '') { ?>
                    <div class="error-message mx-4 mb-4" style="color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 0.75rem; border-radius: 10px; text-align: center; font-weight: 500;">
                        <?php echo $_SESSION['loginresult']; ?>
                    </div>
                <?php } ?>

                <div class="modal-body px-4">
                    <form action="" method="POST">
                        <div class="form-group mb-4">
                            <div class="input-group" style="border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                <span class="input-group-text" style="background-color: #f8f9fa; border: none; color: #102c57;">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="username" name="username" placeholder="Email"
                                    style="border: none; padding: 0.75rem;">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="input-group" style="border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                <span class="input-group-text" style="background-color: #f8f9fa; border: none; color: #102c57;">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                                    style="border: none; padding: 0.75rem;">
                            </div>
                        </div>

                        <div class="form-check text-center mb-4">
                            <label class="form-check-label" style="color: #102c57;">
                                <input type="checkbox" id="termsCheckbox" required class="form-check-input">
                                I agree to the <a href="#" id="termsLink" style="color: #1e56a0; text-decoration: none;">terms and conditions</a>
                            </label>
                        </div>

                        <div class="d-grid mb-4">
                            <button name="login" type="submit" class="btn"
                                style="background: linear-gradient(135deg, #012952, #1e56a0); color: white; padding: 0.75rem; 
                                   border-radius: 10px; font-weight: 500; transition: all 0.3s ease;">
                                Sign In
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>


        </div>
        <?php if (isset($_SESSION['loginresult']) && $_SESSION['loginresult'] != '') { ?>
            <script>
                // Wait for the page to load
                document.addEventListener("DOMContentLoaded", function() {
                    // Get the modal element
                    var modal = document.querySelector(".auto-open-modal");

                    // Open the modal using Bootstrap's JavaScript API
                    var modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                });
            </script>
        <?php $_SESSION['loginresult'] = '';
        } ?>

        <?php if (isset($_SESSION['Updateprofile_result']) && $_SESSION['Updateprofile_result'] != '') { ?>
            <div class="modal fade auto-open-modal2" id="editProfile">
            <?php } else { ?>
                <div class="modal fade" id="editProfile">
                <?php } ?>
                <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'True') { ?>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Profile</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <?php if (isset($_SESSION['Updateprofile_result']) && $_SESSION['Updateprofile_result'] != '') { ?>
                                <br>
                                <center><b><span style="color:red;"><?php echo $_SESSION['Updateprofile_result']; ?></span></b>
                                    <center>
                                    <?php } ?>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm_UserEditProf1()">
                                            <div class="row accent-orange">
                                                <div class="col-12 col-md-12 col-lg-12 mb-3" style="margin-top:1vh;">
                                                    <label for="ProfilePricture">Change Profile Pricture </label>
                                                    <div class="form-group">
                                                        <input type="file" class="form-control" id="profileimage" name="profileimage">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="firstName">First Name </label>
                                                        <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo $_SESSION['firstName']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="MI">Middle Name/Initials </label>
                                                        <input type="text" name="MI" id="MI" class="form-control" value="<?php echo $_SESSION['middleName']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="lastName">Last Name </label>
                                                        <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo $_SESSION['lastName']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="dateOfbirth">Date Of Birth </label>
                                                        <input readonly type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" value="<?php echo $_SESSION['dateOfBirth']; ?>">
                                                    </div>
                                                </div>
                                                <script>
                                                    // Get the date input field
                                                    var dobInput = document.getElementById('dateOfBirth');

                                                    // Calculate the minimum allowed date
                                                    var today = new Date();
                                                    var minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

                                                    // Format the minimum date as YYYY-MM-DD
                                                    var minDateFormatted = minDate.toISOString().split('T')[0];

                                                    // Set the minimum date attribute of the input field
                                                    dobInput.setAttribute('max', minDateFormatted);

                                                    // Add event listener for date change
                                                    dobInput.addEventListener('input', function() {
                                                        var selectedDate = new Date(this.value);
                                                        if (selectedDate > minDate) {
                                                            this.setCustomValidity('You must be 18 years old or above.');
                                                        } else {
                                                            this.setCustomValidity('');
                                                        }
                                                    });
                                                </script>


                                                <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                    <label for="gender">Gender </label>
                                                    <div class="form-group">

                                                        <div class="form-check-inline py-1">
                                                            <?php if ($_SESSION['gender'] == 'Male') { ?>
                                                                <label class="form-check-label" for="male">
                                                                    Male
                                                                </label>
                                                            <?php } else { ?>
                                                                <label class="form-check-label" for="male">
                                                                    Female
                                                                </label>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="contact">Contact Number </label>
                                                        <input type="number" name="contactas" id="contactas" class="form-control" placeholder="9xxxxxxxxx" value="<?php echo $_SESSION['contactNumber']; ?>">
                                                    </div>
                                                    <label id="contactErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Contact number should start with "9"! Example: 9xxxxxxxxx</label>
                                                </div>


                                                <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="address">Address </label>
                                                        <input type="text" name="address" id="address" class="form-control" value="<?php echo $_SESSION['address']; ?>">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="email">Email </label>
                                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $_SESSION['email']; ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                    <div class="form-group">
                                                        <label for="password">Change Password </label>
                                                        <input type="password" name="password" id="passwordaa" class="form-control" value="" oninput="validatePassword()">
                                                    </div>

                                                    <center>
                                                        <label id="lengthErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must be at least 8 characters long.</label>
                                                        <label id="uppercaseErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must contain at least one uppercase letter.</label>
                                                        <label id="numberErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must contain at least one number.</label>
                                                        <label id="symbolErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must contain at least one symbol.</label>
                                                    </center>
                                                    <script>
                                                        function validatePassword() {
                                                            var passwordInput = document.getElementById('passwordaa');

                                                            if (passwordInput.value != null) {
                                                                var lengthErrorLabel = document.getElementById('lengthErrorLabel');
                                                                var uppercaseErrorLabel = document.getElementById('uppercaseErrorLabel');
                                                                var numberErrorLabel = document.getElementById('numberErrorLabel');
                                                                var symbolErrorLabel = document.getElementById('symbolErrorLabel');
                                                                var password = passwordInput.value;
                                                                var hasValidLength = password.length >= 8;
                                                                var hasUppercase = /[A-Z]/.test(password);
                                                                var hasNumber = /\d/.test(password);
                                                                var hasSymbol = /[!@#$%^&*()]/.test(password);
                                                                lengthErrorLabel.style.display = hasValidLength ? 'none' : 'block';
                                                                uppercaseErrorLabel.style.display = hasUppercase ? 'none' : 'block';
                                                                numberErrorLabel.style.display = hasNumber ? 'none' : 'block';
                                                                symbolErrorLabel.style.display = hasSymbol ? 'none' : 'block';
                                                            } else {

                                                                lengthErrorLabel.style.display = 'none';
                                                                uppercaseErrorLabel.style.display = 'none';
                                                                numberErrorLabel.style.display = 'none';
                                                                symbolErrorLabel.style.display = 'none';
                                                            }
                                                        }
                                                    </script>
                                                </div>
                                            </div>

                                    </div>
                                    <div class="modal-footer justify-content-end">
                                        <button name="Updateprofile" id="Updateprofile" class="btn btn-md btn-secondary btn-orange" type="submit" style="background-color:#0d6efd;">Update Profile</button>
                                    </div>

                                    <script>
                                        function validateForm_UserEditProf1() {
                                            // Check if any error labels are visible
                                            var errorLabels_UserEditProf1 = document.querySelectorAll('.error-label-UserEditProf1');
                                            for (var i = 0; i < errorLabels_UserEditProf1.length; i++) {
                                                if (errorLabels_UserEditProf1[i].style.display !== 'none') {
                                                    // If any error label is still visible, prevent form submission
                                                    return false;
                                                }
                                            }
                                        }
                                    </script>
                                    </form>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Profile</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <?php if (isset($_SESSION['Updateprofile_result']) && $_SESSION['Updateprofile_result'] != '') { ?>
                            <br>
                            <center><b><span style="color:red;"><?php echo $_SESSION['Updateprofile_result']; ?></span></b>
                                <center>
                                <?php } ?>
                                <div class="modal-body">
                                    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm_UserEditProf1()">
                                        <div class="row accent-orange">
                                            <div class="col-12 col-md-12 col-lg-12 mb-3" style="margin-top:1vh;">
                                                <label for="ProfilePricture">Change Profile Pricture </label>
                                                <div class="form-group">
                                                    <input type="file" class="form-control" id="profileimage" name="profileimage">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="firstName">First Name </label>
                                                    <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo $_SESSION['firstName']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="MI">Middle Name/Initials </label>
                                                    <input type="text" name="MI" id="MI" class="form-control" value="<?php echo $_SESSION['middleName']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="lastName">Last Name </label>
                                                    <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo $_SESSION['lastName']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="dateOfbirth">Date Of Birth </label>
                                                    <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" value="<?php echo $_SESSION['dateOfBirth']; ?>">
                                                </div>
                                            </div>
                                            <script>
                                                // Get the date input field
                                                var dobInput = document.getElementById('dateOfBirth');

                                                // Calculate the minimum allowed date
                                                var today = new Date();
                                                var minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

                                                // Format the minimum date as YYYY-MM-DD
                                                var minDateFormatted = minDate.toISOString().split('T')[0];

                                                // Set the minimum date attribute of the input field
                                                dobInput.setAttribute('max', minDateFormatted);

                                                // Add event listener for date change
                                                dobInput.addEventListener('input', function() {
                                                    var selectedDate = new Date(this.value);
                                                    if (selectedDate > minDate) {
                                                        this.setCustomValidity('You must be 18 years old or above.');
                                                    } else {
                                                        this.setCustomValidity('');
                                                    }
                                                });
                                            </script>


                                            <div class="col-12 col-md-6 col-lg-6" style="margin-top:1vh;">
                                                <label for="gender">Gender </label>
                                                <div class="form-group">

                                                    <div class="form-check-inline py-1">
                                                        <?php if ($_SESSION['gender'] == 'Male') { ?>
                                                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male"
                                                                name="gender" checked>
                                                        <?php } else { ?>
                                                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male"
                                                                name="gender">
                                                        <?php } ?>
                                                        <label class="form-check-label" for="male">
                                                            Male
                                                        </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <?php if ($_SESSION['gender'] == 'Female') { ?>
                                                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female"
                                                                name="gender" checked>
                                                        <?php } else { ?>
                                                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female"
                                                                name="gender">
                                                        <?php } ?>
                                                        <label class="form-check-label" for="female">
                                                            Female
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="contact">Contact Number </label>
                                                    <input type="number" name="contactas" id="contactas" class="form-control" placeholder="9xxxxxxxxx" value="<?php echo $_SESSION['contactas']; ?>">
                                                </div>
                                            </div>


                                            <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="address">Address </label>
                                                    <input type="text" name="address" id="address" class="form-control" value="<?php echo $_SESSION['address']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="email">Email </label>
                                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $_SESSION['email']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12" style="margin-top:1vh;">
                                                <div class="form-group">
                                                    <label for="password">Change Password </label>
                                                    <input type="password" name="password" id="passwordaa" class="form-control" value="" oninput="validatePassword()">
                                                </div>

                                                <center>
                                                    <label id="lengthErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must be at least 8 characters long.</label>
                                                    <label id="uppercaseErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must contain at least one uppercase letter.</label>
                                                    <label id="numberErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must contain at least one number.</label>
                                                    <label id="symbolErrorLabel" class="error-label-UserEditProf1" style="color: red; display: none;">Password must contain at least one symbol.</label>
                                                </center>
                                                <script>
                                                    function validatePassword() {
                                                        var passwordInput = document.getElementById('passwordaa');

                                                        if (passwordInput.value != null) {
                                                            var lengthErrorLabel = document.getElementById('lengthErrorLabel');
                                                            var uppercaseErrorLabel = document.getElementById('uppercaseErrorLabel');
                                                            var numberErrorLabel = document.getElementById('numberErrorLabel');
                                                            var symbolErrorLabel = document.getElementById('symbolErrorLabel');
                                                            var password = passwordInput.value;
                                                            var hasValidLength = password.length >= 8;
                                                            var hasUppercase = /[A-Z]/.test(password);
                                                            var hasNumber = /\d/.test(password);
                                                            var hasSymbol = /[!@#$%^&*()]/.test(password);
                                                            lengthErrorLabel.style.display = hasValidLength ? 'none' : 'block';
                                                            uppercaseErrorLabel.style.display = hasUppercase ? 'none' : 'block';
                                                            numberErrorLabel.style.display = hasNumber ? 'none' : 'block';
                                                            symbolErrorLabel.style.display = hasSymbol ? 'none' : 'block';
                                                        } else {

                                                            lengthErrorLabel.style.display = 'none';
                                                            uppercaseErrorLabel.style.display = 'none';
                                                            numberErrorLabel.style.display = 'none';
                                                            symbolErrorLabel.style.display = 'none';
                                                        }
                                                    }
                                                </script>
                                            </div>
                                        </div>

                                </div>
                                <div class="modal-footer justify-content-end">
                                    <button name="Updateprofile" id="Updateprofile" class="btn btn-md btn-secondary btn-orange" type="submit" style="background-color:#0d6efd;">Update Profile</button>
                                </div>

                                <script>
                                    function validateForm_UserEditProf1() {
                                        // Check if any error labels are visible
                                        var errorLabels_UserEditProf1 = document.querySelectorAll('.error-label-UserEditProf1');
                                        for (var i = 0; i < errorLabels_UserEditProf1.length; i++) {
                                            if (errorLabels_UserEditProf1[i].style.display !== 'none') {
                                                // If any error label is still visible, prevent form submission
                                                return false;
                                            }
                                        }
                                    }
                                </script>
                                </form>
                    </div>
                </div>
            </div>
        <?php } ?>


        <?php if (isset($_SESSION['Updateprofile_result']) && $_SESSION['Updateprofile_result'] != '') { ?>
            <script>
                // Wait for the page to load
                document.addEventListener("DOMContentLoaded", function() {
                    // Get the modal element
                    var modal = document.querySelector(".auto-open-modal2");

                    // Open the modal using Bootstrap's JavaScript API
                    var modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                });
            </script>
        <?php $_SESSION['Updateprofile_result'] = '';
        } ?>