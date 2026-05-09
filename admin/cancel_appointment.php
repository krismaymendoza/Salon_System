<?php
session_start();
include '../db.php';
include '../includes/logger.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Update status to 'Cancelled'
    $update = mysqli_query($conn, "UPDATE appointments SET status='Cancelled' WHERE id='$id'");

    if($update){
        logAction($conn, $_SESSION['user_id'], $_SESSION['role'], "Admin approved cancellation for appointment ID: " . $id);
    }
}

header("Location: appointments.php");
exit();
?>