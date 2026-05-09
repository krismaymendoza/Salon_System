<?php
session_start();
include '../db.php';
include '../includes/logger.php'; 

// Check if both appointment ID and Employee ID are provided
if(isset($_GET['id']) && isset($_GET['employee_id'])) {
    $id = $_GET['id'];
    $emp_id = $_GET['employee_id'];

    // Fetch details for logging
    $info = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT users.first_name, services.service_name 
        FROM appointments 
        JOIN users ON appointments.user_id = users.id 
        JOIN services ON appointments.service_id = services.id 
        WHERE appointments.id='$id'
    "));

    // Update status and assign employee
    $update = mysqli_query($conn, "
        UPDATE appointments 
        SET status='Approved', employee_id='$emp_id' 
        WHERE id='$id'
    ");

    if($update) {
        logAction($conn, $_SESSION['user_id'], $_SESSION['role'], 
            "Approved & assigned " . $info['first_name'] . " to Employee ID: " . $emp_id);
    }
}

header("Location: appointments.php");
exit();
?>