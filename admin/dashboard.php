<?php
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('includes/modals.php');
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php

if (isset($_POST['updatestaffInfo'])) {

    $validation = 1;
    $userID = $_POST['staff_IDupdate'];
    $path = $_FILES['customFile']['name'];
    $path_tmp = $_FILES['customFile']['tmp_name'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contact'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['newPassword'];

    $checkpass = $password;

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'JPEG' && $ext != 'JPG' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $validation = 0;
            showSweetAlertFailed2("Failed!", "You must have to upload jpg, jpeg, gif or png file of profile image", "error");
        }
    }

    if ($password != $confirmpassword) {
        $validation == 0;
        showSweetAlertFailed2("Failed!", "Confirm password does not matched!", "error");
    }

    if ($validation == 1) {
        $password = md5($_POST['password']);

        if ($checkpass != '') {
            if ($path != '') {

                $profile_timestamp = time();
                $profile_image = 'profile-' . $profile_timestamp . '-' . $file_name . '-.' . $ext;
                move_uploaded_file($path_tmp, '../assets/uploads/' . $profile_image);

                $query = "UPDATE admin SET 
                    address = '$address',
                    contact = '$contactNumber',
                    password = '$password',
                    profileImage = '$profile_image'
                    WHERE 
                    admin_ID = $userID";
                $result = mysqli_query($DB, $query);

                if ($result) {
                    unset($_POST['updatestaffInfo']);

                    $_SESSION['password'] = $password;
                    $_SESSION['Profile_image'] = $profile_image;

                    showSweetAlertSucced2("Success!", "Information has been updated successfully!", "success");
                } else {
                    $error = "Error updating employee account information: " . mysqli_error($DB);
                    unset($_POST['updatestaffInfo']);
                    showSweetAlertFailed2("Failed!", $error, "error");
                }
            }
        }
    }
}

?>
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
                     window.location.href = 'dashboard.php';
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
                  window.location.href = 'dashboard.php';
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
                      window.location.href = 'dashboard.php';
                    });
                }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
                </script>";
}
?>

