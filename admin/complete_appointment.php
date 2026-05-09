<?php
session_start();
include '../db.php';
include '../includes/logger.php';

$id = $_GET['id'];

$info = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT users.first_name, services.service_name 
    FROM appointments 
    JOIN users ON appointments.user_id = users.id 
    JOIN services ON appointments.service_id = services.id 
    WHERE appointments.id='$id'
"));

$update = mysqli_query($conn, "UPDATE appointments SET status='Completed' WHERE id='$id'");

if($update) {
    logAction($conn, $_SESSION['user_id'], $_SESSION['role'], 
        "Marked appointment as Completed: " . $info['first_name'] . " - " . $info['service_name']);
}

header("Location: appointments.php");
?>