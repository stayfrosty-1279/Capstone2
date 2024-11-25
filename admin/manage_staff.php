<?php
include('includes/header.php');

if (isset($_POST['deletestaff'])) {

    $USER_IDS = $_POST['staff_ID_Delete'];

    $query = "DELETE FROM admin WHERE admin_ID = '$USER_IDS'";
    $result = mysqli_query($DB, $query);

    if ($result) {
        unset($_POST['staff_ID_Delete']);
        unset($_POST['deletestaff']);
        showSweetAlertSucced2("Success!", "Employee has been deleted successfully.", "success");
    } else {
        unset($_POST['staff_ID_Delete']);
        unset($_POST['deletestaff']);
        $error = "Error deleting user: " . mysqli_error($DB);
        showSweetAlertFailed2("Failed!", $error, "error");
    }
}

if (isset($_POST['updatestaffInfo'])) {

    $validation = 1;
    $userID = $_POST['staff_IDupdate'];
    $checkpass = $_POST['password']; // password
    $path = $_FILES['customFile']['name'];
    $path_tmp = $_FILES['customFile']['tmp_name'];
    $firstName = $_POST['respondent_firstname'];
    $middleName = $_POST['respondent_middlename'];
    $lastName = $_POST['respondent_lastname'];
    $password = md5($_POST['password']);
    $fullanme = $firstName . ' ' . $middleName . ' ' . $lastName;
    $position = 'Super Admin';


    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'JPEG' && $ext != 'JPG' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $validation = 0;
            showSweetAlertFailed2("Failed!", "You must have to upload jpg, jpeg, gif or png file of profile image", "error");
        }
    }

    if ($validation == 1) {
        if ($checkpass != '') {
            if ($path != '') {

                $profile_timestamp = time();
                $profile_image = 'profile-' . $profile_timestamp . '-' . $file_name . '-.' . $ext;
                move_uploaded_file($path_tmp, '../assets/uploads/' . $profile_image);

                $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    role = '$position',
                    password = '$password',
                    profileImage = '$profile_image'
                    WHERE 
                    admin_ID = $userID";
                $result = mysqli_query($DB, $query);

                if ($result) {
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertSucced2("Success!", "Admin account information has been updated successfully.", "success");
                } else {
                    $error = "Error updating admin account information: " . mysqli_error($DB);
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertFailed2("Failed!", $error, "error");
                }
            } else {

                $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    role = '$position',
                    password = '$password'
                    WHERE 
                    admin_ID = $userID";
                $result = mysqli_query($DB, $query);

                if ($result) {
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertSucced2("Success!", "Admin account information has been updated successfully.", "success");
                } else {

                    $error = "Error updating admin account information: " . mysqli_error($DB);
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertFailed2("Failed!", $error, "error");
                }
            }
        } else {
            if ($path != '') {

                $profile_timestamp = time();
                $profile_image = 'profile-' . $profile_timestamp . '-' . $file_name . '-.' . $ext;
                move_uploaded_file($path_tmp, '../assets/uploads/' . $profile_image);

                $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    role = '$position',
                    profileImage = '$profile_image'
                    WHERE 
                    admin_ID = $userID";
                $result = mysqli_query($DB, $query);

                if ($result) {
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertSucced2("Success!", "Employee account information has been updated successfully.", "success");
                } else {

                    $error = "Error updating Employee account information: " . mysqli_error($DB);
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertFailed2("Failed!", $error, "error");
                }
            } else {
                $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    role = '$position'
                    WHERE 
                    admin_ID = $userID";
                $result = mysqli_query($DB, $query);

                if ($result) {
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertSucced2("Success!", "Employee account information has been updated successfully.", "success");
                } else {
                    $error = "Error updating Employee account information: " . mysqli_error($DB);
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertFailed2("Failed!", $error, "error");
                }
            }
        }
    }
}




