<?php
include('includes/database_config.php');
$org_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$organization = null;

if ($org_id > 0) {
  $stmt = $DB->prepare("SELECT * FROM organization WHERE org_id = ?");
  $stmt->bind_param("i", $org_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $organization = $result->fetch_assoc();
  $stmt->close();
}

if (!$organization) {
  header("Location: index.php");
  exit();
}


if (isset($_POST['registernow'])) {

  $event_id = $_POST['event_id'];
  $p_student_no = $_POST['p_student_no'];
  $p_fullname = $_POST['p_fullname'];
  $p_contact = $_POST['p_contact'];
  $p_email = $_POST['p_email'];

  $checker = 0;
  $checkresident22 = mysqli_query($DB, "SELECT * FROM participants WHERE p_student_no = '$p_student_no' AND p_fullname = '$p_fullname' AND p_event_id = '$event_id'");
  if (mysqli_num_rows($checkresident22) > 0) {
    while ($data22 = mysqli_fetch_assoc($checkresident22)) {
      $checker++;
    }
  }

  if ($checker == 0) {
    $path2 = $_FILES['id_upload']['name'];
    $path_tmp2 = $_FILES['id_upload']['tmp_name'];

    if ($path2 != '') {
      $ext2 = pathinfo($path2, PATHINFO_EXTENSION);
      $file_name2 = basename($path2, '.' . $ext2);
      if ($ext2 != 'jpg' && $ext2 != 'png' && $ext2 != 'jpeg' && $ext2 != 'gif') {
        $validation = 0;
      }
    } else {
      $validation = 0;
    }

    $id_timestamp = time();
    $id_image = 'id-' . $id_timestamp . '-' . $file_name2 . '-.' . $ext2;
    move_uploaded_file($path_tmp2, 'assets/uploads/' . $id_image);

    $sql = "INSERT INTO participants
                            (
                              p_student_no,
                              p_fullname,
                              p_contact,
                              p_idimage,
                              p_event_id,
                              p_email
                            ) 
                            VALUES
                            (
                              ?,
                              ?,
                              ?,
                              ?,
                              ?,
                              ?
                            )";

    $query = mysqli_prepare($DB, $sql);
    mysqli_stmt_bind_param($query, "ssssss", $p_student_no, $p_fullname, $p_contact, $id_image, $event_id, $p_email);


    if (mysqli_stmt_execute($query)) {
      $lastInsertId = mysqli_insert_id($DB);

      if ($lastInsertId) {
        showSweetAlertSucced3("Success!", "Your request of registration has been submitted successfully, we will notify you shortly!", "success");
      }
    }
  } else {

    showSweetAlertSucced3("Already Submitted!", "You are already registered to the event!", "error");
  }
}
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
    .officer-card {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
      border: none;
    }

    .officer-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-image-officer {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

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

    /* Custom Carousel Styling */
    .custom-carousel {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      margin: 20px auto;
      max-width: 1200px;
    }

    .custom-carousel .carousel-item {
      height: 500px;
    }

    .custom-carousel .carousel-item img {
      height: 100%;
      object-fit: cover;
      filter: brightness(0.9);
      transition: transform 0.5s ease;
    }

    .custom-carousel .carousel-item:hover img {
      transform: scale(1.02);
    }

    /* Custom Indicators */
    .custom-carousel .carousel-indicators {
      bottom: 20px;
    }

    .custom-carousel .carousel-indicators button {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin: 0 5px;
      background-color: rgba(255, 255, 255, 0.5);
      border: 2px solid rgba(255, 255, 255, 0.8);
    }

    .custom-carousel .carousel-indicators button.active {
      background-color: white;
    }

    /* Custom Controls */
    .custom-carousel .carousel-control-prev,
    .custom-carousel .carousel-control-next {
      width: 50px;
      height: 50px;
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .custom-carousel:hover .carousel-control-prev,
    .custom-carousel:hover .carousel-control-next {
      opacity: 1;
    }

    .custom-carousel .carousel-control-prev {
      left: 20px;
    }

    .custom-carousel .carousel-control-next {
      right: 20px;
    }

    /* Caption Styling (if you want to add captions) */
    .custom-carousel .carousel-caption {
      background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
      bottom: 0;
      left: 0;
      right: 0;
      padding: 40px 20px 20px;
      text-align: left;
    }
  </style>




</head>
<?php
include 'includes/modals.php';
?>

<body>
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

  include('includes/organization_navBar.php');
  include('javascript/javascripts.php');
  ?>

  <!-- Carousel -->
  <div id="carouselExampleFadeIndicators" class="carousel slide carousel-fade custom-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
      <?php
      $checkresident22 = mysqli_query($DB, "SELECT * FROM sliders WHERE s_org_id = '$org_id'");
      $slideCount = mysqli_num_rows($checkresident22);

      if ($slideCount > 0) {
        for ($i = 0; $i < $slideCount; $i++) {
      ?>
          <button type="button"
            data-bs-target="#carouselExampleFadeIndicators"
            data-bs-slide-to="<?php echo $i; ?>"
            <?php echo ($i == 0) ? 'class="active" aria-current="true"' : ''; ?>
            aria-label="Slide <?php echo $i + 1; ?>">
          </button>
        <?php
        }
      } else {
        ?>
        <button type="button"
          data-bs-target="#carouselExampleFadeIndicators"
          data-bs-slide-to="0"
          class="active"
          aria-current="true"
          aria-label="Slide 1">
        </button>
      <?php
      }
      ?>
    </div>

    <div class="carousel-inner">
      <?php
      if ($slideCount > 0) {
        $counter = 0;
        while ($data22 = mysqli_fetch_assoc($checkresident22)) {
          $counter++;
      ?>
          <div class="carousel-item <?php echo ($counter == 1) ? 'active' : ''; ?>">
            <img src="assets/uploads/<?php echo $data22['s_image']; ?>"
              class="d-block w-100"
              alt="Student Organization Event <?php echo $counter; ?>">
            <!-- Optional: Add captions -->
            <!-- <div class="carousel-caption d-none d-md-block">
            <h5>Event Title</h5>
            <p>Event Description</p>
          </div> -->
          </div>
        <?php
        }
      } else {
        ?>
        <div class="carousel-item active">
          <img src="assets/placeholder/carousel/placeholder.png"
            class="d-block w-100"
            alt="No images available">
        </div>
      <?php
      }
      ?>
    </div>

    <?php if ($slideCount > 1) : ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFadeIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFadeIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    <?php endif; ?>
  </div>


  <!-- Org Infos-->
  <div class="container mt-5">
    <section id="about">

 <h2 class="text-center mb-5 fw-bold">About us</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-info-circle text-primary me-2 fs-4"></i>
                <h2 class="card-title h4 mb-0">Description</h2>
              </div>
              <p class="card-text">
                <?php echo !empty($organization['org_description']) ?
                  htmlspecialchars($organization['org_description']) :
                  '<em class="text-muted">No description is available at this time.</em>'; ?>
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-bullseye text-primary me-2 fs-4"></i>
                <h2 class="card-title h4 mb-0">Mission</h2>
              </div>
              <p class="card-text">
                <?php echo !empty($organization['org_mission']) ?
                  htmlspecialchars($organization['org_mission']) :
                  '<em class="text-muted">No mission statement is available at this time.</em>'; ?>
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-eye text-primary me-2 fs-4"></i>
                <h2 class="card-title h4 mb-0">Vision</h2>
              </div>
              <p class="card-text">
                <?php echo !empty($organization['org_vision']) ?
                  htmlspecialchars($organization['org_vision']) :
                  '<em class="text-muted">No vision statement is available at this time.</em>'; ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Officers-->
    <section id="officers" class="py-5">
      <div class="container">
 <h2 class="text-center mb-5 fw-bold">Officers</h2>
        <div class="row g-4">
          <?php
          $checkresident22 = mysqli_query($DB, "SELECT * FROM users WHERE org_id = '$org_id' AND Status = 'Active' AND admin_approval = 'Approved'");
          if (mysqli_num_rows($checkresident22) > 0) {
            while ($data22 = mysqli_fetch_assoc($checkresident22)) {
              $profile_picture = !empty($data22['profile_picture']) ?
                "assets/uploads/" . htmlspecialchars($data22['profile_picture']) :
                "assets/placeholder/officer/image1.jpg";
          ?>
              <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card officer-card h-100 shadow-sm">
                  <div class="card-body text-center">
                    <div class="mb-3">
                      <img src="<?php echo $profile_picture; ?>"
                        class="profile-image-officer mb-3"
                        alt="<?php echo htmlspecialchars($data22['firstName']); ?>'s profile picture">
                    </div>
                    <h5 class="card-title fw-bold mb-1">
                      <?php echo htmlspecialchars($data22['firstName'] . ' ' . $data22['middleName'] . ' ' . $data22['lastName']); ?>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-primary">
                      <?php echo htmlspecialchars($data22['Position']); ?>
                    </h6>
                    <div class="d-flex align-items-center justify-content-center">
                      <span class="badge bg-success rounded-pill px-3">
                        <?php echo htmlspecialchars($data22['Status']); ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            <?php
            }
          } else {
            ?>
            <div class="col-12 text-center">
              <div class="card shadow-sm">
                <div class="card-body py-5">
                  <img src="assets/placeholder/officer/image1.png"
                    class="profile-image-officer mb-3"
                    alt="Placeholder officer image">
                  <h5 class="text-muted mt-3">No officers available at this time</h5>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>



    <!-- Events -->

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
  <br><br><br><br>



</body>

</html>