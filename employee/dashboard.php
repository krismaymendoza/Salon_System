<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employee'){
    header("Location: ../login.php");
    exit();
}

include '../db.php';

/*
|--------------------------------------------------------------------------
| GET EMPLOYEE ID
|--------------------------------------------------------------------------
*/
$user_id = $_SESSION['user_id'];
$employee_query = mysqli_query($conn, "SELECT * FROM employees WHERE user_id='$user_id'");
$employee = mysqli_fetch_assoc($employee_query);
$employee_id = $employee['id'];

/*
|--------------------------------------------------------------------------
| DASHBOARD COUNTS
|--------------------------------------------------------------------------
*/
// Total assigned to this employee
$total_appointments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE employee_id='$employee_id'"));

$pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE employee_id='$employee_id' AND status='Pending'"));

$approved = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE employee_id='$employee_id' AND status='Approved'"));

$completed = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE employee_id='$employee_id' AND status='Completed'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        /* Specific accent colors for employee cards */
        .card-pending { border-bottom: 4px solid #ffeaa7; }
        .card-approved { border-bottom: 4px solid #3a86ff; }
        .card-completed { border-bottom: 4px solid #38b000; }
        
        .welcome h1 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body>

<div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="content">

        <div class="welcome">
            <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
            <p>Here is an overview of your assigned beauty services and schedule.</p>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Assigned</h3>
                <p><?php echo $total_appointments; ?></p>
            </div>

            <div class="card card-pending">
                <h3>Pending</h3>
                <p><?php echo $pending; ?></p>
            </div>

            <div class="card card-approved">
                <h3>Active/Approved</h3>
                <p><?php echo $approved; ?></p>
            </div>

            <div class="card card-completed">
                <h3>Completed</h3>
                <p><?php echo $completed; ?></p>
            </div>

        </div>

        <div class="table-container" style="margin-top: 40px; text-align: center; padding: 60px;">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 15px;">Ready to work?</h2>
            <p style="color: #666; margin-bottom: 25px;">Check your detailed calendar to manage your client time slots.</p>
            <a href="appointments.php" class="btn-book" style="text-decoration: none; display: inline-block;">View My Appointments</a>
        </div>

    </main>

</div>

</body>
</html>