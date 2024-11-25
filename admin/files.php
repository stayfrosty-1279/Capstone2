<?php
   include('includes/header.php');
   
    $org_id = $_SESSION['org_id'];
                        $ev_org_id = $_SESSION['org_id'];
   if(isset($_POST['deleteuser']))
   {
   
       $USER_IDS = $_POST['user_ID_Delete'];
   
       $query = "DELETE FROM files WHERE f_id = '$USER_IDS'";
       $result = mysqli_query($DB, $query);
   
       if ($result) {    
               unset($_POST['staff_ID_Delete']);  
               unset($_POST['deletestaff']);         
           showSweetAlertSucced2("Success!", "File has been deleted successfully.", "success");  
       } else {
               unset($_POST['staff_ID_Delete']); 
               unset($_POST['deletestaff']);  
           $error = "Error deleting File: " . mysqli_error($DB);
           showSweetAlertFailed2("Failed!", $error, "error");
       }
   }
   
   
   if(isset($_POST['submitadd'])){
      
      $time = time();
      $f_file_subject = $_POST['f_file_subject'];
      $f_file_details = $_POST['f_file_details'];
           if (isset($_FILES['id-image']) && $_FILES['id-image']['error'] == 0) {
               $uploadDir2 = '../assets/uploads/';
               $identification_card = $time.'-'.basename($_FILES['id-image']['name']);
               $uploadFilePath2 = $uploadDir2 . $identification_card;
               if (!is_dir($uploadDir2)) {mkdir($uploadDir2, 0777, true);}
               if (move_uploaded_file($_FILES['id-image']['tmp_name'], $uploadFilePath2)) {}
           }
   
       // Build the INSERT query
       $query = "INSERT INTO files (f_file, f_org_id, f_file_subject, f_file_details) VALUES ('$identification_card','$ev_org_id', '$f_file_subject', '$f_file_details')";
   
       // Execute the query
       $result = mysqli_query($DB, $query);
   
       // Check if the query was successful
       if ($result) {
           showSweetAlertSucced2("Success!", "File has been uploaded successfully.", "success");
       } else {
           $error = "Error inserting file: " . mysqli_error($DB);
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
                     window.location.href = 'files.php';
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
                         window.location.href = 'files.php';
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
               <h1 class="m-0">Manage Shared Files</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Manage Shared Files</li>
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
               <h4 class="float-left">Shared Files List </h4>
               <?php if($_SESSION['priviledges'] == 'Coordinator'){ ?>
               <div class="btn-group float-right">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_packages"><i class="fas fa-plus"></i> Add Files</button>
               </div>
               <?php } ?>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>File Name</th>
                        <th>Details</th>
                        <th>File</th>
                        <th>Date </th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                   
<?php
    $cnt = 1;

    $checkresident2 = mysqli_query($DB, "SELECT * FROM files WHERE f_org_id = '$org_id' ORDER BY f_date DESC");
    if (mysqli_num_rows($checkresident2) > 0) {
        while ($row = mysqli_fetch_assoc($checkresident2)) {
         $package_id = $row['f_id'];
?>
<tr>
    <td style="font-size:1.8vh;"><?php echo htmlentities($cnt); ?></td>
    <td style="font-size:1.8vh;"><?php echo $row['f_file_subject']; ?></td>
    <td style="font-size:1.8vh;"><?php echo $row['f_file_details']; ?></td>

    <td style="font-size:1.8vh;">
        <!-- Download button -->
        <a href="../assets/uploads/<?php echo $row['f_file']; ?>" class="btn btn-primary" download>Download: <?php echo $row['f_file']; ?></a>
    </td>

    <td style="font-size:1.8vh;"><?php echo $row['f_date']; ?></td>
    <td>
       
                           <div class="btn-group" style="width:100% !important;">
                              <?php if($_SESSION['priviledges'] == 'Coordinator'){ ?>
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUsers<?php echo $package_id; ?>" style="width:100% !important; margin-bottom:0.5vh;"><i class="fas fa-trash"></i></button>
                              <?php } ?>
                              <form action="" method="post">
                                 <div class="modal fade" id="deleteUsers<?php echo $package_id; ?>">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h4 class="modal-title">Deleting File</h4>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                             </button>
                                          </div>
                                          <div class="modal-body">
                                             <p>Are you sure you want to delete this File?<br>This action cannot be undo.</p>
                                          </div>
                                          <div class="modal-footer justify-content-end">
                                             <input type="hidden" name="user_ID_Delete" id="user_ID_Delete" value="<?php echo $package_id; ?>">
                                             <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                             <button type="submit" class="btn btn-danger" id="deleteuser" name="deleteuser">Confirm</button>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
    </td>
</tr>
<?php
    $cnt = $cnt + 1;
    }
} else {
    // Display a message if there are no document requests
    echo '<tr><td colspan="6" class="text-center">No document shared found.</td></tr>';
}
?>
                  </tbody>
               </table>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
               <div class="modal fade" id="add_packages">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title">Add Files</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-12 col-md-12 col-lg-12">
                                  <div class="form-group">
                                      <label for="respondent_middlename">File Subject</label>
                                      <input type="text" name="f_file_subject" id="f_file_subject" class="form-control" placeholder="Enter File name / Subject File" value="" required>
                                  </div>
                              </div>
                              <div class="col-12 col-md-12 col-lg-12">
                                  <div class="form-group">
                                      <label for="respondent_middlename">File Description </label>
                                      <textarea name="f_file_details" id="f_file_details" class="form-control" placeholder="Enter Information about the file" value="" required></textarea>
                                  </div>
                              </div>
                              <div class="col-md-12 col-xs-12 col-sm-12">
                                 <label class="form-label" style="font-weight:bold;">Upload File:<span style="color:red;">*</span></label>
                                 <br>
                                 <input type="file" id="id-upload" name="id-image" class="file-upload"  onchange="updateToID(event)" required>
                              </div>
                           </div>


                        </div>
                        <div class="modal-footer justify-content-end">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                           <button type="submit" class="btn btn-danger" id="submitadd" name="submitadd">Save</button>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
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