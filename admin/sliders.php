<?php
   include('includes/header.php');
   
                        $org_id = $_SESSION['org_id'];
   if(isset($_POST['deleteuser']))
   {
       $USER_IDS = $_POST['user_ID_Delete'];
       $query = "DELETE FROM sliders WHERE s_id = '$USER_IDS'";
       $result = mysqli_query($DB, $query);
   
       if ($result) {         
           showSweetAlertSucced2("Success!", "Slider has been deleted successfully.", "success");  
       } else {
           $error = "Error deleting Slider: " . mysqli_error($DB);
           showSweetAlertFailed2("Failed!", $error, "error");
       }
   }
   if (isset($_POST['approve_event'])) {
       $event_id = $_POST['event_id'];
   
       // Update event status to 'Approved'
       $query = "UPDATE organization SET org_status = 'Active' WHERE org_id = '$event_id'";
       $result = mysqli_query($DB, $query);
   
       if ($result) {
           showSweetAlertSucced2("Success!", "Organization has been activated successfully.", "success");
       } 
   }
   if (isset($_POST['disapprove_event'])) {
       $event_id = $_POST['event_id'];
   
       // Update event status to 'Approved'
       $query = "UPDATE organization SET org_status = 'Inactive' WHERE org_id = '$event_id'";
       $result = mysqli_query($DB, $query);
   
       if ($result) {
           showSweetAlertSucced2("Success!", "Organization has been deactivated successfully.", "success");
       } 
   }
   if (isset($_POST['edit_event'])) {
       $event_id = $_POST['event_id'];
       $title = $_POST['title'];
       $description = $_POST['description'];
       $vision = $_POST['vision'];
       $mission = $_POST['mission'];
       $type = $_POST['type'];
       $department = $_POST['department'];
   
       // File upload logic
       if ($_FILES['id-image']['name']) {
           $event_banner = basename($_FILES['id-image']['name']);
           $target_dir = "../assets/uploads/";
           $target_file = $target_dir . $event_banner;
           move_uploaded_file($_FILES['id-image']['tmp_name'], $target_file);
       } else {
           // If no new image uploaded, use the old one
           $event_banner = $_POST['event_banner']; // fetch the original banner if no new one is uploaded
       }
   
       // Update event in the database
       $update_query = "UPDATE organization SET 
                           org_name='$title', 
                           org_description='$description', 
                           org_vision='$vision',
                           org_mission='$mission',
                           org_type='$type',
                           org_department='$department'
                        WHERE org_id='$event_id'";
   
       if (mysqli_query($DB, $update_query)) {
           showSweetAlertSucced2("Success!", "Organization has been updated successfully.", "success");
       } else {
           showSweetAlertFailed2("Failed!", $error, "error");
       }
   }

   if(isset($_POST['submitadd'])){
      
      $time = time();

           if (isset($_FILES['id-image']) && $_FILES['id-image']['error'] == 0) {
               $uploadDir2 = '../assets/uploads/';
               $identification_card = $time.'-'.basename($_FILES['id-image']['name']);
               $uploadFilePath2 = $uploadDir2 . $identification_card;
               if (!is_dir($uploadDir2)) {mkdir($uploadDir2, 0777, true);}
               if (move_uploaded_file($_FILES['id-image']['tmp_name'], $uploadFilePath2)) {}
           }
   
       // Build the INSERT query
       $query = "INSERT INTO sliders (s_image, s_org_id) VALUES ('$identification_card','$org_id')";
   
       // Execute the query
       $result = mysqli_query($DB, $query);
   
       // Check if the query was successful
       if ($result) {
           showSweetAlertSucced2("Success!", "Slider has been posted successfully.", "success");
       } else {
           $error = "Error inserting Slider: " . mysqli_error($DB);
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
                     window.location.href = 'sliders.php';
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
                         window.location.href = 'sliders.php';
                       });
                   }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
                   </script>";
       }
   include('includes/topbar.php');
   include('includes/sidebar.php');
   include('includes/modals.php');
   ?>
