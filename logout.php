<?php
    session_start();

    unset(
        $_SESSION['logged'],
        $_SESSION['user_id'],
        $_SESSION['resident_id'],
        $_SESSION['firstName'],
        $_SESSION['middleName'],
        $_SESSION['lastName'],
        $_SESSION['fullname'],
        $_SESSION['dateOfBirth'],
        $_SESSION['gender'],
        $_SESSION['address'],
        $_SESSION['contactNumber'],
        $_SESSION['validID'],
        $_SESSION['idImage'],
        $_SESSION['email'],
        $_SESSION['password'],
        $_SESSION['dateRegistered'],
        $_SESSION['Activation_code']
    );
// Finally, destroy the session.
session_destroy();

    header('Location: index.php');
    exit();
?>