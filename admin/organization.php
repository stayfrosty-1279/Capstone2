<?php
include('includes/header.php');

// Reusable function to generate department options
function getDepartmentOptions($selectedDepartment = '')
{
   $departments = [

      "College of Informatics and Computing Studies"
   ];

   $options = '';
   if (empty($selectedDepartment)) {
      $options .= '<option value="">DEPARTMENT</option>';
   }

   foreach ($departments as $department) {
      $selected = ($selectedDepartment == $department) ? 'selected="selected"' : '';
      $options .= "<option value=\"$department\" $selected>$department</option>";
   }

   return $options;
}

// Add this function at the top of your file with other functions
function getOrganizationTypeOptions($selectedType = '')
{
   $types = ["Academic", "Non-Academic", "Independent"];
   $options = '';



   foreach ($types as $type) {
      $selected = ($selectedType == $type) ? 'selected="selected"' : '';
      $options .= "<option value=\"$type\" $selected>$type</option>";
   }

   return $options;
}

if (isset($_POST['deleteuser'])) {
   $USER_IDS = $_POST['user_ID_Delete'];
   $query = "DELETE FROM organization WHERE org_id = '$USER_IDS'";
   $result = mysqli_query($DB, $query);

   if ($result) {
      showSweetAlertSucced2("Success!", "Organization has been deleted successfully.", "success");
   } else {
      $error = "Error deleting Event: " . mysqli_error($DB);
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

// Modify the edit organization code (edit_event section)
if (isset($_POST['edit_event'])) {
   $event_id = $_POST['event_id'];
   $title = $_POST['title'];
   $type = $_POST['type'];
   $department = ($type === 'Independent') ? '' : $_POST['department']; // Only get department if not Independent
   $is_mother_organizationg = $_POST['is_mother_organizationg'];

   // Initialize update query parts
   $update_fields = array(
      "org_name='$title'",
      "org_type='$type'",
      "org_department='$department'",
      "org_ismother='$is_mother_organizationg'"
   );

   // Handle file upload
   if (isset($_FILES['id-image']) && $_FILES['id-image']['error'] == 0) {
      $uploadDir = '../assets/uploads/';
      $new_logo = $title . '-' . basename($_FILES['id-image']['name']);
      $uploadFilePath = $uploadDir . $new_logo;

      // Create directory if it doesn't exist
      if (!is_dir($uploadDir)) {
         mkdir($uploadDir, 0777, true);
      }

      // Move uploaded file
      if (move_uploaded_file($_FILES['id-image']['tmp_name'], $uploadFilePath)) {
         // Add logo field to update query
         $update_fields[] = "org_logo='$new_logo'";
      }
   } else {
      // Check if current logo file exists
      $check_logo_query = "SELECT org_logo FROM organization WHERE org_id='$event_id'";
      $check_result = mysqli_query($DB, $check_logo_query);
      $current_logo = mysqli_fetch_assoc($check_result)['org_logo'];

      if ($current_logo && !file_exists('../assets/uploads/' . $current_logo)) {
         // If logo file is missing, reset to default or empty
         $update_fields[] = "org_logo=''";
      }
   }

   // Construct final update query
   $update_query = "UPDATE organization SET " . implode(", ", $update_fields) . " WHERE org_id='$event_id'";

   // Execute the update query
   if (mysqli_query($DB, $update_query)) {
      showSweetAlertSucced2("Success!", "Organization has been updated successfully.", "success");
   } else {
      $error = "Error updating organization: " . mysqli_error($DB);
      showSweetAlertFailed2("Failed!", $error, "error");
   }
}

// Modify the add organization code (submitadd section)
if (isset($_POST['submitadd'])) {
   $title = $_POST['title'];
   $type = $_POST['type'];
   $department = ($type === 'Independent') ? '' : $_POST['department']; // Only get department if not Independent
   $is_mother_organization = $_POST['is_mother_organization'];

   // Initialize logo variable
   $identification_card = '';

   // Handle file upload
   if (isset($_FILES['id-image']) && $_FILES['id-image']['error'] == 0) {
      $uploadDir = '../assets/uploads/';
      $identification_card = $title . '-' . basename($_FILES['id-image']['name']);
      $uploadFilePath = $uploadDir . $identification_card;

      // Create directory if it doesn't exist
      if (!is_dir($uploadDir)) {
         mkdir($uploadDir, 0777, true);
      }

      // Move uploaded file
      if (!move_uploaded_file($_FILES['id-image']['tmp_name'], $uploadFilePath)) {
         showSweetAlertFailed2("Failed!", "Error uploading logo file.", "error");
         return;
      }
   }

   // Build the INSERT query
   $query = "INSERT INTO organization (org_name, org_logo, org_type, org_department, org_ismother) 
             VALUES ('$title', '$identification_card', '$type', '$department', '$is_mother_organization')";

   // Execute the query
   if (mysqli_query($DB, $query)) {
      showSweetAlertSucced2("Success!", "Organization has been posted successfully.", "success");
   } else {
      $error = "Error inserting Organization: " . mysqli_error($DB);
      showSweetAlertFailed2("Failed!", $error, "error");
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
                     window.location.href = 'organization.php';
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
                         window.location.href = 'organization.php';
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
      width: 100%;
      /* Adjust as needed */
      height: 250px;
      /* Rectangle height */
      overflow: hidden;
      /* Crop excess */
   }

   .id-image {
      width: 100%;
      height: 100%;
      object-fit: fit;
      /* Ensures the image covers the rectangle */
   }

   .upload-label {
      position: absolute;
      bottom: 20px;
      /* Position the label at the bottom */
      left: 175px;
      align-content: center;
      color: white;
      /* Change text color */
      background-color: rgba(0, 0, 0, 0.5);
      /* Semi-transparent background */
      padding: 5px;
      /* Padding around the text */
      border-radius: 5px;
      /* Rounded corners */
      cursor: pointer;
      /* Pointer cursor on hover */
      font-size: 1.5vh;
   }

   .file-upload {
      display: none;
      /* Hide the default file input */
   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper accent-orange">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Manage Organization</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">View Organization</li>
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
               <h4 class="float-left">Organization List </h4>
               <?php if ($_SESSION['priviledges'] == 'Super Admin') { ?>
                  <div class="btn-group float-right">
                     <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_packages"><i class="fas fa-plus"></i> Add Organization</button>
                  </div>
               <?php } ?>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th># </th>
                        <th>Organization</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Mother Organization</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $i = 0;
                     $systemconfig = mysqli_query($DB, "SELECT * FROM organization ORDER BY org_name DESC");
                     if (mysqli_num_rows($systemconfig) > 0) {
                        while ($row = mysqli_fetch_assoc($systemconfig)) {
                           $i++;
                           // Extract event details
                           $package_id = $row['org_id'];
                     ?>
                           <tr>
                              <td><?php echo $i; ?></td>
                              <td>
                                 <?php
                                 $logo_path = '../assets/uploads/' . $row['org_logo'];
                                 if (!empty($row['org_logo']) && file_exists($logo_path)) {
                                    echo '<img src="' . $logo_path . '" class="card-img-top" alt="' . $row['org_name'] . '" style="width:100px; height: 100px; object-fit: fit; margin-right:2vh;">';
                                 } else {
                                    // Display a default image or placeholder
                                    echo '<img src="../images/empty.png" class="card-img-top" alt="No Logo" style="width:100px; height: 100px; object-fit: fit; margin-right:2vh;">';
                                 }
                                 echo $row['org_name'];
                                 ?>
                              </td>

                              <td><?php echo $row['org_department']; ?></td>
                              <td><?php echo $row['org_type']; ?></td>

                              <td>
                                 <?php echo $row['org_ismother']; ?></td>
                              <td>
                                 <center><?php echo $row['org_status']; ?></center>
                                 <!-- Approve Button (only if event_status == 'Pending') -->
                                 <?php if ($row['org_status'] == 'Inactive') { ?>
                                    <br>
                                    <form action="" method="post" style="display:inline;">
                                       <input type="hidden" name="event_id" value="<?php echo $package_id; ?>">
                                       <button type="submit" class="btn btn-success" name="approve_event" style="width:100% !important; margin-bottom:0.5vh;">
                                          <i class="fas fa-check"></i> Active
                                       </button>
                                    </form>
                                 <?php } ?>
                                 <!-- Approve Button (only if event_status == 'Pending') -->
                                 <?php if ($row['org_status'] == 'Active') { ?>
                                    <br>
                                    <form action="" method="post" style="display:inline;">
                                       <input type="hidden" name="event_id" value="<?php echo $package_id; ?>">
                                       <button type="submit" class="btn btn-success" name="disapprove_event" style="width:100% !important; margin-bottom:0.5vh;">
                                          <i class="fas fa-close"></i> Inactive
                                       </button>
                                    </form>
                                 <?php } ?>
                              </td>
                              <td align="center">
                                 <div class="btn-group" style="width:100% !important;">
                                    <?php if ($_SESSION['priviledges'] == 'Super Admin') { ?>
                                       <!-- Edit Button -->
                                       <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editEventModal<?php echo $package_id; ?>" style="width:100% !important; margin-bottom:0.5vh;">
                                          <i class="fas fa-edit"></i>
                                       </button>
                                       <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUsers<?php echo $package_id; ?>" style="width:100% !important; margin-bottom:0.5vh;"><i class="fas fa-trash"></i></button>
                                    <?php } ?>
                                    <form action="" method="post">
                                       <div class="modal fade" id="deleteUsers<?php echo $package_id; ?>">
                                          <div class="modal-dialog">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                   <h4 class="modal-title">Deleting Organization</h4>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                   </button>
                                                </div>
                                                <div class="modal-body">
                                                   <p>Are you sure you want to delete this Organization?<br>This action cannot be undo.</p>
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
                                 <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal fade" id="editEventModal<?php echo $package_id; ?>">
                                       <div class="modal-dialog">
                                          <div class="modal-content">
                                             <div class="modal-header">
                                                <h4 class="modal-title">Edit Organization</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">&times;</span>
                                                </button>
                                             </div>
                                             <div class="modal-body">
                                                <div class="row">
                                                   <div class="col-md-12 col-xs-12 col-sm-12">
                                                      <input type="hidden" id="event_banner<?php echo $package_id; ?>" name="event_banner" value="<?php echo $eventBanner; ?>" />
                                                      <br>
                                                      <label class="form-label" style="font-weight:bold;">Upload Logo:</label>
                                                      <div id="id-image-wrapper" style="border:2px solid gray;">
                                                         <img id="id-image<?php echo $package_id; ?>" src="../assets/uploads/<?php echo $row['org_logo']; ?>" alt="Event Banner" class="id-image">
                                                         <center><label for="id-upload<?php echo $package_id; ?>" class="upload-label">Click To Upload</label></center>
                                                         <input type="file" id="id-upload<?php echo $package_id; ?>" name="id-image" class="file-upload" accept="image/*" onchange="updateToID(event, '<?php echo $package_id; ?>')">
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-xs-12 col-sm-12">
                                                      <br>
                                                      <label class="form-label" style="font-weight:bold;">Organization Name<span style="color:red;">*</span></label>
                                                      <input type="text" class="form-control" id="title<?php echo $package_id; ?>" name="title" value="<?php echo $row['org_name']; ?>" required>
                                                   </div>


                                                   <div class="col-12 col-md-12 col-lg-12">
                                                      <br>
                                                      <div class="form-group">
                                                         <label>Is mother organization?</label><br>
                                                         <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="yes<?php echo $package_id; ?>" name="is_mother_organizationg" value="Yes" style="cursor:pointer;"
                                                               <?php if ($row['org_ismother'] == 'Yes') {
                                                                  echo 'checked';
                                                               } ?>>
                                                            <label class="form-check-label" for="yes">Yes</label>
                                                         </div>
                                                         <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="no<?php echo $package_id; ?>" name="is_mother_organizationg" value="No" style="cursor:pointer;" <?php if ($row['org_ismother'] == 'No') {
                                                                                                                                                                                                                  echo 'checked';
                                                                                                                                                                                                               } ?>>
                                                            <label class="form-check-label" for="no">No</label>
                                                         </div>
                                                      </div>
                                                   </div>



                                                   <div class="col-12 col-md-12 col-lg-12">
                                                      <div class="form-group">
                                                         <label for="accessLevel">Type</label>
                                                         <select class="form-control select2" name="type" id="type">
                                                            <?php echo getOrganizationTypeOptions($row['org_type']); ?>
                                                         </select>

                                                      </div>
                                                   </div>

                                                   <!-- In the edit modal form, replace the department select with: -->
                                                   <div class="col-12 col-md-12 col-lg-12">
                                                      <div class="form-group">
                                                         <label for="accessLevel">Department</label>
                                                         <select class="form-control select2" name="department" id="department"
                                                            data-dropdown-css-class="select2-orange" style="width: 100%">
                                                            <?php echo getDepartmentOptions($row['org_department']); ?>
                                                         </select>
                                                      </div>
                                                   </div>

                                                </div>
                                             </div>
                                             <div class="modal-footer justify-content-end">
                                                <input type="hidden" name="event_id" value="<?php echo $package_id; ?>">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="edit_event">Save Changes</button>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </td>
                           </tr>
                     <?php
                        }
                     }
                     ?>
                  </tbody>
               </table>
            </div>
            <script>
               function updateToID(event, id) {
                  const file = event.target.files[0];
                  const imgElement = document.getElementById('id-image' + id);

                  if (file) {
                     const reader = new FileReader();
                     reader.onload = function(e) {
                        imgElement.src = e.target.result;
                     };
                     reader.readAsDataURL(file);
                  }
               }

               function updateToIDEdit(event, package_id) {
                  const file = event.target.files[0];
                  const imgElement = document.getElementById('id-image' + package_id);

                  if (file) {
                     const reader = new FileReader();
                     reader.onload = function(e) {
                        imgElement.src = e.target.result; // Update the image source to display the selected image
                     };
                     reader.readAsDataURL(file);
                  }
               }
            </script>
            <form action="" method="post" enctype="multipart/form-data">
               <div class="modal fade" id="add_packages">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title">Add Event</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <div class="row">
                              <div class="col-md-12 col-xs-12 col-sm-12">
                                 <br>
                                 <label class="form-label" style="font-weight:bold;">Upload Organization Logo:<span style="color:red;">*</span></label>
                                 <div id="id-image-wrapper" style="border:2px solid gray;">
                                    <img id="id-image" src="../images/empty.jpg" alt="Default ID" class="id-image">
                                    <center> <label for="id-upload" class="upload-label">Click To Upload</label></center>
                                    <input type="file" id="id-upload" name="id-image" class="file-upload" accept="image/*" onchange="updateToID(event)" required>
                                 </div>
                              </div>


                              <div class="col-md-12 col-xs-12 col-sm-12">
                                 <br>
                                 <label class="form-label" style="font-weight:bold;">Organization Name<span style="color:red;">*</span></label>
                                 <input type="text" class="form-control" id="title" name="title" value="" required>
                              </div>
                              <div class="col-md-12 col-xs-12 col-sm-12" style="display:none;">
                                 <br>
                                 <label class="form-label" style="font-weight:bold;">Organization Description<span style="color:red;">*</span></label>
                                 <textarea class="form-control" id="description" name="description"></textarea>
                              </div>
                              <div class="col-md-12 col-xs-12 col-sm-12" style="display:none;">
                                 <br>
                                 <label class="form-label" style="font-weight:bold;">Vision<span style="color:red;">*</span></label>
                                 <textarea class="form-control" id="vision" name="vision"></textarea>
                              </div>
                              <div class="col-md-12 col-xs-12 col-sm-12" style="display:none;">
                                 <br>
                                 <label class="form-label" style="font-weight:bold;">Mission<span style="color:red;">*</span></label>
                                 <textarea class="form-control" id="vision" name="mission"></textarea>
                              </div>


                              <div class="col-12 col-md-12 col-lg-12">
                                 <br>
                                 <div class="form-group">
                                    <label>Is mother organization?</label><br>
                                    <div class="form-check form-check-inline">
                                       <input class="form-check-input" type="radio" id="yes" name="is_mother_organization" value="Yes" style="cursor:pointer;">
                                       <label class="form-check-label" for="yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                       <input class="form-check-input" type="radio" id="no" name="is_mother_organization" value="No" style="cursor:pointer;" checked>
                                       <label class="form-check-label" for="no">No</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-md-12 col-lg-12">
                                 <div class="form-group">
                                    <label for="accessLevel">Type</label>
                                    <select class="form-control select2" name="type" id="type">
                                       <?php echo getOrganizationTypeOptions(); ?>
                                    </select>
                                 </div>
                              </div>

                              <!-- In the add modal form, replace the department select with: -->
                              <div class="col-12 col-md-12 col-lg-12">
                                 <div class="form-group">
                                    <label for="accessLevel">Department</label>
                                    <select class="form-control select2" name="department" id="department"
                                       data-dropdown-css-class="select2-orange" style="width: 100%">
                                       <?php echo getDepartmentOptions(); ?>
                                    </select>
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
   document.addEventListener('DOMContentLoaded', function() {
      // Enhanced toggle function with better element targeting and validation
      function toggleDepartmentField(typeValue, departmentContainer) {
         if (!departmentContainer) return;

         const departmentSelect = departmentContainer.querySelector('select[name="department"]');
         if (!departmentSelect) return;

         if (typeValue === 'Independent') {
            departmentContainer.style.display = 'none';
            departmentSelect.value = '';
            departmentSelect.removeAttribute('required');
         } else {
            departmentContainer.style.display = 'block';
            departmentSelect.setAttribute('required', 'required');
         }
      }

      // Setup for Add Organization modal
      const addModal = document.getElementById('add_packages');
      if (addModal) {
         const typeSelect = addModal.querySelector('select[name="type"]');
         // Find the department container within the add modal specifically
         const departmentContainer = addModal.querySelector('select[name="department"]').closest('.col-12');

         if (typeSelect && departmentContainer) {
            // Set initial state
            toggleDepartmentField(typeSelect.value, departmentContainer);

            // Add change event listener
            typeSelect.addEventListener('change', function() {
               toggleDepartmentField(this.value, departmentContainer);
            });
         }
      }

      // Setup for Edit Organization modals
      document.querySelectorAll('[id^="editEventModal"]').forEach(modal => {
         const typeSelect = modal.querySelector('select[name="type"]');
         const departmentContainer = modal.querySelector('select[name="department"]').closest('.col-12');

         if (typeSelect && departmentContainer) {
            // Set initial state
            toggleDepartmentField(typeSelect.value, departmentContainer);

            // Add change event listener
            typeSelect.addEventListener('change', function() {
               toggleDepartmentField(this.value, departmentContainer);
            });
         }
      });
   });
</script>
<?php
   include('includes/footer.php');
   ?>