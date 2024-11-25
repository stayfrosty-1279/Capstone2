<?php
    include('includes/header.php');
    include('includes/topbar.php');
    include('includes/sidebar.php');
    include('includes/modals.php');

    $org_id = $_SESSION['org_id'];

    if(isset($_POST['updateorg_name'])){

        $org_name = $_POST['org_name'];

        $sql = "UPDATE organization SET org_name = ? WHERE org_id = ?";
        $query = mysqli_prepare($DB, $sql);
        mysqli_stmt_bind_param($query, "si", $org_name, $org_id);

        if (mysqli_stmt_execute($query)) {
            showSweetAlertSucced333("Updated Successfully!", "Organization information has been updated successfully!", "success");
        } else {
            echo "Error updating record: " . mysqli_error($DB);
        }
    }

    if(isset($_POST['updateorg_description'])){

        $org_description = $_POST['org_description'];

        $sql = "UPDATE organization SET org_description = ? WHERE org_id = ?";
        $query = mysqli_prepare($DB, $sql);
        mysqli_stmt_bind_param($query, "si", $org_description, $org_id);

        if (mysqli_stmt_execute($query)) {
            showSweetAlertSucced333("Updated Successfully!", "Organization information has been updated successfully!", "success");
        } else {
            echo "Error updating record: " . mysqli_error($DB);
        }
    }
    if(isset($_POST['updateorg_vision'])){

        $org_vision = $_POST['org_vision'];

        $sql = "UPDATE organization SET org_vision = ? WHERE org_id = ?";
        $query = mysqli_prepare($DB, $sql);
        mysqli_stmt_bind_param($query, "si", $org_vision, $org_id);

        if (mysqli_stmt_execute($query)) {
            showSweetAlertSucced333("Updated Successfully!", "Organization information has been updated successfully!", "success");
        } else {
            echo "Error updating record: " . mysqli_error($DB);
        }
    }
    if(isset($_POST['updateorg_mission'])){

        $org_mission = $_POST['org_mission'];

        $sql = "UPDATE organization SET org_mission = ? WHERE org_id = ?";
        $query = mysqli_prepare($DB, $sql);
        mysqli_stmt_bind_param($query, "si", $org_mission, $org_id);

        if (mysqli_stmt_execute($query)) {
            showSweetAlertSucced333("Updated Successfully!", "Organization information has been updated successfully!", "success");
        } else {
            echo "Error updating record: " . mysqli_error($DB);
        }
    }



    if(isset($_POST['gcashimagesubmit'])){
            $path1 = $_FILES['gcashimage']['name'];
            $path1_tmp = $_FILES['gcashimage']['tmp_name'];

            if ($path1 != '') 
            {
                $ext1 = pathinfo($path1, PATHINFO_EXTENSION);
                $requirement1 = basename($path1, '.' . $ext1);
                if ($ext1 != 'jpg' && $ext1 != 'png' && $ext1 != 'jpeg') 
                {
                    $validation = 0;
                    $registration_error .= 'You must have to upload jpg, png or jpeg format only.<br>';
                }
            } 
            else 
            {
                $validation = 0;
                $registration_error .= 'You must have to upload letter of intent<br>';
            }
                $id_timestamp = time();
                $requirement_upload1 = $id_timestamp.'-'.$requirement1.'-.'. $ext1;
                move_uploaded_file($path1_tmp, '../assets/uploads/'.$requirement_upload1);

        $sql = "UPDATE organization SET 
                org_logo = ?
                WHERE org_id = ?";

        $query = mysqli_prepare($DB, $sql);

        mysqli_stmt_bind_param($query, "si", 
            $requirement_upload1, 
            $org_id // Note that $school_id should be the last parameter and must be an integer
        );

        if (mysqli_stmt_execute($query)) {
            showSweetAlertSucced333("LOGO Updated!", "Logo has been updated successfully!", "success");
        } else {
            // Handle error
            echo "Error updating record: " . mysqli_error($DB);
        }
    }



    function showSweetAlertSucced333($title, $message, $type) {
               echo "
               <script>
               setTimeout(function() {
                   Swal.fire({
                       title: '$title',
                       text: '$message',
                       icon: '$type',
                       confirmButtonText: 'OK'
                   }).then(function() {
                     window.location.href = 'org_settings.php';
                   });
               }, 500); // Delay in milliseconds (e.g., 1000ms = 1 second)
               </script>";
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper accent-orange">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Organization Page Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 accent-orange">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Organization Page Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <style>
        p {
            margin-top: 0;
            margin-bottom: 0rem !important;
        }
    </style>
    <!-- Main content -->
    <?php 
            $checkresident = mysqli_query($DB, "SELECT * FROM organization WHERE org_id = '$org_id'");
            if (mysqli_num_rows($checkresident) > 0) 
            {
                while ($residentrecord = mysqli_fetch_assoc($checkresident)) 
                {
                    $org_name = $residentrecord['org_name'];
                    $org_description = $residentrecord['org_description'];
                    $org_vision = $residentrecord['org_vision'];
                    $org_mission = $residentrecord['org_mission'];
                    $org_logo = $residentrecord['org_logo'];
                    $org_type = $residentrecord['org_type'];
                    $org_department = $residentrecord['org_department'];
                }
            }
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body" style="min-height:80vh;">
                    <div class="row">
                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <form action="" method="POST">
                                <div style="padding:2vh; border:1px solid black; border-radius:2vh; min-height:25vh;"><br>
                                    <label style="font-size:2.5vh;">Organization Name</label><br>
                                    <input type="text" id="org_name" name="org_name" class="form-control" placeholder="" value="<?php echo $org_name; ?>"/>
                                    <br>
                                    <center>
                                    <button class="btn btn-primary" id="updateorg_name" name="updateorg_name" type="submit" style="font-size:1.7vh;">Save Settings</button></center>
                                    <br>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <form action="" method="POST">
                                <div style="padding:2vh; border:1px solid black; border-radius:2vh; min-height:25vh;"><br>
                                    <label style="font-size:2.5vh;">Organization Description</label><br>
                                    <textarea type="number" id="org_description" name="org_description" class="form-control"><?php echo $org_description; ?></textarea>
                                    <br>
                                    <center>
                                    <button class="btn btn-primary" id="updateorg_description" name="updateorg_description" type="submit" style="font-size:1.7vh;">Save Settings</button></center>
                                    <br>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6">
                            <div style="padding:2vh; border:1px solid black; border-radius:2vh; min-height:25vh;">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <img style="width:80%; height:80%;" src="../assets/uploads/<?php echo $org_logo; ?>" alt="form"/>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12">
                                        <br>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                        <label style="font-size:2.5vh;">Organization Logo</label><br>
                                                <div class="form-group">
                                                    <input type="file" class="form-control" id="gcashimage" name="gcashimage" style="height:4.5vh;">
                                                </div>
                                        <br>
                                        <center>
                                        <button class="btn btn-primary" id="gcashimagesubmit" name="gcashimagesubmit" type="submit" style="font-size:1.7vh;">Save Settings</button></center>
                                 
                                        </form>
                                    </div>
                                </div>   
                            </div>
                        </div>


                        <div class="col-md-6 col-xs-6 col-sm-6">
                        <br>
                            <form action="" method="POST">
                                <div style="padding:2vh; border:1px solid black; border-radius:2vh; min-height:25vh;"><br>
                                    <label style="font-size:2.5vh;">Vision</label><br>
                                    <textarea type="number" id="org_vision" name="org_vision" class="form-control"><?php echo $org_vision; ?></textarea>
                                    <br>
                                    <center>
                                    <button class="btn btn-primary" id="updateorg_vision" name="updateorg_vision" type="submit" style="font-size:1.7vh;">Save Settings</button></center>
                                    <br>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 col-xs-6 col-sm-6">
                        <br>
                            <form action="" method="POST">
                                <div style="padding:2vh; border:1px solid black; border-radius:2vh; min-height:25vh;"><br>
                                    <label style="font-size:2.5vh;">Mission</label><br>
                                    <textarea type="number" id="org_mission" name="org_mission" class="form-control"><?php echo $org_mission; ?></textarea>
                                    <br>
                                    <center>
                                    <button class="btn btn-primary" id="updateorg_mission" name="updateorg_mission" type="submit" style="font-size:1.7vh;">Save Settings</button></center>
                                    <br>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>


                                                            

<?php
include('includes/footer.php');
?>