<?php if ($_SESSION['priviledges'] == 'Coordinator' && $_SESSION['Profile_image'] == '') { ?>

    <?php
    $my_adminid = $_SESSION['user_id'];
    $residentQuery = mysqli_query($DB, "SELECT * FROM admin WHERE admin_ID = '$my_adminid'");
    if (mysqli_num_rows($residentQuery) > 0) {
        while ($row = mysqli_fetch_assoc($residentQuery)) {
            $staff_ID = $row['admin_ID'];
    ?>
            <!-- Modal -->
            <div class="modal fade" id="editStaff<?php echo $staff_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm<?php echo $staff_ID; ?>()">
                            <div class="modal-header">
                                <h4 class="modal-title">Fill Up Information</h4>
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
                                        <label for="respondent_lastname">Profile Picture</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="customFile">
                                            <?php if ($row['profileImage'] == '') { ?>
                                                <label class="custom-file-label" for="customFile">Choose Profile Picture</label>
                                            <?php } else { ?>
                                                <label class="custom-file-label" for="customFile">Change Profile Picture</label>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="respondent_lastname">Address</label>
                                            <input type="text" name="address" id="address" class="form-control" value="<?php echo $row['address']; ?>">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="contact">Contact Number</label>
                                            <input type="text" name="contact" id="contact" class="form-control" placeholder="9xxxxxxxxx" oninput="validateContactNumber<?php echo $row['username']; ?>(this)" value="<?php echo $row['contact']; ?>">
                                        </div>
                                        <label id="contactErrorLabel<?php echo $row['username']; ?>" style="color: red; display: none;">Contact number should start with "9"! Example: 9xxxxxxxxx</label>
                                    </div>

                                    <script>
                                        function validateContactNumber<?php echo $row['username']; ?>(input) {
                                            var inputValue = input.value;
                                            var numericValue = inputValue.replace(/\D/g, '');

                                            if (!numericValue.startsWith('9') || numericValue.length !== 10) {
                                                document.getElementById('contactErrorLabel<?php echo $row['username']; ?>').style.display = 'block';
                                            } else {
                                                var formattedValue = numericValue;
                                                input.value = formattedValue;
                                                document.getElementById('contactErrorLabel<?php echo $row['username']; ?>').style.display = 'none';
                                            }
                                        }
                                    </script>
                                    <!-- Password Inputs -->
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password" name="password" id="password" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <label for="newPassword">Confirm Password</label>
                                            <input type="password" name="newPassword" id="newPassword" class="form-control" required oninput="validateNewPassword(this)">
                                            <label id="passwordErrorLabel" style="color: red; display: none;">Password must be at least 8 characters long, contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="submit" class="btn btn-success" name="updatestaffInfo" id="updatestaffInfo">Update <i class="fas fa-edit"></i></button>
                            </div>
                        </form>
                        <script>
                            function validateNewPassword(input) {
                                var password = input.value;
                                var errorLabel = document.getElementById('passwordErrorLabel');

                                // Regular expression to check password strength
                                var strongPasswordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                                if (!strongPasswordPattern.test(password)) {
                                    errorLabel.style.display = 'block';
                                } else {
                                    errorLabel.style.display = 'none';
                                }
                            }

                            function validateForm<?php echo $staff_ID; ?>() {
                                var contactErrorLabel = document.getElementById('contactErrorLabel<?php echo $row['username']; ?>');
                                if (contactErrorLabel.style.display !== 'none') {
                                    return false;
                                }
                                var passwordErrorLabel = document.getElementById('passwordErrorLabel');
                                if (passwordErrorLabel.style.display !== 'none') {
                                    return false;
                                }
                                var errorLabelspassword = document.querySelectorAll('.error-label<?php echo $staff_ID; ?>');
                                for (var i = 0; i < errorLabelspassword.length; i++) {
                                    if (errorLabelspassword[i].style.display !== 'none') {
                                        return false;
                                    }
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>

            <script>
                // Open the modal when the page loads if the condition is met
                $(document).ready(function() {
                    $('#editStaff<?php echo $staff_ID; ?>').modal('show');
                });
            </script>
    <?php
        }
    }
    ?>
<?php } ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6 accent-orange">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php" style="color:green;">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <hr>
            <div class="row">
                <?php if ($_SESSION['priviledges'] == 'Super Admin') { ?>
                    <?php

                    if ($_SESSION['priviledges'] == 'Super Admin') {
                        $userscountactive = 0;
                        $residentqeury = mysqli_query($DB, "SELECT * FROM users WHERE Status = 'Active'");
                        if (mysqli_num_rows($residentqeury) > 0) {
                            while ($row = mysqli_fetch_assoc($residentqeury)) {
                                $userscountactive++;
                            }
                        }

                        $userscountinactive = 0;
                        $residentqeury = mysqli_query($DB, "SELECT * FROM users WHERE Status = 'Inactive'");
                        if (mysqli_num_rows($residentqeury) > 0) {
                            while ($row = mysqli_fetch_assoc($residentqeury)) {
                                $userscountinactive++;
                            }
                        }

                        $total_students = $userscountactive + $userscountinactive;
                    }

                    $a1 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM organization");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a1++;
                        }
                    }

                    $a2 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM organization WHERE org_status = 'Active'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a2++;
                        }
                    }


                    $a3 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM organization WHERE org_status = 'Inactive'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a3++;
                        }
                    }
                    ?>
                    <div class="col-lg-12 col-12">
                        <h1>Organizations</h1>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-12">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#ADD8E6 !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $a1; ?></h3>
                                <p class="text-black">Total Organizations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add text-black"></i>
                            </div>
                            <a href="organization.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:Lightgreen !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $a2; ?></h3>
                                <p class="text-black">Active Organizations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add text-black"></i>
                            </div>
                            <a href="organization.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#FF7F7F !important;">
                            <div class="inner">
                                <h3 class="text-white"><?php echo $a3; ?></h3>
                                <p class="text-white">Inactive Organizations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person text-white"></i>
                            </div>
                            <a href="organization.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-12 col-12">
                        <h1>Officers</h1>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-12">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#ADD8E6 !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $total_students; ?></h3>
                                <p class="text-black">Total Officers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add text-black"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:Lightgreen !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $userscountactive; ?></h3>
                                <p class="text-black">Active Officers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add text-black"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#FF7F7F !important;">
                            <div class="inner">
                                <h3 class="text-white"><?php echo $userscountinactive; ?></h3>
                                <p class="text-white">Inactive Officers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person text-white"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                <?php } ?>

                <?php if ($_SESSION['priviledges'] == 'Coordinator') { ?>
                    <?php
                    $org_id = $_SESSION['org_id'];

                    $userscountactive = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM users WHERE Status = 'Active' AND org_id = '$org_id'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $userscountactive++;
                        }
                    }

                    $userscountinactive = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM users WHERE Status = 'Inactive'  AND org_id = '$org_id'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $userscountinactive++;
                        }
                    }

                    $total_students = $userscountactive + $userscountinactive;


                    $a1 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_org_id = '$org_id'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a1++;
                        }
                    }

                    $a2 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_status = 'Ongoing' AND ev_org_id = '$org_id'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a2++;
                        }
                    }


                    $a3 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_status = 'Completed' AND ev_org_id = '$org_id'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a3++;
                        }
                    }
                    $a4 = 0;
                    $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_status = 'Cancelled' AND ev_org_id = '$org_id'");
                    if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
                            $a4++;
                        }
                    }
                    ?>
                    <div class="col-lg-12 col-12">
                        <h1>Organization Events</h1>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-12">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#ADD8E6 !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $a1; ?></h3>
                                <p class="text-black">Total Events</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar text-black"></i>
                            </div>
                            <a href="events.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:Lightgreen !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $a2; ?></h3>
                                <p class="text-black">Ongoing Events</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar text-black"></i>
                            </div>
                            <a href="events.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:Yellow !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $a3; ?></h3>
                                <p class="text-black">Completed Events</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar text-black"></i>
                            </div>
                            <a href="events.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#FF7F7F !important;">
                            <div class="inner">
                                <h3 class="text-white"><?php echo $a4; ?></h3>
                                <p class="text-white">Cancelled Events</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar text-white"></i>
                            </div>
                            <a href="events.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-12 col-12">
                        <h1>Organization Officers</h1>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-12">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#ADD8E6 !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $total_students; ?></h3>
                                <p class="text-black">Total Officers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add text-black"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:Lightgreen !important;">
                            <div class="inner">
                                <h3 class="text-black"><?php echo $userscountactive; ?></h3>
                                <p class="text-black">Active Officers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add text-black"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-orange" style="background-color:#FF7F7F !important;">
                            <div class="inner">
                                <h3 class="text-white"><?php echo $userscountinactive; ?></h3>
                                <p class="text-white">Inactive Officers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person text-white"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">View Details <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                <?php } ?>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<?php
   include('includes/footer.php');
   ?>