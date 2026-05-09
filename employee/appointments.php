<?php
session_start();

if(!isset($_SESSION['user_id']) ||
   $_SESSION['role'] != 'employee'){

    header("Location: ../login.php");
}

include '../db.php';

/*
|--------------------------------------------------------------------------
| GET EMPLOYEE ID
|--------------------------------------------------------------------------
*/

$user_id = $_SESSION['user_id'];

$employee_query = mysqli_query($conn, "
SELECT * FROM employees
WHERE user_id='$user_id'
");

$employee = mysqli_fetch_assoc($employee_query);

$employee_id = $employee['id'];

/*
|--------------------------------------------------------------------------
| FETCH APPOINTMENTS
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn, "
SELECT
appointments.*,
users.first_name,
users.last_name,
services.service_name
FROM appointments
JOIN users ON appointments.user_id = users.id
JOIN services ON appointments.service_id = services.id
WHERE appointments.employee_id='$employee_id'
ORDER BY appointment_date ASC
");

?>

<!DOCTYPE html>
<html>
<head>

    <title>My Appointments</title>

    <link rel="stylesheet" href="../css/admin.css">

    <!-- FULLCALENDAR -->

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet'>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <style>

        .table-container{
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        table th{
            background: #ffd6e0;
            padding: 15px;
            text-align: left;
        }

        table td{
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .badge{
            padding: 8px 12px;
            border-radius: 20px;
            color: white;
            font-size: 13px;
            font-weight: bold;
        }

        .pending{
            background: orange;
        }

        .approved{
            background: #3a86ff;
        }

        .completed{
            background: #38b000;
        }

        .cancelled{
            background: #d90429;
        }

        .action-btn{
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-size: 13px;
        }

        .complete-btn{
            background: #38b000;
        }

        #calendar{
            margin-top: 40px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .fc-button{
            background: #ff8fab !important;
            border: none !important;
        }

        .fc-button-primary {
    background-color: #ff8fab !important;
    border-color: #ff8fab !important;
    text-transform: capitalize;
}

.fc-event {
    cursor: pointer;
    border-radius: 4px;
    padding: 2px;
}

    </style>

</head>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <?php include 'includes/sidebar.php'; ?>

    <!-- CONTENT -->

    <div class="content">

        <h1>My Appointments</h1>

        <br>

        <!-- TABLE -->

        <div class="table-container">

            <table>

                <tr>

                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

                <?php while($row = mysqli_fetch_assoc($query)){ ?>

                <tr>

                    <td>
                        <?php
                        echo $row['first_name'] . " " .
                             $row['last_name'];
                        ?>
                    </td>

                    <td>
                        <?php echo $row['service_name']; ?>
                    </td>

                    <td>
                        <?php echo $row['appointment_date']; ?>
                    </td>

                    <td>
                        <?php echo date("h:i A",
                        strtotime($row['appointment_time'])); ?>
                    </td>

                    <td>

                        <span class="badge
                        <?php echo strtolower($row['status']); ?>">

                            <?php echo $row['status']; ?>

                        </span>

                    </td>

                    <td>

                        <?php if($row['status']=="Approved"){ ?>

                        <a
                        class="action-btn complete-btn"
                        href="update_status.php?id=<?php echo $row['id']; ?>">
                            Mark Completed
                        </a>

                        <?php } ?>

                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

        <!-- CALENDAR -->

        <div id="calendar"></div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function() {

    var calendarEl =
        document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(
        calendarEl,
        {

        initialView: 'dayGridMonth',

        height: 700,

        events: 'load_employee_appointments.php',

        headerToolbar: {

            left: 'prev,next today',

            center: 'title',

            right:
            'dayGridMonth,timeGridWeek,timeGridDay'
        },

        eventClick: function(info){

            alert(
                "Customer: " +
                info.event.extendedProps.customer +

                "\nService: " +
                info.event.extendedProps.service +

                "\nStatus: " +
                info.event.extendedProps.status
            );
        }

    });

    calendar.render();

});

</script>

</body>
</html>