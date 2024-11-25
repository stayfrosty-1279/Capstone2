<?php
   include('includes/header.php');
   
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
    $mail = new PHPMailer();

                        $ev_org_id = $_SESSION['org_id'];

    if(isset($_POST['deletebooked'])){
        $B_ID = $_POST['b_id'];

       $query = "DELETE FROM booked WHERE bid = '$B_ID'";
       $result = mysqli_query($DB, $query);
   
       if ($result) {    
               unset($_POST['staff_ID_Delete']);  
               unset($_POST['deletestaff']);         
           showSweetAlertSucced2("Success!", "Booked has been deleted successfully.", "success");  
       } else {
               unset($_POST['staff_ID_Delete']); 
               unset($_POST['deletestaff']);  
           $error = "Error deleting Booked: " . mysqli_error($DB);
           showSweetAlertFailed2("Failed!", $error, "error");
       }
    }

    if(isset($_POST['Approve'])){
        $b_id1 = $_POST['b_id1'];

                    $query = "UPDATE booked SET 
                    b_status = 'Approved'
                    WHERE 
                    bid = $b_id1";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertSucced2("Success!", "Booking has been approved successfully.", "success");  
                    } else {
                        $error = "Error approving booking " . mysqli_error($DB);
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }

    }
    if(isset($_POST['Disapprove'])){
        $b_id2 = $_POST['b_id2'];
                    $query = "UPDATE booked SET 
                    b_status = 'Disapproved'
                    WHERE 
                    bid = $b_id2";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertSucced2("Success!", "Booking has been disapproved successfully.", "success");  
                    } else {
                        $error = "Error disapproving booking " . mysqli_error($DB);
                        unset($_POST['updatestaffInfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
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
                     window.location.href = 'amenities.php';
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
                         window.location.href = 'amenities.php';
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
               <h1 class="m-0">Manage Booked Amenities</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Manage Booked Amenities</li>
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
         <div class="card">
            <div class="card-header">
               <h4 class="float-left">Booked Amenities List </h4>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Event Name </th>
                        <th>Date & Time </th>
                        <th>Venue</th>
                        <th>Amenities</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $i = 0;
                        $myorg = $_SESSION['org_id'];
                          $residentqeury = mysqli_query($DB, "SELECT DISTINCT a.ev_id, a.ev_time, a.ev_date, a.ev_status, a.ev_venue, a.ev_name FROM events as a inner join booked as b on a.ev_id = b.b_event_id");
                        if (mysqli_num_rows($residentqeury) > 0) 
                        { 
                            while ($row = mysqli_fetch_assoc($residentqeury)) 
                            {
                                $i++;
                                $staff_ID = $row['ev_id'];
                                // Example ev_time value
                                $ev_time = $row['ev_time']; // e.g., "1:00 PM TO 2:00 PM"
                                $ev_date = $row['ev_date'];
                                // Split the string by ' TO '
                                $time_parts = explode(' TO ', $ev_time);


                                // Extract the start time (From Time) - this will be used for comparison
                                $checker_time = date("Y-m-d H:i:s", strtotime($ev_date . ' ' . $time_parts[0]));
                                // Convert 12-hour time to 24-hour format (for input type="time")
                                $ev_time_from = date("H:i", strtotime($time_parts[0])); // Converts "1:00 PM" to "13:00"
                                $ev_time_to = date("H:i", strtotime($time_parts[1])); // Converts "2:00 PM" to "14:00"
                                // Get the current date and time
                                $current_time = date("Y-m-d H:i:s");

                                // Check if the status is "Ongoing" and the current time is less than the start time
                                if ($row['ev_status'] == 'Ongoing' && $current_time < $checker_time) {
                                    $row['ev_status'] = 'Waiting'; // Update the status to "Waiting"
                                }
                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['ev_name']; ?></td>
                        <td>Date: <?php echo $row['ev_date'].' <br>Time: '.$row['ev_time']; ?></td>
                        <td><?php echo $row['ev_venue']; ?></td>
                        <td>
                            <?php 
                            $test=0;
                          $residentqeurygg1 = mysqli_query($DB, "SELECT * FROM booked WHERE b_event_id='$staff_ID'");
                        if (mysqli_num_rows($residentqeurygg1) > 0) 
                        { 
                            while ($rowgg1 = mysqli_fetch_assoc($residentqeurygg1)) 
                            {
                                $test++;
                                 if($rowgg1['b_status'] == 'Approved'){ 
                                echo '<div style="border:2px solid lightgreen; border-radius:2vh; padding:1vh;">Status: <b>'.$rowgg1['b_status'].'</b><hr>'.$rowgg1['b_description'].'';
                            }else if($rowgg1['b_status'] == 'Disapproved'){ 

                                echo '<div style="border:2px solid red; border-radius:2vh; padding:1vh;">Status: <b>'.$rowgg1['b_status'].'</b><hr>'.$rowgg1['b_description'].'';
                            }else{

                                echo '<div style="border:2px solid gray; border-radius:2vh; padding:1vh;">Status: <b>'.$rowgg1['b_status'].'</b><hr>'.$rowgg1['b_description'].'';
                            }
                                ?>
                                <?php if($rowgg1['b_status'] == 'Pending'){ ?>
                                <form action="" method="POST">

                                                <input type="hidden" name="b_id1" id="<?php echo $test; ?>b_id1<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $rowgg1['bid']; ?>" required>

                                            <center> <button type="submit" class="btn btn-success" name="Approve" id="<?php echo $test; ?>Approve<?php echo $staff_ID; ?>">Approve</button></center>
                                </form>
                                <br>
                                <form action="" method="POST">

                                                <input type="hidden" name="b_id2" id="<?php echo $test; ?>b_id2<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $rowgg1['bid']; ?>" required>

                                            <center> <button type="submit" class="btn btn-danger" name="Disapprove" id="<?php echo $test; ?>Disapprove<?php echo $staff_ID; ?>">Disapprove</button></center>
                                </form>
                            <?php } ?>
                            </div><br>
                                <?php
                            } 
                        }?>
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
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
<?php
   include('includes/footer.php');
   ?>