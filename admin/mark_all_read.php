<?php
include('includes/header.php');

if(isset($_GET['type'])) {
    if($_GET['type'] == 'admin' && $_SESSION['priviledges'] == 'Super Admin') {
        // Mark all amenities booking notifications as read for Super Admin
        $update_query = "UPDATE notifications 
                        SET notification_status = 'read' 
                        WHERE notification_type = 'amenities_booking' 
                        AND notification_status = 'unread'";
        mysqli_query($DB, $update_query);
    }
    else if($_GET['type'] == 'coordinator' && $_SESSION['priviledges'] == 'Coordinator') {
        // Mark all notifications as read for this coordinator
        $update_query = "UPDATE notifications 
                        SET notification_status = 'read' 
                        WHERE coordinator_id = ? 
                        AND notification_status = 'unread'";
        $stmt = mysqli_prepare($DB, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
    }
}

// Redirect back to previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>