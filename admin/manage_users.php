<?php
include('includes/header.php');

include('includes/modals.php');


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../PHPMailer-master/src/Exception.php';
    require '../PHPMailer-master/src/PHPMailer.php';
    require '../PHPMailer-master/src/SMTP.php';
    $mail = new PHPMailer();

    $registration_message = '';
    $registration_error = '';

if(isset($_POST['deleteuser']))
{
    $USER_IDS = $_POST['user_ID_Delete'];

    $query = "DELETE FROM users WHERE user_id = '$USER_IDS'";
    $result = mysqli_query($DB, $query);

    if ($result) {    
            unset($_POST['user_ID_Delete']);  
            unset($_POST['deleteuser']);         
        showSweetAlertSucced2("Success!", "Officer has been deleted successfully.", "success");  
    } else {
            unset($_POST['user_ID_Delete']); 
            unset($_POST['deleteuser']);  
        $error = "Error deleting user: " . mysqli_error($DB);
        showSweetAlertFailed2("Failed!", $error, "error");
    }

}

if(isset($_POST['updateprofileinfos']))
    {
        $validation = 1;
        $userID = $_POST['user_ids'];
        
        $firstName = $_POST['firstNames'];
        $middleName = $_POST['MIs'];
        $lastName = $_POST['lastNames'];
      
        $fullanme = $firstName.' '.$middleName.' '.$lastName;

        if($validation == 1)
        {
                    $query = "UPDATE users SET 
                    firstName = '$firstName',
                    middleName = '$middleName',
                    lastName = '$lastName'
                    WHERE 
                    user_id = $userID";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfos']);
                        showSweetAlertSucced2("Success!", "Officer has been updated successfully.", "success");  
                    } else {
                        $error = "Error updating profile: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfos']);
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
                  window.location.href = 'manage_users.php';
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
                      window.location.href = 'manage_users.php';
                    });
                }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
                </script>";
    }


if(isset($_POST['inactiveuser'])){
    $student_id = $_POST['student_id'];

                    $query = "UPDATE users SET 
                    Status = 'Inactive'
                    WHERE 
                    user_id = $student_id";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertSucced2("Success!", "Officer has been disabled successfully.", "success");  
                    } else {
                        $error = "Error updating profile: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                
}
   
if(isset($_POST['activeuser'])){
    $student_id = $_POST['student_id'];
    
                    $query = "UPDATE users SET 
                    Status = 'Active'
                    WHERE 
                    user_id = $student_id";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertSucced2("Success!", "Officer has been enabled successfully.", "success");  
                    } else {
                        $error = "Error updating profile: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                
} 

