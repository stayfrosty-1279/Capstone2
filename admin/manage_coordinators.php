<?php
include('includes/header.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
    $mail = new PHPMailer();

function generateRandomPassword($length = 8) {
    // Define the character sets
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';
    $symbols = '!@#$%^&*()-_=+<>?';
    
    // Ensure the password has at least one character from each set
    $password = '';
    $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
    $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
    $password .= $numbers[rand(0, strlen($numbers) - 1)];
    $password .= $symbols[rand(0, strlen($symbols) - 1)];
    
    // Fill the rest of the password length with random characters
    $allCharacters = $uppercase . $lowercase . $numbers . $symbols;
    for ($i = 4; $i < $length; $i++) {
        $password .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
    }
    
    // Shuffle the password to ensure randomness
    return str_shuffle($password);
}

if(isset($_POST['deletestaff']))
{

    $USER_IDS = $_POST['staff_ID_Delete'];

    $query = "DELETE FROM admin WHERE admin_ID = '$USER_IDS'";
    $result = mysqli_query($DB, $query);

    if ($result) {    
            unset($_POST['staff_ID_Delete']);  
            unset($_POST['deletestaff']);         
        showSweetAlertSucced2("Success!", "Coordinator has been deleted successfully.", "success");  
    } else {
            unset($_POST['staff_ID_Delete']); 
            unset($_POST['deletestaff']);  
        $error = "Error deleting user: " . mysqli_error($DB);
        showSweetAlertFailed2("Failed!", $error, "error");
    }
}

if(isset($_POST['updatestaffInfo']))
{

        $validation = 1;
        $userID = $_POST['staff_IDupdate'];
        $checkpass = ''; // password
        $path = $_FILES['customFile']['name'];
        $path_tmp = $_FILES['customFile']['tmp_name'];
        $firstName = $_POST['respondent_firstname'];
        $middleName = $_POST['respondent_middlename'];
        $lastName = $_POST['respondent_lastname'];
        $address = $_POST['address'];
        $contactNumber = $_POST['contact'];
        $password = '';
        $fullanme = $firstName.' '.$middleName.' '.$lastName;
        $position = 'Coordinator';
    $organization = $_POST['organization'];

        if($position == 'Other'){
            $specified_role = $_POST['role_others'];
            $position = $specified_role;
        }

        if ($path != '') 
        {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $file_name = basename($path, '.' . $ext);
            if ($ext != 'JPEG' && $ext != 'JPG' && $ext != 'PNG' && $ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') 
            {
                $validation = 0;
                showSweetAlertFailed2("Failed!", "You must have to upload jpg, jpeg, gif or png file of profile image", "error");
            }
        } 

        if($validation == 1)
        {
            if($checkpass != '')
            {   
                if($path != '')
                {

                    $profile_timestamp = time();
                    $profile_image = 'profile-'.$profile_timestamp.'-'.$file_name.'-.'. $ext;
                    move_uploaded_file($path_tmp, '../assets/uploads/'.$profile_image);

                    $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    address = '$address',
                    contact = '$contactNumber',
                    role = '$position',
                    password = '$password',
                    profileImage = '$profile_image',
                    org_id = '$organization'
                    WHERE 
                    admin_ID = $userID";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertSucced2("Success!", "Coordinator account information has been updated successfully.", "success");  
                    } else {
                        $error = "Error updating employee account information: " . mysqli_error($DB);
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }

                }
                else
                {

                    $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    address = '$address',
                    contact = '$contactNumber',
                    role = '$position',
                    password = '$password',
                    org_id = '$organization'
                    WHERE 
                    admin_ID = $userID";
                    $result = mysqli_query($DB, $query);

                    if ($result) {      
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertSucced2("Success!", "Coordinator account information has been updated successfully.", "success");  
                    } else {

                        $error = "Error updating employee account information: " . mysqli_error($DB);
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }

                }
            }
            else
            {
                if($path != '')
                {

                    $profile_timestamp = time();
                    $profile_image = 'profile-'.$profile_timestamp.'-'.$file_name.'-.'. $ext;
                    move_uploaded_file($path_tmp, '../assets/uploads/'.$profile_image);

                    $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    address = '$address',
                    contact = '$contactNumber',
                    role = '$position',
                    profileImage = '$profile_image',
                    org_id = '$organization'
                    WHERE 
                    admin_ID = $userID";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertSucced2("Success!", "Coordinator account information has been updated successfully.", "success");  
                    } else {

                        $error = "Error updating Coordinator account information: " . mysqli_error($DB);
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }

                }
                else
                {
                    $query = "UPDATE admin SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName',
                    address = '$address',
                    role = '$position',
                    contact = '$contactNumber',
                    org_id = '$organization'
                    WHERE 
                    admin_ID = $userID";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertSucced2("Success!", "Coordinator account information has been updated successfully.", "success");  
                    } else {
                        $error = "Error updating Coordinator account information: " . mysqli_error($DB);
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                }
            }
        }
}




