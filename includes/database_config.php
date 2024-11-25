<?php
$islocalhost = 0;
if($islocalhost == 0){
    $SERVER = "localhost";
    $USERNAME = "root";
    $PASSWORD = "";
    $DB_NAME = "db_neuconnect";
    $SMTPSETTINGS = 'smtp';
    $BASE_URL = 'localhost/neuconnect_v2/';
}
// Create connection
$DB = mysqli_connect($SERVER, $USERNAME, $PASSWORD, $DB_NAME);
// Check connection
if (!$DB) {
    die("Connection failed: " . mysqli_connect_error());
}

ob_start();
session_start();
?>