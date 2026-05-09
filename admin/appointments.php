<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

include '../db.php';

/*
|--------------------------------------------------------------------------
| SEARCH & FILTER
|--------------------------------------------------------------------------
*/
$search = "";
$status_filter = "";
$date_filter = "";
$where = "WHERE 1=1";

if(isset($_GET['search']) && $_GET['search'] != ""){
    $search = $_GET['search'];
    $where .= " AND (users.first_name LIKE '%$search%' OR users.last_name LIKE '%$search%' OR services.service_name LIKE '%$search%')";
}

if(isset($_GET['status']) && $_GET['status'] != ""){
    $status_filter = $_GET['status'];
    $where .= " AND appointments.status='$status_filter'";
}

if(isset($_GET['date']) && $_GET['date'] != ""){
    $date_filter = $_GET['date'];
    $where .= " AND appointments.appointment_date='$date_filter'";
}

/*
|--------------------------------------------------------------------------
| FETCH DATA
|--------------------------------------------------------------------------
*/
$query = mysqli_query($conn, "
SELECT appointments.*, users.first_name, users.last_name, services.service_name
FROM appointments
JOIN users ON appointments.user_id = users.id
JOIN services ON appointments.service_id = services.id
$where
ORDER BY appointments.appointment_date DESC
");

// Fetch staff for assignment dropdown
$employees_list = mysqli_query($conn, "SELECT employees.id, users.first_name FROM employees JOIN users ON employees.user_id = users.id");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointments Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <style>
        /* [Retained Original Styles] */
        .top-bar{ display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .filter-form{ display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }
        .filter-form input, .filter-form select{ padding: 10px; border: 1px solid #ccc; border-radius: 8px; }
        .filter-form button{ padding: 10px 20px; border: none; background: #ff8fab; color: white; border-radius: 8px; cursor: pointer; }
        .table-container{ background: white; padding: 25px; border-radius: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.08); overflow-x: auto; }
        table{ width: 100%; border-collapse: collapse; }
        table th{ background: #ffd6e0; padding: 15px; text-align: left; }
        table td{ padding: 15px; border-bottom: 1px solid #eee; }
        .badge{ padding: 8px 12px; border-radius: 20px; color: white; font-size: 13px; font-weight: bold; text-transform: capitalize; }
        .pending{ background: orange; }
        .approved{ background: #3a86ff; }
        .completed{ background: #38b000; }
        .cancelled{ background: #d90429; }
        .cancellation-requested { background: #ffeaa7; color: #d6a01d !important; }
        .action-btn{ padding: 8px 12px; border-radius: 8px; text-decoration: none; color: white; font-size: 13px; margin-right: 5px; border: none; cursor: pointer; }
        .approve-btn{ background: #3a86ff; }
        .cancel-btn{ background: #d90429; }
        .complete-btn{ background: #38b000; }

        /* FIX: Ensure calendar is clickable and assignment UI looks clean */
        #calendar{ 
            position: relative; 
            z-index: 10; 
            margin-top: 40px; 
            background: white; 
            padding: 25px; 
            border-radius: 15px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.08); 
        }
        .fc-button{ background: #ff8fab !important; border: none !important; }
        .assign-select { padding: 5px; border-radius: 5px; border: 1px solid #ccc; font-size: 12px; margin-right: 5px; }
        .assign-form { display: flex; align-items: center; }
    </style>
</head>
<body>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="top-bar">
            <h1>Appointment Management</h1>
        </div>

        <form method="GET" class="filter-form">
            <input type="text" name="search" placeholder="Search customer/service" value="<?php echo $search; ?>">
            <select name="status">
                <option value="">All Status</option>
                <option value="Pending" <?php if($status_filter=="Pending") echo "selected"; ?>>Pending</option>
                <option value="Approved" <?php if($status_filter=="Approved") echo "selected"; ?>>Approved</option>
                <option value="Cancellation Requested" <?php if($status_filter=="Cancellation Requested") echo "selected"; ?>>Cancellation Requested</option>
                <option value="Completed" <?php if($status_filter=="Completed") echo "selected"; ?>>Completed</option>
                <option value="Cancelled" <?php if($status_filter=="Cancelled") echo "selected"; ?>>Cancelled</option>
            </select>
            <input type="date" name="date" value="<?php echo $date_filter; ?>">
            <button type="submit">Filter</button>
        </form>

        <div class="table-container">
            <table>
                <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($query)){ ?>
                <tr>
                    <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo date("h:i A", strtotime($row['appointment_time'])); ?></td>
                    <td>
                        <span class="badge <?php echo str_replace(' ', '-', strtolower($row['status'])); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['status']=="Pending"){ ?>
                            <form action="approve_appointment.php" method="GET" class="assign-form">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <select name="employee_id" class="assign-select" required>
                                    <option value="">Assign Staff</option>
                                    <?php 
                                    mysqli_data_seek($employees_list, 0);
                                    while($emp = mysqli_fetch_assoc($employees_list)){
                                        echo "<option value='".$emp['id']."'>".$emp['first_name']."</option>";
                                    } 
                                    ?>
                                </select>
                                <button type="submit" class="action-btn approve-btn">Approve</button>
                                <a class="action-btn cancel-btn" href="cancel_appointment.php?id=<?php echo $row['id']; ?>">Cancel</a>
                            </form>
                        <?php } ?>

                        <?php if($row['status']=="Cancellation Requested"){ ?>
                            <a class="action-btn cancel-btn" href="approve_cancel.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Approve this cancellation?')">Approve Cancel</a>
                        <?php } ?>

                        <?php if($row['status']=="Approved"){ ?>
                            <a class="action-btn complete-btn" href="complete_appointment.php?id=<?php echo $row['id']; ?>">Complete</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div id="calendar"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 700,
        editable: true,
        selectable: true, // Required for clicking slots
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
        events: 'load_appointments.php',
        eventClick: function(info){
            alert("Customer: " + info.event.extendedProps.customer + "\nService: " + info.event.extendedProps.service + "\nStatus: " + info.event.extendedProps.status);
        },
        eventDrop: function(info){
            let id = info.event.id;
            let start = info.event.start;
            let date = start.toISOString().split('T')[0];
            let time = start.toTimeString().split(' ')[0];
            fetch('update_appointment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id + '&date=' + date + '&time=' + time
            })
            .then(response => response.text())
            .then(data => { alert("Appointment Updated"); });
        }
    });
    calendar.render();
});
</script>
</body>
</html>