if (isset($_POST['addstaffer'])) {
    $validation = 1;
    $addrespondent_firstname = $_POST['addrespondent_firstname'];
    $addrespondent_middlename = $_POST['addrespondent_middlename'];
    $addrespondent_lastname = $_POST['addrespondent_lastname'];
    $addaddress = '';
    $addcontact = '';
    $addemail = $_POST['addemail'];
    $addpassword = md5($_POST['addpassword']);
    $addposition = 'Super Admin';
    $adminID = $_SESSION['user_id']; //id of admin who added.

    $path = $_FILES['addcustomFile']['name'];
    $path_tmp = $_FILES['addcustomFile']['tmp_name'];

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'JPEG' && $ext != 'JPG' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $validation = 0;
            showSweetAlertFailed2("Failed!", "You must have to upload jpg, jpeg, or png file for admin profile", "error");
        }
    }

    if ($validation == 1) {
        $profile_timestamp = time();
        $profile_image = 'profile-' . $profile_timestamp . '-' . $file_name . '-.' . $ext;
        move_uploaded_file($path_tmp, '../assets/uploads/' . $profile_image);

        $query = "INSERT INTO admin (
                firstName, 
                middleName, 
                lastName, 
                role, 
                username,
                password, 
                profileImage
            )
            VALUES 
            (
                '$addrespondent_firstname', 
                '$addrespondent_middlename', 
                '$addrespondent_lastname', 
                '$addposition', 
                '$addemail',
                '$addpassword',
                '$profile_image'
                )";

        $result = mysqli_query($DB, $query);

        if ($result) {
            unset($_POST['addstaffer']);
            showSweetAlertSucced2("Success!", "New Administrator has been added successfully.", "success");
        } else {
            $error = "Error saving new admin account: " . mysqli_error($DB);
            unset($_POST['addstaffer']);
            showSweetAlertFailed2("Failed!", $error, "error");
        }
    }
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
                  window.location.href = 'manage_staff.php';
                });
            }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
            </script>";
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
                      window.location.href = 'manage_staff.php';
                    });
                }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
                </script>";
}

