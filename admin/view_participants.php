<?php
   include('includes/header.php');
   
   $ev_org_id = $_SESSION['org_id'];
   $event_id = $_REQUEST['id'];



if(isset($_POST['inactiveuser'])){
    $student_id = $_POST['p_id'];

    
                    $query = "UPDATE participants SET 
                    p_status = 'Disapproved'
                    WHERE 
                    p_id = $student_id";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertSucced2("Success!", "Participant has been disapproved successfully.", "success");  
                    } else {
                        $error = "Error updating data: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                
}
   
if(isset($_POST['activeuser'])){
    $student_id = $_POST['p_id'];
    
                    $query = "UPDATE participants SET 
                    p_status = 'Approved'
                    WHERE 
                    p_id = $student_id";
                    $result = mysqli_query($DB, $query);

                    if ($result) {
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertSucced2("Success!", "Participant has been approved successfully.", "success");  
                    } else {
                        $error = "Error updating data: " . mysqli_error($DB);
                        unset($_POST['updateprofileinfo']);
                        showSweetAlertFailed2("Failed!", $error, "error");
                    }
                
} 

function showSweetAlertSucced2($title, $message, $type) {
    // Retrieve the id from the request
    $id = $_REQUEST['id'];

    echo "
    <script>
    setTimeout(function() {
        Swal.fire({
            title: '$title',
            text: '$message',
            icon: '$type',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'view_participants.php?id=$id'; // Use PHP to inject the ID
        });
    }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
    </script>";
}

function showSweetAlertFailed2($title, $message, $type) {
    // Retrieve the id from the request
    $id = $_REQUEST['id'];

    echo "
    <script>
    setTimeout(function() {
        Swal.fire({
            title: '$title',
            text: '$message',
            icon: '$type',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'view_participants.php?id=$id'; // Use PHP to inject the ID
        });
    }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
    </script>";
}

   include('includes/topbar.php');
   include('includes/sidebar.php');
   include('includes/modals.php');

   $event_name = '';
                        $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_id='$event_id'");
                        if (mysqli_num_rows($residentqeury) > 0) 
                        { 
                            while ($row = mysqli_fetch_assoc($residentqeury)) 
                            {
                              $event_name = $row['ev_name'];
                            }
                         }
   ?><!-- Bootstrap CSS -->

<!-- Bootstrap JS (Popper and Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper accent-orange">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Manage <b><?php echo $event_name; ?></b> Participants</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Manage <?php echo $event_name; ?> Participants</li>
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
               <h4 class="float-left"><?php echo $event_name; ?> Participants List </h4>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Student #</th>
                        <th>Participant Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Identification Card</th>
                        <th>Status </th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $i = 0;
                        $myorg = $_SESSION['org_id'];
                        $residentqeury = mysqli_query($DB, "SELECT * FROM participants WHERE p_event_id='$event_id'");
                        if (mysqli_num_rows($residentqeury) > 0) 
                        { 
                            while ($row = mysqli_fetch_assoc($residentqeury)) 
                            {
                                $i++;
                                 $unique_id = $row['p_id']; // Generate a unique ID for each row/modal
                                 $image_url = "../assets/uploads/".$row['p_idimage']; // Replace with the actual image URL
                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['p_student_no']; ?></td>
                        <td><?php echo $row['p_fullname']; ?></td>
                        <td><?php echo $row['p_contact']; ?></td>
                        <td><?php echo $row['p_email']; ?></td>
                        <td>
                            <!-- Trigger the modal with an image -->
                            <center><img src="<?php echo $image_url; ?>" alt="Image" class="img-thumbnail" style="width: 100px; height: auto;" data-bs-toggle="modal" data-bs-target="#modal<?php echo $unique_id; ?>" /></center>

                            <!-- Modal -->
                            <div class="modal fade" id="modal<?php echo $unique_id; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $unique_id; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel<?php echo $unique_id; ?>">Image Preview</h5>
                                        </div>
                                        <div class="modal-body">
                                            <center><img src="<?php echo $image_url; ?>" alt="Image" class="img-fluid" /></center>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $row['p_status']; ?></td>
                        <td align="center">
                           <?php if($row['p_status'] == 'Pending'){ ?>

                                        <form action="" method="POST">
                                            <input type="hidden" id="p_id<?php echo $i; ?>" name="p_id" value="<?php echo $row['p_id']; ?>" />
                                            <button style="width:100%;" type="submit" class="btn btn-primary" id="activeuser<?php echo $i; ?>" name="activeuser">Approve</button>
                                        </form>
                                        <form action="" method="POST">
                                            <input type="hidden" id="p_id<?php echo $i; ?>" name="p_id" value="<?php echo $row['p_id']; ?>" />
                                            <button style="width:100%;" type="submit" class="btn btn-danger" id="inactiveuser<?php echo $i; ?>" name="inactiveuser">Disapprove</button>
                                        </form>
                           <?php }else{ ?>
                              No Action Required
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