if(isset($_POST['addstaffer']))
{
    $validation = 1;
    $addrespondent_firstname = $_POST['addrespondent_firstname'];
    $addrespondent_middlename = $_POST['addrespondent_middlename'];
    $addrespondent_lastname = $_POST['addrespondent_lastname'];
    $name = $addrespondent_firstname.' '.$addrespondent_middlename.' '.$addrespondent_lastname;
    $addemail = $_POST['addemail'];

    // Generate a random password
    $password = generateRandomPassword();

    $addpassword = md5($password);
    $addposition = 'Coordinator';
    $organization = $_POST['organization'];
    $adminID = $_SESSION['user_id']; //id of admin who added.

        if($validation == 1)
        {
            $query = "INSERT INTO admin (
                firstName, 
                middleName, 
                lastName, 
                role, 
                username,
                password, 
                org_id
            )
            VALUES 
            (
                '$addrespondent_firstname', 
                '$addrespondent_middlename', 
                '$addrespondent_lastname', 
                '$addposition', 
                '$addemail',
                '$addpassword',
                '$organization'
                )";

            $result = mysqli_query($DB, $query);

            if ($result) {
                  //ogbuyghbkabhkmit
                            $message = '
                                <html>
                              <body>
    <center>
        <h2><b>Welcome to NEUCONNECT</b></h2>
    </center>
    <p>Dear '.$name.',</p>
    <br>
    <p>We are pleased to inform you that your NEUCONNECT Coordinator account has been successfully created. Below are your login credentials:</p>
    <br>
    <p><b>Email Address: </b> '.$addemail.'</p>
    <p><b>Password: </b> '.$password.'</p>
    <br>
    <p>We recommend logging in promptly to change your password to ensure the security of your account. Should you have any questions or need additional support, please feel free to reach out to us at any time.</p>
    <br>
    <p>Thank you for joining NEUCONNECT.</p>
    <br>
    <p>Warm regards,</p>
    <p><b>The NEUCONNECT Team</b></p>
</body>

                                </html>';

                    
                            try 
                            {   
                                $cust_email = $addemail;
                                $cust_name = $name;
                    
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
                                $mail->AddReplyTo("finalsemcapstone@gmail.com", "NEUCONNECT");//when in hosting change this the email of web.z
                                $mail->Subject = 'Coordinator Account';
                                $EMAIL_MESSAGE = $message;
                                $mail->MsgHTML($EMAIL_MESSAGE); 
                                if(!$mail->Send()) 
                                {
                                  $success_message =  "Error while sending Email!";
                                  var_dump($mail);
                                } 
                                else 
                                {
                                  $success_message =  "Email has been sent successfully!";
                                } 
                            } 
                            catch (Exception $e) 
                            {
                                echo 'Message could not be sent!';
                                echo 'Mailer Error: ' . $mail->ErrorInfo;
                            }


                unset($_POST['addstaffer']);
                showSweetAlertSucced2("Success!", "New Coordinator has been added successfully.", "success");  
            } else {
                $error = "Error saving new employee account: " . mysqli_error($DB);
                unset($_POST['addstaffer']);
                showSweetAlertFailed2("Failed!", $error, "error");
            }
        }

}



    function showSweetAlertSucced2($title, $message, $type) {
            echo "
            <script>
            setTimeout(function() {
                Swal.fire({
                    title: '$title',
                    text: '$message',
                    icon: '$type',
                    confirmButtonText: 'OK'
                }).then(function() {
                  window.location.href = 'manage_coordinators.php';
                });
            }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
            </script>";
    }
    function showSweetAlertFailed2($title, $message, $type) {
                echo "
                <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '$title',
                        text: '$message',
                        icon: '$type',
                        confirmButtonText: 'OK'
                    }).then(function() {
                      window.location.href = 'manage_coordinators.php';
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
                    <h1 class="m-0">Manage Coordinators</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 accent-orange">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Manage Coordinators</li>
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
                    <h4 class="float-left">Coordinators List </h4>
                    <div class="btn-group float-right">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStaff_user"><i class="fas fa-plus"></i> Add Coordinator</button>
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
                                <th>Organization </th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $i = 0;
                              $residentqeury = mysqli_query($DB, "SELECT * FROM admin as a inner join organization as b on a.org_id = b.org_id WHERE a.role = 'Coordinator'");
                            if (mysqli_num_rows($residentqeury) > 0) 
                            { 
                                while ($row = mysqli_fetch_assoc($residentqeury)) 
                                {
                                    $i++;
                                    $staff_ID = $row['admin_ID'];
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['firstName'].' '.$row['middleName'].' '.$row['lastName']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td><?php echo $row['org_name']; ?></td>
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
                                                      <h4 class="modal-title">Deleting Coordinator Account</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <p>Are you sure you want to delete this Coordinator Account?</p>
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
                                                        <h4 class="modal-title">Edit Coordinator</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row accent-orange">

                                                        <input type="hidden" name="staff_IDupdate" id="staff_IDupdate" value="<?php echo $staff_ID; ?>">
                                                            <?php if($row['profileImage'] != '') { ?>
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
                                                                <?php if($row['profileImage'] == '') { ?>
                                                                    <label class="custom-file-label" for="customFile">Choose Profile Picture</label>
                                                                <?php }else{ ?>
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


                                                            <div class="col-12 col-md-12 col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="respondent_lastname">Address </label>
                                                                    <input type="text" name="address" id="address" class="form-control" value="<?php echo $row['address']; ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-12 col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="contact">Contact Number </label>
                                                                    <input type="text" name="contact" id="contact" class="form-control" placeholder="9xxxxxxxxx" oninput="validateContactNumber<?php echo $row['username']; ?>(this)" value="<?php echo $row['contact']; ?>">
                                                                </div>   
                                                                <label id="contactErrorLabel<?php echo $row['username']; ?>" style="color: red; display: none;">Contact number should start with "9"! Example: 9xxxxxxxxx</label>
                             
                                                            </div>
                                                            <script>
                                                                function validateContactNumber<?php echo $row['username']; ?>(input) {
                                                                    var inputValue = input.value;

                                                                    // Remove any non-numeric characters
                                                                    var numericValue = inputValue.replace(/\D/g, '');

                                                                    // Ensure the number starts with '+639' and has a length of 13 characters
                                                                    if (!numericValue.startsWith('9') || numericValue.length !== 10) {
                                                                        document.getElementById('contactErrorLabel<?php echo $row['username']; ?>').style.display = 'block';
                                                                    } else {
                                                                        // Format the number as '+639XXXXXXXXX'
                                                                        var formattedValue = numericValue;
                                                                        input.value = formattedValue;
                                                                        document.getElementById('contactErrorLabel<?php echo $row['username']; ?>').style.display = 'none';
                                                                    }
                                                                }
                                                            </script>
                                                            <div class="col-12 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="email">Email Address </label>
                                                                    <input type="text" name="email" id="email" class="form-control" value="<?php echo $row['username']; ?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="accessLevel">Organization</label>
                                                                    <select class="form-control select2" name="organization" id="organization" data-dropdown-css-class="select2-orange"
                                                                        style="width: 100%">
                                                                    <?php  
                                                                        $counter = 0;
                                                                        $checkresident22 = mysqli_query($DB, "SELECT * FROM organization WHERE org_status = 'Active'");
                                                                        if (mysqli_num_rows($checkresident22) > 0) 
                                                                        {
                                                                            while ($data22 = mysqli_fetch_assoc($checkresident22)) 
                                                                            { ?>
                                                                        <option value="<?php echo $data22['org_id']; ?>" <?php if($row['org_id'] == $data22['org_id']){ echo 'selected'; } ?>><?php echo $data22['org_name']; ?></option>
                                                                    <?php } } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
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
                <h4 class="modal-title">Add Coordinator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action=""  enctype="multipart/form-data" onsubmit="return validateFormmanagestaff()">
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
                                                            <div class="col-12 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="accessLevel">Organization</label>
                                                                    <select class="form-control select2" name="organization" id="organization" data-dropdown-css-class="select2-orange"
                                                                        style="width: 100%">
                                                                    <?php  
                                                                        $counter = 0;
                                                                        $checkresident22 = mysqli_query($DB, "SELECT * FROM organization WHERE org_status = 'Active'");
                                                                        if (mysqli_num_rows($checkresident22) > 0) 
                                                                        {
                                                                            while ($data22 = mysqli_fetch_assoc($checkresident22)) 
                                                                            { ?>
                                                                        <option value="<?php echo $data22['org_id']; ?>"><?php echo $data22['org_name']; ?></option>
                                                                    <?php } } ?>
                                                                    </select>
                                                                </div>
                                                            </div>


                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="email">Email Address </label>
                                <input type="email" name="addemail" id="addemail" class="form-control" value="" required>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="submit" name="addstaffer" id="addstaffer" class="btn btn-success"><span>Create Coordinator  </span><i class="fas fa-plus"></i></button>
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