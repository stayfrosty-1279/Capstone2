<?php
include('includes/database_config.php');

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<?php

if (isset($_POST['deleteuser'])) {

    $USER_IDS = $_POST['user_ID_Delete'];

    $query = "DELETE FROM files WHERE f_id = '$USER_IDS'";
    $result = mysqli_query($DB, $query);

    if ($result) {
        unset($_POST['staff_ID_Delete']);
        unset($_POST['deletestaff']);
        showSweetAlertSucced3("Success!", "File has been deleted successfully.", "success");
    } else {
        unset($_POST['staff_ID_Delete']);
        unset($_POST['deletestaff']);
        $error = "Error deleting File: " . mysqli_error($DB);
        showSweetAlertFailed3("Failed!", $error, "error");
    }
}

if (isset($_POST['submitadd'])) {

    $time = time();
    $ev_org_id = $_SESSION['org_id'];
    $myid = $_SESSION['user_id'];
    $f_file_subject = $_POST['f_file_subject'];
    $f_file_details = $_POST['f_file_details'];
    if (isset($_FILES['id-image']) && $_FILES['id-image']['error'] == 0) {
        $uploadDir2 = 'assets/uploads/';
        $identification_card = $time . '-' . basename($_FILES['id-image']['name']);
        $uploadFilePath2 = $uploadDir2 . $identification_card;
        if (!is_dir($uploadDir2)) {
            mkdir($uploadDir2, 0777, true);
        }
        if (move_uploaded_file($_FILES['id-image']['tmp_name'], $uploadFilePath2)) {
        }
    }

    // Build the INSERT query
    $query = "INSERT INTO files (f_user_id, f_file, f_org_id, f_file_subject, f_file_details) VALUES ('$myid','$identification_card','$ev_org_id', '$f_file_subject', '$f_file_details')";

    // Execute the query
    $result = mysqli_query($DB, $query);

    // Check if the query was successful
    if ($result) {
        showSweetAlertSucced3("Success!", "File has been uploaded successfully.", "success");
    } else {
        $error = "Error inserting file: " . mysqli_error($DB);
        showSweetAlertFailed3("Failed!", $error, "error");
    }
}

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
                     window.location.href = 'my_files.php';
                   });
               }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
               </script>";
}
function showSweetAlertFailed3($title, $message, $type)
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
                       });
                   }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
                   </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEUConnect</title>
 <!-- Bootstrap ICON CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <!-- Font Awesome CDN -->
  <link href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <!-- FONT FAMILY -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


  <!-- CSS LINK -->
  <link rel="stylesheet" href="style/style.css">




        <!-- Bootstrap JS (including jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
		  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
  
    <!-- Include jsPDF and QRious libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qr.js/1.1.0/qr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

 <style>
        .file-sharing-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #102c57;
        }

        .header-title {
            color: #102c57;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .add-files-btn {
            background: linear-gradient(135deg, #012952, #1e56a0);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .add-files-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(1, 41, 82, 0.2);
        }

        .files-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1rem;
        }

        .files-table th {
            background-color: #102c57;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .files-table th:first-child {
            border-top-left-radius: 10px;
        }

        .files-table th:last-child {
            border-top-right-radius: 10px;
        }

        .files-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .files-table tr:hover {
            background-color: #faf5ef;
        }

        .download-btn {
            background-color: #102c57;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            background-color: #1e56a0;
            transform: translateY(-2px);
            color: white;
        }

        .modal-content {
            background-color: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #102c57;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #102c57;
            outline: none;
        }
    </style>
</head>
<?php
include 'includes/modals.php';
?>

<body style=" overflow-x: hidden !important;">
  
	 <!-- Top Info Bar -->
  <div class="container-fluid bg-primary px-5 d-none d-lg-block">
    <div class="row gx-0 align-items-center" style="height: 45px;">
      <div class="col-lg-8 text-center text-lg-start mb-lg-0">
        <div class="d-flex flex-wrap link-text">
          <a href="https://neu.edu.ph/main/#" class="text-light me-4 text-decoration-none"><i class="bi bi-geo-alt-fill me-2"></i>9 Central Ave, New Era, Quezon City, 1107 Metro Manila</a>
          <a href="https://neu.edu.ph/main/#" class="text-light me-4 text-decoration-none"><i class="bi bi-telephone-fill me-2"></i>(02) 8981 4221</a>
          <a href="https://neu.edu.ph/main/#" class="text-light me-0 text-decoration-none"><i class="bi bi-envelope-at-fill me-2"></i>info@neu.edu.ph</a>
        </div>
      </div>
    </div>
  </div>
 
        <?php

        include('includes/navBar.php');
        include('javascript/javascripts.php');
        ?>
<div class="file-sharing-container">
        <div class="header-container">
            <h2 class="header-title">ORGANIZATION SHARED FILES</h2>
            <button type="button" class="add-files-btn" data-toggle="modal" data-target="#add_packages">
                <i class="fas fa-plus"></i> Add Files
            </button>
        </div>

        <table id="zctb" class="files-table display table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Uploaded By</th>
                    <th>File Name</th>
                    <th>Details</th>
                    <th>File</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 1;
                $myuserid = $_SESSION['user_id'];
                $org_id = $_SESSION['org_id'];

                $checkresident2 = mysqli_query($DB, "SELECT * FROM files WHERE f_org_id = '$org_id' ORDER BY f_date DESC");
                if (mysqli_num_rows($checkresident2) > 0) {
                    while ($row = mysqli_fetch_assoc($checkresident2)) {
                        $package_id = $row['f_id'];
                        $user_id = $row['f_user_id'];

                        $name = "Coordinator";

                        $checkresident23 = mysqli_query($DB, "SELECT * FROM users WHERE user_id = '$user_id'");
                        if (mysqli_num_rows($checkresident23) > 0) {
                            while ($row3 = mysqli_fetch_assoc($checkresident23)) {
                                $name  = $row3['firstName'] . ' ' . $row3['middleName'] . ' ' . $row3['lastName'];
                            }
                        }
                ?>
                        <tr>
                            <td><?php echo htmlentities($cnt); ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $row['f_file_subject']; ?></td>
                            <td><?php echo $row['f_file_details']; ?></td>
                            <td>
                                <a href="assets/uploads/<?php echo $row['f_file']; ?>" class="download-btn" download>
                                    <i class="fas fa-download"></i> Download: <?php echo $row['f_file']; ?>
                                </a>
                            </td>
                            <td><?php echo $row['f_date']; ?></td>
                        </tr>
                <?php
                        $cnt = $cnt + 1;
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">No document shared found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Files Modal -->
    <div class="modal fade" id="add_packages">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Files</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File Subject</label>
                            <input type="text" name="f_file_subject" class="form-control" placeholder="Enter File name / Subject File" required>
                        </div>
                        <div class="form-group">
                            <label>File Description</label>
                            <textarea name="f_file_details" class="form-control" placeholder="Enter Information about the file" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Upload File <span style="color:red;">*</span></label>
                            <input type="file" name="id-image" class="form-control file-upload" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="add-files-btn" name="submitadd">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function generatePDF(elementId) {
            const element = document.getElementById(elementId);
            const opt = {
                margin: 1,
                filename: 'receipt.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };
            html2pdf().from(element).set(opt).save();
        }
    </script>
    <br><br>

</body>

</html>