<?php
session_start();
include '../db.php';
include '../includes/logger.php';

$id = $_POST['id'];
$date = $_POST['date'];
$time = $_POST['time'];

// Fetch service name for the log
$info = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT services.service_name 
    FROM appointments 
    JOIN services ON appointments.service_id = services.id 
    WHERE appointments.id='$id'
"));

$update = mysqli_query($conn, "
    UPDATE appointments 
    SET appointment_date='$date', appointment_time='$time' 
    WHERE id='$id'
");

if($update) {
    logAction($conn, $_SESSION['user_id'], $_SESSION['role'], 
        "Rescheduled " . $info['service_name'] . " to " . $date . " " . $time);
    echo "Success";
}
?>