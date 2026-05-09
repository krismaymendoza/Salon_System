<?php
session_start();
include '../db.php';
include '../includes/logger.php'; // Required for tracking the request

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    /* | Update status to 'Cancellation Requested' 
    | We include user_id in the WHERE clause to ensure users can only 
    | request cancellations for their own appointments.
    */
    $update = mysqli_query($conn, "
        UPDATE appointments 
        SET status='Cancellation Requested' 
        WHERE id='$id' AND user_id='$user_id'
    ");

    if($update){
        // Log the customer's request for the Admin to see
        logAction($conn, 
            $user_id, 
            $_SESSION['role'], 
            "Requested cancellation for appointment ID: " . $id
        );

        echo "<script>
            alert('Cancellation request has been sent to the Admin for approval.');
            window.location='dashboard.php';
        </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard.php");
}
?>