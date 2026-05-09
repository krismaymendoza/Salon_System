<?php
session_start();
include '../db.php';
include '../includes/logger.php'; // Required for audit trail

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Fetch customer and service info for the log message
    $info = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT users.first_name, services.service_name 
        FROM appointments 
        JOIN users ON appointments.user_id = users.id 
        JOIN services ON appointments.service_id = services.id 
        WHERE appointments.id='$id'
    "));

    // Update status to 'Cancelled'
    $update = mysqli_query($conn, "UPDATE appointments SET status='Cancelled' WHERE id='$id'");

    if($update){
        // Log the admin's approval of the cancellation
        logAction($conn, 
            $_SESSION['user_id'], 
            $_SESSION['role'], 
            "Admin approved cancellation for " . $info['first_name'] . " (" . $info['service_name'] . ")"
        );
    }
}

header("Location: appointments.php");
exit();
?>