include('includes/topbar.php');
include('includes/sidebar.php');
include('includes/modals.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper accent-orange">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Administrators</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 accent-orange">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Manage Administrators</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-left">Administrators List </h4>
                    <div class="btn-group float-right">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStaff_user"><i class="fas fa-plus"></i> Add Administrator</button>
                    </div>
                </div>

                <div class="card-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name </th>
                                <th>Email Address</th>
                                <th>Role </th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $i = 0;
                            $residentqeury = mysqli_query($DB, "SELECT * FROM admin WHERE role LIKE '%Admin%'");
                            if (mysqli_num_rows($residentqeury) > 0) {
                                while ($row = mysqli_fetch_assoc($residentqeury)) {
                                    $i++;
                                    $staff_ID = $row['admin_ID'];
                            ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['role']; ?></td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <button class="btn btn-success" data-toggle="modal" data-target="#editStaff<?php echo $staff_ID; ?>"><i
                                                        class="fas fa-edit"></i></button>

                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteStaffs<?php echo $staff_ID; ?>"> <span class="d-lg-block d-lg-none d-xl-none">Delete</span><i class="fas fa-trash"></i> </button>

                                                <form action="" method="post">
                                                    <div class="modal fade" id="deleteStaffs<?php echo $staff_ID; ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Deleting Administrator Account</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete this Administrator Account?</p>
                                                                </div>
                                                                <div class="modal-footer justify-content-end">
                                                                    <input type="hidden" name="staff_ID_Delete" id="staff_ID_Delete" value="<?php echo $staff_ID; ?>">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger" id="deletestaff" name="deletestaff">Confirm</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>



                                                <div class="modal fade" id="editStaff<?php echo $staff_ID; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm<?php echo $i; ?>()">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Edit Administrator</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row accent-orange">

                                                                        <input type="hidden" name="staff_IDupdate" id="staff_IDupdate" value="<?php echo $staff_ID; ?>">
                                                                        <?php if ($row['profileImage'] != '') { ?>
                                                                            <div class="col-12 col-md-12 col-lg-12">
                                                                                <div class="form-group">
                                                                                    <center>
                                                                                        <img src="../assets/uploads/<?php echo $row['profileImage']; ?>" style=" width:220px; height:220px; border-radius:50%;">
                                                                                    </center>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>

                                                                        <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="customFile" name="customFile">
                                                                                <?php if ($row['profileImage'] == '') { ?>
                                                                                    <label class="custom-file-label" for="customFile">Choose Profile Picture</label>
                                                                                <?php } else { ?>
                                                                                    <label class="custom-file-label" for="customFile">Change Profile Picture</label>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="respondent_firstname">First Name </label>
                                                                                <input type="text" name="respondent_firstname" id="respondent_firstname"
                                                                                    class="form-control" value="<?php echo $row['firstName']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="respondent_middlename">Middle Name/Initials </label>
                                                                                <input type="text" name="respondent_middlename" id="respondent_middlename"
                                                                                    class="form-control" value="<?php echo $row['middleName']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label for="respondent_lastname">Last Name </label>
                                                                                <input type="text" name="respondent_lastname" id="respondent_lastname" class="form-control"
                                                                                    value="<?php echo $row['lastName']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="email">Email Address </label>
                                                                                <input type="text" name="email" id="email" class="form-control" value="<?php echo $row['username']; ?>" disabled>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 col-md-6 col-lg-6">
                                                                            <div class="form-group">
                                                                                <label for="password">Change Password </label>
                                                                                <input type="password" name="password" id="password<?php echo $row['username']; ?>" class="form-control" oninput="validatePassword<?php echo $row['username']; ?>()">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 col-md-12 col-lg-12">

                                                                            <center>
                                                                                <label id="lengthErrorLabel<?php echo $row['username']; ?>" class="error-label<?php echo $i; ?>" style="color: red; display: none;">Password must be at least 8 characters long.</label>
                                                                                <label id="uppercaseErrorLabel<?php echo $row['username']; ?>" class="error-label<?php echo $i; ?>" style="color: red; display: none;">Password must contain at least one uppercase letter.</label>
                                                                                <label id="numberErrorLabel<?php echo $row['username']; ?>" class="error-label<?php echo $i; ?>" style="color: red; display: none;">Password must contain at least one number.</label>
                                                                                <label id="symbolErrorLabel<?php echo $row['username']; ?>" class="error-label<?php echo $i; ?>" style="color: red; display: none;">Password must contain at least one symbol.</label>
                                                                            </center>
                                                                            <script>
                                                                                function validatePassword<?php echo $row['username']; ?>() {
                                                                                    var passwordInput = document.getElementById('password<?php echo $row['username']; ?>');

                                                                                    var lengthErrorLabel = document.getElementById('lengthErrorLabel<?php echo $row['username']; ?>');
                                                                                    var uppercaseErrorLabel = document.getElementById('uppercaseErrorLabel<?php echo $row['username']; ?>');
                                                                                    var numberErrorLabel = document.getElementById('numberErrorLabel<?php echo $row['username']; ?>');
                                                                                    var symbolErrorLabel = document.getElementById('symbolErrorLabel<?php echo $row['username']; ?>');
                                                                                    var password = passwordInput.value;
                                                                                    var hasValidLength = password.length >= 8;
                                                                                    var hasUppercase = /[A-Z]/.test(password);
                                                                                    var hasNumber = /\d/.test(password);
                                                                                    var hasSymbol = /[!@#$%^&*()]/.test(password);
                                                                                    lengthErrorLabel.style.display = hasValidLength ? 'none' : 'block';
                                                                                    uppercaseErrorLabel.style.display = hasUppercase ? 'none' : 'block';
                                                                                    numberErrorLabel.style.display = hasNumber ? 'none' : 'block';
                                                                                    symbolErrorLabel.style.display = hasSymbol ? 'none' : 'block';
                                                                                }
                                                                            </script>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer justify-content-end">
                                                                        <button type="submit" class="btn btn-success" name="updatestaffInfo" id="updatestaffInfo">Update <i class="fas fa-edit"></i></button>
                                                                    </div>
                                                            </form>
                                                            <script>
                                                                function validateForm<?php echo $i; ?>() {
                                                                    // Check if the error label is visible
                                                                    var contactErrorLabel = document.getElementById('contactErrorLabel<?php echo $row['username']; ?>');
                                                                    if (contactErrorLabel.style.display !== 'none') {
                                                                        // If the error label is still visible, prevent form submission
                                                                        return false;
                                                                    }

                                                                    var errorLabelspassword = document.querySelectorAll('.error-label<?php echo $i; ?>');
                                                                    for (var i = 0; i < errorLabelspassword.length; i++) {
                                                                        if (errorLabelspassword[i].style.display !== 'none') {
                                                                            // If any error label is still visible, prevent form submission
                                                                            return false;
                                                                        }
                                                                    }
                                                                }
                                                            </script>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!--STAFF-->

<div class="modal fade" id="addStaff_user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Administrator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateFormmanagestaff()">
                    <div class="row accent-orange">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="respondent_firstname">First Name </label>
                                <input type="text" name="addrespondent_firstname" id="addrespondent_firstname" class="form-control" placeholder="First Name" value="" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="respondent_middlename">Middle Name/Initials </label>
                                <input type="text" name="addrespondent_middlename" id="addrespondent_middlename" class="form-control" placeholder="Middle Name" value="" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="respondent_lastname">Last Name </label>
                                <input type="text" name="addrespondent_lastname" id="addrespondent_lastname" class="form-control" placeholder="Last Name" value="" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12" style="display:none;">
                            <div class="form-group">
                                <label for="respondent_lastname">Address </label>
                                <input type="text" name="addaddress" id="addaddress" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12" style="display:none;">
                            <div class="form-group">
                                <label for="contact">Contact Number </label>
                                <input type="number" name="addcontact" id="addcontact" class="form-control" placeholder="9xxxxxxxxx">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12" style="display:none;">
                            <div class="form-group">
                                <label for="accessLevel">Role</label>
                                <select class="form-control select2" name="role_selected_add" id="role_selected_add" data-dropdown-css-class="select2-orange"
                                    style="width: 100%">
                                    <option value="Admin">Administrator</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-12 col-md-12 col-lg-12 mb-3">
                            <label for="ProfilePricture">Profile Pricture </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="addcustomFile" name="addcustomFile" required>
                                <label class="custom-file-label" for="customFile">Choose Profile Picture</label>
                            </div>
                        </div>


                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="email">Email Address </label>
                                <input type="text" name="addemail" id="addemail" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="password">Password </label>
                                <input type="password" name="addpassword" id="addpassword123" class="form-control" value="" required oninput="validatePassword()">
                            </div>
                            <center>
                                <label id="lengthErrorLabel33" class="error-labelmanagestaff" style="color: red; display: none;">Password must be at least 8 characters long.</label>
                                <label id="uppercaseErrorLabel33" class="error-labelmanagestaff" style="color: red; display: none;">Password must contain at least one uppercase letter.</label>
                                <label id="numberErrorLabel33" class="error-labelmanagestaff" style="color: red; display: none;">Password must contain at least one number.</label>
                                <label id="symbolErrorLabel33" class="error-labelmanagestaff" style="color: red; display: none;">Password must contain at least one symbol.</label>
                            </center>

                            <script>
                                function validatePassword() {
                                    var passwordInput = document.getElementById('addpassword123');

                                    var lengthErrorLabel = document.getElementById('lengthErrorLabel33');
                                    var uppercaseErrorLabel = document.getElementById('uppercaseErrorLabel33');
                                    var numberErrorLabel = document.getElementById('numberErrorLabel33');
                                    var symbolErrorLabel = document.getElementById('symbolErrorLabel33');

                                    var password = passwordInput.value;

                                    var hasValidLength = password.length >= 8;
                                    var hasUppercase = /[A-Z]/.test(password);
                                    var hasNumber = /\d/.test(password);
                                    var hasSymbol = /[!@#$%^&*()]/.test(password);

                                    lengthErrorLabel.style.display = hasValidLength ? 'none' : 'block';
                                    uppercaseErrorLabel.style.display = hasUppercase ? 'none' : 'block';
                                    numberErrorLabel.style.display = hasNumber ? 'none' : 'block';
                                    symbolErrorLabel.style.display = hasSymbol ? 'none' : 'block';
                                }
                            </script>
                        </div>

                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="submit" name="addstaffer" id="addstaffer" class="btn btn-success"><span>Create Admin </span><i class="fas fa-plus"></i></button>
                    </div>


                    <script>
                        function validateFormmanagestaff() {

                            // Check if any error labels are visible
                            var errorLabelsmanagestaff = document.querySelectorAll('.error-labelmanagestaff');
                            for (var i = 0; i < errorLabelsmanagestaff.length; i++) {
                                if (errorLabelsmanagestaff[i].style.display !== 'none') {
                                    // If any error label is still visible, prevent form submission
                                    return false;
                                }
                            }

                        }
                    </script>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php
   include('includes/footer.php');
   ?>