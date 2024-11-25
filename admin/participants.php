<?php
   include('includes/header.php');
   
                        $ev_org_id = $_SESSION['org_id'];
   if(isset($_POST['deletestaff']))
   {
   
       $USER_IDS = $_POST['staff_ID_Delete'];
   
       $query = "DELETE FROM events WHERE ev_id = '$USER_IDS'";
       $result = mysqli_query($DB, $query);
   
       if ($result) {    
               unset($_POST['staff_ID_Delete']);  
               unset($_POST['deletestaff']);         
           showSweetAlertSucced2("Success!", "Event has been deleted successfully.", "success");  
       } else {
               unset($_POST['staff_ID_Delete']); 
               unset($_POST['deletestaff']);  
           $error = "Error deleting Event: " . mysqli_error($DB);
           showSweetAlertFailed2("Failed!", $error, "error");
       }
   }
   
   if(isset($_POST['updatestaffInfo']))
   {
        $ev_id = $_POST['ev_id'];
        $ev_name = $_POST['ev_name'];
        $ev_description = $_POST['ev_description'];
        $ev_venue = $_POST['ev_venue'];
        $ev_date = $_POST['ev_date'];
        $ev_time_from = $_POST['ev_time_from'];
        $ev_time_to = $_POST['ev_time_to'];
// Convert from 24-hour to 12-hour AM/PM format
$ev_time_from = date("g:i A", strtotime($ev_time_from)); // Converts "13:00" to "1:00 PM"
$ev_time_to = date("g:i A", strtotime($ev_time_to));     // Converts "03:00" to "3:00 AM"

        $TIME = $ev_time_from.' TO '.$ev_time_to;

                       $query = "UPDATE events SET 
                       ev_name = '$ev_name',
                       ev_description = '$ev_description',
                       ev_date = '$ev_date',
                       ev_time = '$TIME'
                       WHERE 
                       ev_id = $ev_id";
                       $result = mysqli_query($DB, $query);
   
                       if ($result) {
                           unset($_POST['updatestaffInfo']);
                           showSweetAlertSucced2("Success!", "Event information has been updated successfully.", "success");  
                       } else {
                           $error = "Error updating Event information: " . mysqli_error($DB);
                           unset($_POST['updatestaffInfo']);
                           showSweetAlertFailed2("Failed!", $error, "error");
                       }
   }
   
   if(isset($_POST['addstaffer']))
   {
        $ev_name = $_POST['ev_name'];
        $ev_description = $_POST['ev_description'];
        $ev_venue = $_POST['ev_venue'];
        $ev_date = $_POST['ev_date'];
        $ev_time_from = $_POST['ev_time_from'];
        $ev_time_to = $_POST['ev_time_to'];

// Convert from 24-hour to 12-hour AM/PM format
$ev_time_from = date("g:i A", strtotime($ev_time_from)); // Converts "13:00" to "1:00 PM"
$ev_time_to = date("g:i A", strtotime($ev_time_to));     // Converts "03:00" to "3:00 AM"

        $ev_timers = $ev_time_from.' TO '.$ev_time_to;
               $query = "INSERT INTO events (
                   ev_name, 
                   ev_description, 
                   ev_date, 
                   ev_time, 
                   ev_venue, 
                   ev_org_id
               )
               VALUES 
               (
                   '$ev_name', 
                   '$ev_description', 
                   '$ev_date', 
                   '$ev_timers', 
                   '$ev_venue', 
                   '$ev_org_id'
                   )";
   
               $result = mysqli_query($DB, $query);
   
               if ($result) {
                   unset($_POST['addstaffer']);
                   showSweetAlertSucced2("Success!", "New event has been added successfully.", "success");  
               } else {
                   $error = "Error saving new event: " . mysqli_error($DB);
                   unset($_POST['addstaffer']);
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
                     window.location.href = 'events.php';
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
                         window.location.href = 'events.php';
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
               <h1 class="m-0">Manage Events Participants</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Manage Events Participants</li>
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
               <h4 class="float-left">Events Participants List </h4>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Event Name </th>
                        <th>Number of Participants</th>
                        <th>Status</th>
                        <th>Action </th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $i = 0;
                        $myorg = $_SESSION['org_id'];
                        $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_org_id='$myorg'");
                        if (mysqli_num_rows($residentqeury) > 0) 
                        { 
                            while ($row = mysqli_fetch_assoc($residentqeury)) 
                            {
                                $i++;
                                $event_id = $row['ev_id'];
                                $participant_count = 0;

                                 $residentqeury2 = mysqli_query($DB, "SELECT * FROM participants WHERE p_event_id='$event_id' AND p_status = 'Approved'");
                                 if (mysqli_num_rows($residentqeury2) > 0) 
                                 { 
                                     while ($row2 = mysqli_fetch_assoc($residentqeury2)) 
                                     {
                                       $participant_count++;
                                     }
                                  }
                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['ev_name']; ?></td>
                        <td><?php echo $participant_count; ?> Participants/s</td>
                        <td><?php echo $row['ev_status']; ?></td>
                        <td align="center">
                           <a href="view_participants.php?id=<?php echo $row['ev_id']; ?>" class="btn btn-primary" style="background-color:lightgreen; color:black;">View Participants</a>
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
<!--STAFF-->
<div class="modal fade" id="addStaff_user">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Add Events</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action=""  enctype="multipart/form-data" onsubmit="return validateFormmanagestaff()">
               <div class="row accent-orange">
                  <div class="col-12 col-md-12 col-lg-12">
                     <div class="form-group">
                        <label for="ev_name">Event Name</label>
                        <input type="text" name="ev_name" id="ev_name" class="form-control" placeholder="Event Name" value="" required>
                     </div>
                  </div>
                  <div class="col-12 col-md-12 col-lg-12">
                     <div class="form-group">
                        <label for="ev_description">Event Description</label>
                        <textarea type="text" name="ev_description" id="ev_description" class="form-control" placeholder="Event Description" value="" required></textarea>
                     </div>
                  </div>
                  <div class="col-12 col-md-12 col-lg-12">
                     <div class="form-group">
                        <label for="ev_venue">Event Venue</label>
                        <input type="text" name="ev_venue" id="ev_venue" class="form-control" placeholder="Event Venue" value="" required>
                     </div>
                  </div>
                  <div class="col-12 col-md-12 col-lg-12">
                     <div class="form-group">
                        <label for="respondent_lastname">Event Date </label>
                        <input type="date" name="ev_date" id="ev_date" min="<?php echo date('Y-m-d'); ?>" class="form-control" required>
                     </div>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6">
                     <div class="form-group">
                        <label for="ev_time_from">From Time</label>
                        <input type="time" name="ev_time_from" id="ev_time_from" class="form-control" required>
                     </div>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6">
                     <div class="form-group">
                        <label for="ev_time_to">To Time</label>
                        <input type="time" name="ev_time_to" id="ev_time_to" class="form-control" required>
                     </div>
                  </div>
                 
                  </div>
               </div>
               <div class="modal-footer justify-content-end">
                  <button type="submit" name="addstaffer" id="addstaffer" class="btn btn-success"><span>Create Event </span><i class="fas fa-plus"></i></button>
               </div>
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