<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
<!-- Summernote JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<style>
   #id-image-wrapper {
   position: relative;
   width: 100%; /* Adjust as needed */
   height: 250px; /* Rectangle height */
   overflow: hidden; /* Crop excess */
   }
   .id-image {
   width: 100%;
   height: 100%;
   object-fit: fit; /* Ensures the image covers the rectangle */
   }
   .upload-label {
   position: absolute;
   bottom: 20px; /* Position the label at the bottom */
   left:175px;
   align-content: center;
   color: white; /* Change text color */
   background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
   padding: 5px; /* Padding around the text */
   border-radius: 5px; /* Rounded corners */
   cursor: pointer; /* Pointer cursor on hover */
   font-size:1.5vh;
   }
   .file-upload {
   display: none; /* Hide the default file input */
   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper accent-orange">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Manage Sliders</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">View Sliders</li>
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
               <h4 class="float-left">Sliders List </h4>
               <?php if($_SESSION['priviledges'] == 'Coordinator'){ ?>
               <div class="btn-group float-right">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_packages"><i class="fas fa-plus"></i> Add Sliders</button>
               </div>
               <?php } ?>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th># </th>
                        <th>Slider Images</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $i = 0;
                        $systemconfig = mysqli_query($DB, "SELECT * FROM sliders WHERE s_org_id = '$org_id' ORDER BY s_id DESC");
                        if (mysqli_num_rows($systemconfig) > 0) 
                        { 
                            while ($row = mysqli_fetch_assoc($systemconfig)) 
                            {
                                $i++;
                                // Extract event details
                                $package_id = $row['s_id'];
                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                           <img src="../assets/uploads/<?php echo $row['s_image']; ?>" class="card-img-top" alt="<?php echo $row['s_image']; ?>" style="width:100%; height: 100%; object-fit: fit;">
                        </td>
                        <td align="center">
                           <div class="btn-group" style="width:100% !important;">
                              <?php if($_SESSION['priviledges'] == 'Coordinator'){ ?>
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUsers<?php echo $package_id; ?>" style="width:100% !important; margin-bottom:0.5vh;"><i class="fas fa-trash"></i></button>
                              <?php } ?>
                              <form action="" method="post">
                                 <div class="modal fade" id="deleteUsers<?php echo $package_id; ?>">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h4 class="modal-title">Deleting Slider</h4>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                             </button>
                                          </div>
                                          <div class="modal-body">
                                             <p>Are you sure you want to delete this Slider?<br>This action cannot be undo.</p>
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
                        }
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
                           <h4 class="modal-title">Add Slider</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-md-12 col-xs-12 col-sm-12">
                                 <br>
                                 <label class="form-label" style="font-weight:bold;">Upload Slider Image:<span style="color:red;">*</span></label>
                                 <div id="id-image-wrapper" style="border:2px solid gray;">
                                    <img id="id-image" src="../images/empty.jpg" alt="Default ID" class="id-image">
                                    <center> <label for="id-upload" class="upload-label">Click To Upload</label></center>
                                    <input type="file" id="id-upload" name="id-image" class="file-upload" accept="image/*" onchange="updateToID(event)" required>
                                 </div>
                              </div>
                              <script>
                                 function updateToID(event) {
                                     const file = event.target.files[0];
                                     const imgElement = document.getElementById('id-image');
                                     
                                     if (file) {
                                         const reader = new FileReader();
                                         reader.onload = function(e) {
                                             imgElement.src = e.target.result; // Update the image source to display the selected image
                                         };
                                         reader.readAsDataURL(file);
                                     }
                                 }
                              </script>
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
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
<script>
   $(document).ready(function() {
       $('.open-modal').click(function(e) {
           e.preventDefault(); // Prevent default anchor behavior
           var modalId = $(this).data('modal-id'); // Get the ID of the modal
           $('#imageModal_payment' + modalId).modal('show'); // Show the modal
       });
   });
</script>
<script>
   $(document).ready(function() {
       // Initialize Summernote for the textarea with the class "summernote"
       $('.summernote').summernote({
           height: 200 // Set the height of the Summernote editor
           // You can add more options here as needed
       });
   });
</script>
<?php
   include('includes/footer.php');
   ?>