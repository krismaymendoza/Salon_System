<?php

session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

include '../db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $appointment_id = intval($_POST['appointment_id']);
    $employee_id = intval($_POST['employee_id']);

    mysqli_query($conn, "
    UPDATE appointments
    SET employee_id='$employee_id'
    WHERE id='$appointment_id'
    ");
}

header("Location: appointments.php");
exit();

?>