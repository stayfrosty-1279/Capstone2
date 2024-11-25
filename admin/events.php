<?php
include('includes/header.php');

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
$mail = new PHPMailer();

$ev_org_id = $_SESSION['org_id'];

if (isset($_POST['deletebooked'])) {
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
if (isset($_POST['deletestaff'])) {

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

if (isset($_POST['bookamenities'])) {
   $additionalText = $_POST['additionalText'];
   $eve_id = $_POST['eve_id'];


   $query = "INSERT INTO booked (
                b_event_id, 
                b_description
            )
            VALUES 
            (
                '$eve_id', 
                '$additionalText'
                )";

   $result = mysqli_query($DB, $query);

   if ($result) {
      unset($_POST['addstaffer']);
      showSweetAlertSucced2("Success!", "Amenities Booking has been submitted successfully.", "success");
   } else {
      $error = "Error saving submitting request: " . mysqli_error($DB);
      unset($_POST['addstaffer']);
      showSweetAlertFailed2("Failed!", $error, "error");
   }
}

if (isset($_POST['updatestaffInfo'])) {
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

   $TIME = $ev_time_from . ' TO ' . $ev_time_to;

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

if (isset($_POST['addstaffer'])) {
   $ev_name = $_POST['ev_name'];
   $ev_description = $_POST['ev_description'];
   $ev_venue = $_POST['ev_venue'];
   $ev_date = $_POST['ev_date'];
   $ev_time_from = $_POST['ev_time_from'];
   $ev_time_to = $_POST['ev_time_to'];

   // Convert from 24-hour to 12-hour AM/PM format
   $ev_time_from = date("g:i A", strtotime($ev_time_from)); // Converts "13:00" to "1:00 PM"
   $ev_time_to = date("g:i A", strtotime($ev_time_to));     // Converts "03:00" to "3:00 AM"

   $ev_timers = $ev_time_from . ' TO ' . $ev_time_to;
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

if (isset($_POST['cancelevent'])) {

   $ev_id = $_POST['ev_id'];
   $query = "UPDATE events SET ev_status = 'Cancelled' WHERE ev_id = $ev_id";
   $result = mysqli_query($DB, $query);
   if ($result) {

      $residentqeuryg1 = mysqli_query($DB, "SELECT * FROM participants WHERE p_event_id='$ev_id' AND p_status = 'Approved'");
      if (mysqli_num_rows($residentqeuryg1) > 0) {
         while ($rowg1 = mysqli_fetch_assoc($residentqeuryg1)) {
            $part_email = $rowg1['p_email'];
            $part_name = $rowg1['p_fullname'];
            $message = '
                              <html>
                                  <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 3vh;">
                                      <center>
                                          <!-- Container for the email body -->
                                          <div style="background-color: #ffffff; max-width: 800px; margin: 20px auto; padding: 20px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); border: 3px solid red;">
                                              <!-- Header Section -->
                                              <h2 style="font-size: 32px; color: red; margin-bottom: 10px; text-align: center; font-family: Georgia, serif;"><b>Event Cancellation Notice</b></h2>

                                              <p style="font-size: 18px; color: #666; margin-bottom: 20px; text-align: center;">
                                                  We regret to inform you that the event has been cancelled.
                                              </p>
                                              

                                              <p style="font-size: 18px; color: #666; margin-bottom: 30px; text-align: center;">
                                                  We apologize for any inconvenience this may cause and appreciate your understanding.
                                              </p>

                                              <div style="margin-top: 30px; text-align: center;">
                                                  <p style="font-size: 18px; color: #666;">Thank you for your support!</p>
                                              </div>

                                              <!-- Footer Section -->
                                              <p style="font-size: 18px; color: #666; margin-top: 30px; text-align: center;">
                                                  Best regards,<br>
                                                  <strong>The NEUCONNECT Team</strong>
                                              </p>
                                          </div>

                                          <!-- Optional footer information -->
                                          <div style="margin-top: 20px; font-size: 12px; color: #999;">
                                              <p>NEUCONNECT Inc, 123 Main Street, City, Country.</p>
                                          </div>
                                      </center>
                                  </body>
                              </html>';


            try {
               $cust_email = $part_email;
               $cust_name = $part_name;

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
               $mail->AddReplyTo("finalsemcapstone@gmail.com", "NEUCONNECT"); //when in hosting change this the email of web.z
               $mail->Subject = 'Participating Certificate';
               $EMAIL_MESSAGE = $message;
               $mail->MsgHTML($EMAIL_MESSAGE);
               if (!$mail->Send()) {
                  $success_message =  "Error while sending Email!";
                  var_dump($mail);
               } else {
                  $success_message =  "Email has been sent successfully!";
               }
            } catch (Exception $e) {
               echo 'Message could not be sent!';
               echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
         }
      }

      showSweetAlertSucced2("Success!", "Event has been cancelled successfully.", "success");
   } else {
      $error = "Error updating data: " . mysqli_error($DB);
      unset($_POST['updateprofileinfo']);
      showSweetAlertFailed2("Failed!", $error, "error");
   }
}

if (isset($_POST['completedevent'])) {
   $event_name = $_POST['event_name'];
   $ev_id = $_POST['ev_id'];
   $query = "UPDATE events SET ev_status = 'Completed' WHERE ev_id = $ev_id";
   $result = mysqli_query($DB, $query);

   if ($result) {
      $residentqeuryg1 = mysqli_query($DB, "SELECT * FROM participants WHERE p_event_id='$ev_id' AND p_status = 'Approved'");
      if (mysqli_num_rows($residentqeuryg1) > 0) {
         while ($rowg1 = mysqli_fetch_assoc($residentqeuryg1)) {
            $part_email = $rowg1['p_email'];
            $part_name = $rowg1['p_fullname'];

            $message = '
                         <html>
<body style="font-family: Arial, sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%); margin: 0; padding: 5vh;">
    <center>
        <!-- Main Certificate Container - with border -->
       <div style="background-color: #ffffff; max-width: 765px; margin: 18px auto; padding: 15px; border-radius: 15px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); position: relative; overflow: hidden; border: 3px solid #3d83cc; border-top-width: 10px;">
            <!-- Decorative Elements -->
     

            <!-- Certificate Content -->
            <div style="position: relative; z-index: 1;">
                <!-- Header -->
                <h2 style="font-size: 34px; color: #2C3E50; margin-bottom: 18px; text-align: center; font-family: Georgia, serif; letter-spacing: 2px;">
                    <span style="display: block; font-size: 22px; color: #3d83cc; margin-bottom: 9px; font-weight: normal;">NEUCONNECT</span>
					
					
                    Certificate of Participation
                </h2> 
                <!-- Decorative Line -->
               <div style="width: 90px; height: 3px; background-color: #3d83cc; margin: 18px auto;"></div>
                <p style="font-size: 18px; color: #3d83cc; margin: 27px 0; text-align: center;">
                    This is to certify that
                </p>
                <!-- Participant Name -->
                <h1 style="font-size: 38px; color: #2C3E50; font-weight: bold; margin: 27px 0; text-transform: uppercase; text-align: center; font-family: Georgia, serif;">
                    <span style="display: block; border-bottom: 2px solid #3d83cc; padding-bottom: 13px; margin: 0 auto; max-width: 80%;">
                        ' . $part_name . '
                    </span>
                </h1>
                <p style="font-size: 18px; color: #5D6D7E; margin: 27px 0; text-align: center; line-height: 1.6;">
                    has successfully participated in the<br>
                    <span style="font-size: 22px; color: #3d83cc; font-weight: bold;">' . $event_name . '</span>
                </p>
                <p style="font-size: 18px; color: #5D6D7E; margin: 27px 0; text-align: center;">
                    Awarded on <strong>' . date('F j, Y') . '</strong>
                </p>
                <!-- Signature Section using Table -->
                <table style="width: 100%; margin-top: 45px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; text-align: center; padding: 0 20px;">
                            <img src="https://drive.usercontent.google.com/download?id=1VtfpMHc5ByHx9cRShbQyy3mCO4umOo1A&authuser=1" alt="Authorized Signature 1" style="width: 50px; height: 50px; margin-bottom: 0px;">
                            <div style="border-bottom: 2px solid #3d83cc; width: 80%; margin: 13px auto;"></div>
                            <p style="font-size: 16px; color: #2C3E50; margin: 0;">President</p>
                        </td>
                        <td style="width: 50%; text-align: center; padding: 0 20px;">
                            <img src="' . $_SESSION['rc_signature'] . '" alt="Authorized Signature 2" style="width: 50px; height: 50px; margin-bottom: 0px;">
                            <div style="border-bottom: 2px solid #3d83cc; width: 80%; margin: 13px auto;"></div>
                            <p style="font-size: 16px; color: #2C3E50; margin: 0;">Coordinator</p>
                        </td>
                    </tr>
                </table>
                <!-- Footer Message -->
                <p style="font-size: 16px; color: #5D6D7E; margin-top: 36px; text-align: center; font-style: italic;">
                    Thank you for being part of our growing community.
                </p>
            </div>
        </div>
    </center>
</body>
</html>';

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($message);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $pdfContent = $dompdf->output();

            $pdfPath = __DIR__ . '/temp/certificate_' . $ev_id . '_' . time() . '.pdf';
            file_put_contents($pdfPath, $pdfContent);

            try {
               $cust_email = $part_email;
               $cust_name = $part_name;
               $mail->IsSMTP();
               $mail->Mailer = "smtp";
               $mail->SMTPDebug  = false;
               $mail->SMTPAuth   = true;
               $mail->SMTPSecure = "tls";
               $mail->Port       = 587;
               $mail->Host       = "smtp.gmail.com";
               $mail->Username   = "finalsemcapstone@gmail.com";
               $mail->Password   = "bzgkrupmopizwgog";
               $mail->IsHTML(true);
               $mail->AddAddress($cust_email, $cust_name);
               $mail->SetFrom("finalsemcapstone@gmail.com", "NEUCONNECT");
               $mail->AddReplyTo("finalsemcapstone@gmail.com", "NEUCONNECT");
               $mail->Subject = 'Certificate of Participation';

               // Add a simple HTML message body
               $mail->Body = 'Please find your Certificate of Participation attached to this email.';

               // Attach the PDF file
               $mail->addAttachment($pdfPath, 'Certificate_of_Participation.pdf');

               if (!$mail->Send()) {
                  $success_message = "Error while sending Email!";
                  var_dump($mail);
               } else {
                  $success_message = "Email has been sent successfully!";
                  // Delete the temporary PDF file after sending
                  unlink($pdfPath);
               }
            } catch (Exception $e) {
               echo 'Message could not be sent!';
               echo 'Mailer Error: ' . $mail->ErrorInfo;
               // Make sure to delete the PDF even if sending fails
               if (file_exists($pdfPath)) {
                  unlink($pdfPath);
               }
            }
         } // end while loop
      } // end if mysqli_num_rows check

      showSweetAlertSucced2("Success!", "Event has been completed successfully.", "success");
   } else {
      $error = "Error updating data: " . mysqli_error($DB);
      showSweetAlertFailed2("Failed!", $error, "error");
   }
} // end if isset($_POST['completedevent'])

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
                     window.location.href = 'events.php';
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
               <h1 class="m-0">Manage Events</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 accent-orange">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Manage Events</li>
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
               <h4 class="float-left">Events List </h4>
               <div class="btn-group float-right">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStaff_user"><i class="fas fa-plus"></i> Add Events</button>
               </div>
            </div>
            <div class="card-body">
               <table id="example2" class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Event Name </th>
                        <th>Description</th>
                        <th>Date & Time </th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Amenities</th>
                        <th>Action </th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $i = 0;
                     $myorg = $_SESSION['org_id'];
                     $residentqeury = mysqli_query($DB, "SELECT * FROM events WHERE ev_org_id='$myorg'");
                     if (mysqli_num_rows($residentqeury) > 0) {
                        while ($row = mysqli_fetch_assoc($residentqeury)) {
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
                              <td><?php echo $row['ev_description']; ?></td>
                              <td>Date: <?php echo $row['ev_date'] . ' <br>Time: ' . $row['ev_time']; ?></td>
                              <td><?php echo $row['ev_venue']; ?></td>
                              <td><?php echo $row['ev_status']; ?></td>
                              <td>
                                 <?php
                                 $test = 0;
                                 $residentqeurygg1 = mysqli_query($DB, "SELECT * FROM booked WHERE b_event_id='$staff_ID'");
                                 if (mysqli_num_rows($residentqeurygg1) > 0) {
                                    while ($rowgg1 = mysqli_fetch_assoc($residentqeurygg1)) {
                                       $test++;
                                       echo '<div style="border:1px solid gray; border-radius:2vh; padding:1vh;">Status: <b>' . $rowgg1['b_status'] . '</b><br>' . $rowgg1['b_description'] . '';
                                 ?>
                                       <form action="" method="POST">

                                          <input type="hidden" name="b_id" id="<?php echo $test; ?>b_id<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $rowgg1['bid']; ?>" required>

                                          <center> <button type="submit" class="btn btn-success" name="deletebooked" id="<?php echo $test; ?>deletebooked<?php echo $staff_ID; ?>">DELETE</button></center>
                                       </form>
            </div><br>
      <?php
                                    }
                                 } ?>
      </td>
      <td align="center">

         <?php if ($row['ev_status'] != 'Cancelled' && $row['ev_status'] != 'Completed') { ?>
            <button style="width:100%;" class="btn btn-primary" data-toggle="modal" data-target="#amenities<?php echo $staff_ID; ?>">BOOK AMENITIES</button>
            <br>
         <?php } ?>
         <div class="modal fade" id="amenities<?php echo $staff_ID; ?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm<?php echo $i; ?>()">
                     <div class="modal-header">
                        <h4 class="modal-title">BOOK AMENITIES</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">

                        <input type="hidden" name="eve_id" id="eve_id<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $row['ev_id']; ?>" required>
                        <div class="col-12 col-md-12 col-lg-12">
                           <div class="form-group">
                              <label for="amenities">Describe Amenities Needed For The Event</label>
                              <textarea name="additionalText" id="additionalText<?php echo $staff_ID; ?>" class="form-control" placeholder="Amenities" required></textarea>
                           </div>
                        </div>

                        <div class="modal-footer justify-content-end">
                           <button type="submit" class="btn btn-success" name="bookamenities" id="bookamenities<?php echo $staff_ID; ?>">Submit</button>
                        </div>
                     </div>
                  </form>
               </div>
               <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
         </div>
         <script>
            $(function() {
               // Summernote
               $('#additionalText<?php echo $staff_ID; ?>').summernote()
               // CodeMirror
               CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                  mode: "htmlmixed",
                  theme: "monokai"
               });
            })
         </script>
         <br>
         <?php if ($row['ev_status'] == 'Waiting') { ?>
            <form action="" method="POST">
               <input type="hidden" id="ev_id<?php echo $i; ?>" name="ev_id" value="<?php echo $row['ev_id']; ?>" />
               <button style="width:100%;" type="submit" class="btn btn-danger" id="cancelevent<?php echo $i; ?>" name="cancelevent">Cancel Event</button>
            </form>
            <br>
         <?php } ?>
         <?php if ($row['ev_status'] == 'Ongoing') { ?>
            <form action="" method="POST">
               <input type="hidden" id="event_name<?php echo $i; ?>" name="event_name" value="<?php echo $row['ev_name']; ?>" />
               <input type="hidden" id="ev_id<?php echo $i; ?>" name="ev_id" value="<?php echo $row['ev_id']; ?>" />
               <button style="width:100%; background-color:yellow; color:black;" type="submit" class="btn btn-primary" id="completedevent<?php echo $i; ?>" name="completedevent">Set as Completed</button>
            </form>
            <br>
         <?php } ?>

         <div class="btn-group">
            <button class="btn btn-success" data-toggle="modal" data-target="#editStaff<?php echo $staff_ID; ?>"><i
                  class="fas fa-edit"></i></button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteStaffs<?php echo $staff_ID; ?>"> <span class="d-lg-block d-lg-none d-xl-none">Delete</span><i class="fas fa-trash"></i> </button>
            <form action="" method="post">
               <div class="modal fade" id="deleteStaffs<?php echo $staff_ID; ?>">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title">Deleting Event</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <p>Are you sure you want to delete this Event?</p>
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
                           <h4 class="modal-title">Edit Event</h4>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">

                           <input type="hidden" name="ev_id" id="ev_id<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $row['ev_id']; ?>" required>
                           <div class="col-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                 <label for="ev_name">Event Name</label>
                                 <input type="text" name="ev_name" id="ev_name<?php echo $staff_ID; ?>" class="form-control" placeholder="Event Name" value="<?php echo $row['ev_name']; ?>" required>
                              </div>
                           </div>
                           <div class="col-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                 <label for="ev_description">Event Description</label>
                                 <textarea type="text" name="ev_description" id="ev_description<?php echo $staff_ID; ?>" class="form-control" placeholder="Event Description" value="" required><?php echo $row['ev_description']; ?></textarea>
                              </div>
                           </div>
                           <div class="col-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                 <label for="ev_venue">Event Venue</label>
                                 <input type="text" name="ev_venue" id="ev_venue<?php echo $staff_ID; ?>" class="form-control" placeholder="Event Venue" value="<?php echo $row['ev_venue']; ?>" required>
                              </div>
                           </div>
                           <div class="col-12 col-md-12 col-lg-12">
                              <div class="form-group">
                                 <label for="respondent_lastname">Event Date </label>
                                 <input type="date" name="ev_date" id="ev_date<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $row['ev_date']; ?>" required>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-6 col-md-6 col-lg-6">
                                 <div class="form-group">
                                    <label for="ev_time_from">From Time</label>
                                    <input type="time" name="ev_time_from" id="ev_time_from<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $ev_time_from; ?>" required>
                                 </div>
                              </div>
                              <div class="col-6 col-md-6 col-lg-6">
                                 <div class="form-group">
                                    <label for="ev_time_to">To Time</label>
                                    <input type="time" name="ev_time_to" id="ev_time_to<?php echo $staff_ID; ?>" class="form-control" value="<?php echo $ev_time_to; ?>" required>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer justify-content-end">
                           <button type="submit" class="btn btn-success" name="updatestaffInfo" id="updatestaffInfo">Update <i class="fas fa-edit"></i></button>
                        </div>
                     </form>
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
            <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateFormmanagestaff()">
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