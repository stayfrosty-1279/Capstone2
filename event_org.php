<?php
include('includes/database_config.php');

// Ensure organization ID is set
$org_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

// Fetch events for this organization
$events_query = "SELECT * FROM events WHERE ev_org_id = ?";
$stmt = mysqli_prepare($DB, $events_query);
mysqli_stmt_bind_param($stmt, "i", $org_id);
mysqli_stmt_execute($stmt);
$events_result = mysqli_stmt_get_result($stmt);

// Get the current date and time with Manila timezone
$currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<?php
function showSweetAlertSucced3($title, $message, $type)
{
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
                     window.location.href = 'view_organization.php?id=$id';
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
  <title><?php echo $organization['org_name']; ?></title>


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
  <link rel="stylesheet" href="style/org_style.css">

  <link rel="icon" type="image/png" href="assets/uploads/<?php echo $organization['org_logo']; ?>">


  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .event-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: none;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .event-time {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .event-date {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
        }

        .register-btn {
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<?php
include 'includes/modals.php';
?>
<body>
    <div class="container py-5">
       
    <!-- Events -->
    <section id="events" class="py-5">
      <div class="container">
        <h2 class="text-center mb-5 fw-bold">Events</h2>
        <div class="row g-4">
          <?php
          $checkresident22 = mysqli_query($DB, "SELECT * FROM events WHERE ev_org_id = '$org_id'");
          if (mysqli_num_rows($checkresident22) > 0) {
            while ($data22 = mysqli_fetch_assoc($checkresident22)) {
              // Get the current date and time with Manila timezone
              $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));

              // Format event date and time
              $eventDate = $data22['ev_date'];
              $eventTime = $data22['ev_time'];

              // Split the event time into start and end times
              list($eventTimeStart, $eventTimeEnd) = explode(' TO ', $eventTime);

              // Combine event date with start time and end time
              $eventStartDateTime = DateTime::createFromFormat('Y-m-d h:i A', $eventDate . ' ' . $eventTimeStart, new DateTimeZone('Asia/Manila'));
              $eventEndDateTime = DateTime::createFromFormat('Y-m-d h:i A', $eventDate . ' ' . $eventTimeEnd, new DateTimeZone('Asia/Manila'));
          ?>
              <div class="col-lg-3 col-md-6">
                <div class="card event-card h-100 shadow-sm">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="badge bg-primary"><?php echo htmlspecialchars($data22['ev_status']); ?></span>
                      <div class="event-date">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <?php echo date('M d, Y', strtotime($data22['ev_date'])); ?>
                      </div>
                    </div>

                    <h3 class="h5 fw-bold mb-3 text-truncate">
                      <a href="#!" class="text-decoration-none text-dark">
                        <?php echo htmlspecialchars($data22['ev_name']); ?>
                      </a>
                    </h3>

                    <div class="mb-4">
                      <div class="d-flex align-items-center mb-2 event-time">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <span><?php echo htmlspecialchars($eventTimeStart); ?> - <?php echo htmlspecialchars($eventTimeEnd); ?></span>
                      </div>
                      <div class="d-flex align-items-center event-time">
                        <i class="fas fa-globe-americas text-primary me-2"></i>
                        <span>Manila Time (PHT)</span>
                      </div>
                    </div>

                    <?php if ($currentDateTime < $eventEndDateTime && $data22['ev_status'] == 'Ongoing') { ?>
                      <button class="btn btn-primary w-100 register-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#registerModal<?php echo $data22['ev_id']; ?>">
                        <i class="fas fa-user-plus me-2"></i>Register Now
                      </button>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <!-- Modal for this specific event -->
              <div class="modal fade" id="registerModal<?php echo $data22['ev_id']; ?>" tabindex="-1" aria-labelledby="registerModalLabel<?php echo $data22['ev_id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="registerModalLabel<?php echo $data22['ev_id']; ?>">Register for <?php echo htmlspecialchars($data22['ev_name']); ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="event_id" value="<?php echo $data22['ev_id']; ?>" />

                        <div class="mb-3">
                          <label for="p_student_no<?php echo $data22['ev_id']; ?>" class="form-label">Student Number ( Invalid number results to disapproval )</label>
                          <input type="text" class="form-control" id="p_student_no<?php echo $data22['ev_id']; ?>" name="p_student_no" required>
                        </div>
                        <div class="mb-3">
                          <label for="p_fullname<?php echo $data22['ev_id']; ?>" class="form-label">Full Name</label>
                          <input type="text" class="form-control" id="p_fullname<?php echo $data22['ev_id']; ?>" name="p_fullname" required>
                        </div>
                        <div class="mb-3">
                          <label for="p_contact<?php echo $data22['ev_id']; ?>" class="form-label">Contact Number</label>
                          <input type="text" class="form-control" id="p_contact<?php echo $data22['ev_id']; ?>" name="p_contact" required>
                        </div>
                        <div class="mb-3">
                          <label for="p_email<?php echo $data22['ev_id']; ?>" class="form-label">Email Address</label>
                          <input type="email" class="form-control" id="p_email<?php echo $data22['ev_id']; ?>" name="p_email" required>
                        </div>
                        <div class="mb-3">
                          <label for="id_upload<?php echo $data22['ev_id']; ?>" class="form-label">Upload Student ID ( Non-NEU ID results to disapproval )</label>
                          <input type="file" class="form-control" id="id_upload<?php echo $data22['ev_id']; ?>" name="id_upload" accept="image/*" required>
                        </div>

                        <button type="submit" name="registernow" class="btn btn-primary">Submit</button>
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            <?php
            }
          } else {
            ?>
            <div class="col-12">
              <div class="card shadow-sm text-center py-5">
                <div class="card-body">
                  <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
                  <h5 class="text-muted mb-3">No Events Available</h5>
                  <p class="text-muted mb-0">There are no events scheduled at this time. Please check back later.</p>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>