if(isset($_POST['disapproveuser'])){
    $student_id = $_POST['student_id'];
    
                    $query = "UPDATE users SET 
                    admin_approval = 'Disapproved'
                    WHERE 
                    user_id = $student_id";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertSucced2("Success!", "Officer has been disapproved successfully.", "success");  
                    } else {
                        $error = "Error updating profile: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                
} 
if(isset($_POST['approveuser'])){
    $student_id = $_POST['student_id'];
    
                    $query = "UPDATE users SET 
                    admin_approval = 'Approved'
                    WHERE 
                    user_id = $student_id";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertSucced2("Success!", "Officer has been approved successfully.", "success");  
                    } else {
                        $error = "Error updating profile: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                
} 
    include('includes/topbar.php');
    include('includes/sidebar.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper accent-orange">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Officers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 accent-orange">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Manage Officers</li>
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
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th># </th>
                                <th>Full Name </th>
                                <th>Email</th>
                                <th>Contact # </th>
                                <?php 
                            if( $_SESSION['priviledges'] == "Super Admin"){ ?>
                                <th>Organization</th>
                            <?php } ?>
                                <th>Account Status</th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            if( $_SESSION['priviledges'] == "Super Admin"){
                            $residentqeury = mysqli_query($DB, "SELECT * FROM users as a inner join organization as b on a.org_id = b.org_id");
                        }else{
                            $my_org_id = $_SESSION['org_id'];
                            $residentqeury = mysqli_query($DB, "SELECT * FROM users as a inner join organization as b on a.org_id = b.org_id WHERE a.org_id = '$my_org_id'");
                        }
                            if (mysqli_num_rows($residentqeury) > 0) 
                            { 
                                while ($row = mysqli_fetch_assoc($residentqeury)) 
                                {
                                    $i++;
                                    $user_ID = $row['user_id'];
                                    $modalIdview = 'modalview-' . $i; //view records
                                    $modalIdviewpRINT = 'modalviewPrint-' . $i; //view records
                                    $dateString = $row['dateRegistered'];
                                    $timestamp = strtotime($dateString);
                                    $formattedDate = date("M j, Y g:i A", $timestamp);

                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['firstName'].' '.$row['middleName'].' '.$row['lastName']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['contactNumber']; ?></td>
                                <?php 
                            if( $_SESSION['priviledges'] == "Super Admin"){ ?>
                                <td><?php echo $row['org_name']; ?></td>
                            <?php } ?>
                                <td>
                                    <center><?php echo $row['Status']; ?>
                                    
                                    <?php if($row['Status'] == 'Active'){ ?>
                                        <form action="" method="POST">
                                            <input type="hidden" id="studentid<?php echo $i; ?>" name="student_id" value="<?php echo $row['user_id']; ?>" />
                                            <button type="submit" class="btn btn-danger" id="inactiveuser<?php echo $i; ?>" name="inactiveuser">Disable</button>
                                        </form>
                                    <?php }else{ ?>

                                        <form action="" method="POST">
                                            <input type="hidden" id="studentid<?php echo $i; ?>" name="student_id" value="<?php echo $row['user_id']; ?>" />
                                            <button type="submit" class="btn btn-primary" id="activeuser<?php echo $i; ?>" name="activeuser">Enable</button>
                                        </form>
                                    <?php } ?>
                                </center>
                                </td>
                                
                                <td align="center">
                                <?php 
                            if($_SESSION['priviledges'] != 'Admin'){ ?>

                                    <?php if($row['admin_approval'] == 'Pending'){ ?>

                                        <form action="" method="POST">
                                            <input type="hidden" id="studentid<?php echo $i; ?>" name="student_id" value="<?php echo $row['user_id']; ?>" />
                                            <button type="submit" style="width:100%;" class="btn btn-primary" id="approveuser<?php echo $i; ?>" name="approveuser">Approve Officer</button>
                                        </form>
                                    <br>
                                        <form action="" method="POST">
                                            <input type="hidden" id="studentid<?php echo $i; ?>" name="student_id" value="<?php echo $row['user_id']; ?>" />
                                            <button type="submit"  style="width:100%;"class="btn btn-danger" id="disapproveuser<?php echo $i; ?>" name="disapproveuser">Disapprove Officer</button>
                                        </form>
                                    <hr>
                                    <?php } ?>

                                    <div class="btn-group">
                                    <?php 
                                    if($_SESSION['priviledges'] != 'Admin'){ ?>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#manageUsers<?php echo $user_ID; ?>" style="margin-right:1vh;"> <span class="d-lg-block d-lg-none d-xl-none">Edit </span><i class="fas fa-edit"></i> </button>
                                    <?php } ?>

                                        <div class="modal fade" id="manageUsers<?php echo $user_ID; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm<?php echo $i; ?>()">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Manage Officer</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row accent-orange">
                                                                <input type="hidden" name="user_ids" id="user_id<?php echo $user_ID; ?>" value="<?php echo $row['user_id']; ?>"></input>

                                                                <?php if($row['profile_picture'] != '') { ?>
                                                              
                                                                <div class="col-6 col-md-6 col-lg-6">
                                                                    <label for="ID">Profile Picture </label>
                                                                    <div class="mailbox-attachment-info ">
                                                                        <a href="../assets/uploads/<?php echo $row['profile_picture']; ?>" data-lightbox="gallery"
                                                                            class="btn btn-default">View <i class="fas fa-eye"></i></a>
                                                                        <span class="mailbox-attachment-size clearfix">
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>

                                                                <?php if($row['idImage'] != '') { ?>
                                                              
                                                                <div class="col-6 col-md-6 col-lg-6">
                                                                    <label for="ID">Employee ID </label>
                                                                    <div class="mailbox-attachment-info ">
                                                                        <a href="../assets/uploads/<?php echo $row['idImage']; ?>" data-lightbox="gallery"
                                                                            class="btn btn-default">View <i class="fas fa-eye"></i></a>
                                                                        <span class="mailbox-attachment-size clearfix">
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                                <hr>
                                                                <div class="col-12 col-md-6 col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="firstName">First Name </label>
                                                                        <input type="text" name="firstNames" id="firstName<?php echo $user_ID; ?>" class="form-control" value="<?php echo $row['firstName']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-6 col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="MI">Middle Name </label>
                                                                        <input type="text" name="MIs" id="MI<?php echo $user_ID; ?>" class="form-control" value="<?php echo $row['middleName']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="lastName">Last Name </label>
                                                                        <input type="text" name="lastNames" id="lastName<?php echo $user_ID; ?>" class="form-control" value="<?php echo $row['lastName']; ?>">
                                                                    </div>
                                                                </div>
                                                               


                                                                <div class="col-6 col-md-6 col-lg-6" style="display:none;">
                                                                    <div class="form-group">
                                                                        <label for="email">Email Address</label>
                                                                        <input type="email" name="emails" id="email<?php echo $user_ID; ?>" class="form-control"  value="<?php echo $row['email']; ?>"
                                                                            disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-md-12 col-lg-12 mb-3" style="display:none;">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" id="customFile<?php echo $user_ID; ?>" name="customFiles">
                                                                        <label class="custom-file-label" for="customFile">Change Profile Picture</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer justify-content-end">
                                                            <button type="submit" class="btn btn-success" name="updateprofileinfos" id="updateprofileinfo<?php echo $user_ID; ?>">Update <i class="fas fa-edit"></i></button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>

                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUsers<?php echo $user_ID; ?>"> <span class="d-lg-block d-lg-none d-xl-none"> Delete</span><i class="fas fa-trash"></i> </button>
                            
                                            <form action="" method="post">                                   
                                              <div class="modal fade" id="deleteUsers<?php echo $user_ID; ?>">
                                                <div class="modal-dialog">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h4 class="modal-title">Deleting Officer Account</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <p>Are you sure you want to delete this Officer Account?</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-end">
                                                        <input type="hidden" name="user_ID_Delete" id="user_ID_Delete" value="<?php echo $user_ID; ?>">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                      <button type="submit" class="btn btn-danger" id="deleteuser" name="deleteuser">Confirm</button>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </form>

                                    </div>
                                <?php }else{ ?>
                                    NO ACTION IS REQUIRED
                                <?php } ?>
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


<?php
   include('includes/footer.php');
   ?>