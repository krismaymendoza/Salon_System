<?php
session_start();
include '../db.php';
include '../includes/logger.php'; // Include your logger function

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Optional: Fetch service name before deleting to make the log more specific
    $service_query = mysqli_query($conn, "SELECT service_name FROM services WHERE id='$id'");
    $service_data = mysqli_fetch_assoc($service_query);
    $service_name = $service_data['service_name'];

    // Execute deletion
    $delete_query = mysqli_query($conn, "DELETE FROM services WHERE id='$id'");

    if($delete_query){
        // Record the action in system_logs
        logAction($conn, 
            $_SESSION['user_id'], 
            $_SESSION['role'], 
            "Deleted service: " . $service_name
        );
    }
}

header("Location: services.php");
exit();
?>