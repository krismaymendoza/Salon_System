<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include '../db.php';
include '../includes/logger.php'; // Required for recording booking actions

$services = mysqli_query($conn, "SELECT * FROM services");

/*
|--------------------------------------------------------------------------
| SALON SETTINGS
|--------------------------------------------------------------------------
*/
$opening_time = "09:00";
$closing_time = "20:00";
$max_customers_per_slot = 3;

if(isset($_POST['book'])){

    $user_id = $_SESSION['user_id'];
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    /* VALIDATION 1 — PREVENT PAST DATES */
    $today = date("Y-m-d");
    if($appointment_date < $today){
        echo "<script>alert('You cannot book a past date.');</script>";
        exit();

    }

    /* VALIDATION 2 — PREVENT SUNDAYS */
    $day = date('l', strtotime($appointment_date));
    if($day == "Sunday"){
        echo "<script>alert('Salon is closed on Sundays.');</script>";
        exit();

    }

    /* VALIDATION 3 — OPERATING HOURS */
    if($appointment_time < $opening_time || $appointment_time > $closing_time){
        echo "<script>alert('Booking time must be between 9AM and 8PM.');</script>";
        exit();

    }

    /* VALIDATION 4 — MAX CUSTOMERS PER SLOT */
    $slot_query = mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM appointments
        WHERE appointment_date='$appointment_date'
        AND appointment_time='$appointment_time'
        AND status != 'Cancelled'
    ");
    $slot_data = mysqli_fetch_assoc($slot_query);
    if($slot_data['total'] >= $max_customers_per_slot){
        echo "<script>alert('This schedule is already full.');</script>";
        exit();

    }

    /* VALIDATION 5 — PREVENT DOUBLE BOOKING */
    $duplicate = mysqli_query($conn, "
        SELECT * FROM appointments
        WHERE user_id='$user_id'
        AND appointment_date='$appointment_date'
        AND appointment_time='$appointment_time'
        AND status != 'Cancelled'
    ");
    if(mysqli_num_rows($duplicate) > 0){
        echo "<script>alert('You already have an appointment at this time.');</script>";
        exit();

    }

    /* VALIDATION 6 — ASSIGN AVAILABLE EMPLOYEE */
    $employee_query = mysqli_query($conn, "
        SELECT employees.id FROM employees
        WHERE availability_status='Available'
        AND id NOT IN (
            SELECT employee_id FROM appointments
            WHERE appointment_date='$appointment_date'
            AND appointment_time='$appointment_time'
            AND status != 'Cancelled'
        ) LIMIT 1
    ");

    if(mysqli_num_rows($employee_query) == 0){
        echo "<script>alert('No available employee for this schedule.');</script>";
        exit();

    }

    $employee = mysqli_fetch_assoc($employee_query);
    $employee_id = $employee['id'];

    /* SAVE APPOINTMENT AND LOG ACTION */
    $insert = mysqli_query($conn, "
        INSERT INTO appointments (user_id, service_id, employee_id, appointment_date, appointment_time)
        VALUES ('$user_id', '$service_id', '$employee_id', '$appointment_date', '$appointment_time')
    ");

    if($insert){
        /* |--------------------------------------------------------------------------
        | NEW CODE: GENERATE CUSTOM BOOKING ID (bk-2026-01)
        |--------------------------------------------------------------------------
        */
        $new_booking_id = mysqli_insert_id($conn); // Get auto-incremented ID
        $year = date("Y"); // Current Year
        
        // Formats ID with padding: bk-2026-01
        $booking_custom_id = "bk-" . $year . "-" . str_pad($new_booking_id, 2, '0', STR_PAD_LEFT);
        
        // Update record with the formatted string
        mysqli_query($conn, "UPDATE appointments SET booking_custom_id='$booking_custom_id' WHERE id='$new_booking_id'");

        // Fetch service name for detailed logging
        $service_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT service_name FROM services WHERE id='$service_id'"));
        $service_name = $service_info['service_name'];

        // Log the successful booking with the new custom ID
        logAction($conn, $user_id, $_SESSION['role'], "Created booking: " . $booking_custom_id . " for " . $service_name);

        echo "<script>window.location='dashboard.php';</script>";
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment | Glow & Style Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body.booking-page {
            background: linear-gradient(135deg, #ffd6e0 0%, #fffafc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .booking-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(255, 143, 171, 0.15);
            width: 100%;
            max-width: 500px;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            color: #2d2d2d;
            text-align: center;
            font-size: 28px;
        }

        .underline {
            width: 50px;
            height: 3px;
            background: #ff8fab;
            margin: 10px auto 25px;
        }

        .schedule-note {
            background: #fff0f3;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #666;
            border-left: 4px solid #ff8fab;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #444;
        }

        select, input[type="date"], input[type="time"] {
            width: 100%;
            padding: 12px 18px;
            border: 1px solid #eee;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            background: #fdfdfd;
            transition: all 0.3s ease;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #ff8fab;
            box-shadow: 0 0 8px rgba(255, 143, 171, 0.1);
        }

        .confirm-btn {
            width: 100%;
            background: #ff8fab;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(255, 143, 171, 0.3);
            transition: all 0.3s ease;
        }

        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 143, 171, 0.4);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
        }

        .back-link:hover { color: #ff8fab; }

        /* Modal */
        .modal-overlay{
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }
        .modal-overlay.show{ display: flex; }
        .modal{
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .modal-header{
            padding: 18px 22px;
            background: #fff0f3;
            border-bottom: 1px solid #ffe1e8;
        }
        .modal-header h3{
            margin: 0;
            font-family: 'Playfair Display', serif;
            color: #2d2d2d;
            font-size: 20px;
        }
        .modal-body{
            padding: 18px 22px;
            color: #444;
            font-size: 14px;
            line-height: 1.4;
        }
        .modal-actions{
            padding: 16px 22px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            border-top: 1px solid #f3f3f3;
        }
        .modal-btn{
            border: none;
            border-radius: 50px;
            padding: 12px 18px;
            font-weight: 600;
            cursor: pointer;
        }
        .modal-btn.cancel{
            background: #e9e9e9;
            color: #333;
        }
        .modal-btn.confirm{
            background: #ff8fab;
            color: #fff;
        }

        .modal-btn:focus{ outline: none; }
    </style>
</head>
<body class="booking-page">


    <div class="booking-card">
        <div class="section-header">
            <h2>Reserve Your Glow</h2>
            <div class="underline"></div>
        </div>

        <div class="schedule-note">
            <strong>Operating Hours:</strong><br>
            Mon — Sat: 9:00 AM - 8:00 PM<br>
            <small>*Closed on Sundays</small>
        </div>

        <form method="POST">
            <div class="input-group">
                <label>Choose a Service</label>
                <select name="service_id" required>
                    <option value="">Select a treatment...</option>
                    <?php while($service = mysqli_fetch_assoc($services)){ ?>
                        <option value="<?php echo $service['id']; ?>">
                            <?php echo $service['service_name'] . " — ₱" . number_format($service['price']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-group">
                <label>Appointment Date</label>
                <input type="date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="input-group">
                <label>Preferred Time</label>
                <input type="time" name="appointment_time" min="09:00" max="20:00" required>
            </div>

            <button type="button" name="book" class="confirm-btn" id="confirmAppointmentBtn">
                Confirm Appointment
            </button>
            <button type="submit" name="book" id="hiddenSubmitBtn" style="display:none;"></button>


            <a href="dashboard.php" class="back-link">← Cancel and return to Dashboard</a>
        </form>
    </div>

    <div class="modal-overlay" id="confirmModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="confirmModalTitle">
            <div class="modal-header">
                <h3 id="confirmModalTitle">Confirm Appointment</h3>
            </div>
            <div class="modal-body" id="confirmModalBody">
                Appointment booked successfully! Your Booking ID is: bk-2026-11
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn cancel" id="modalCancelBtn">Cancel</button>
                <button type="button" class="modal-btn confirm" id="modalConfirmBtn">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const confirmBtn = document.getElementById('confirmAppointmentBtn');
            const modal = document.getElementById('confirmModal');
            const modalCancelBtn = document.getElementById('modalCancelBtn');
            const modalConfirmBtn = document.getElementById('modalConfirmBtn');
            const hiddenSubmitBtn = document.getElementById('hiddenSubmitBtn');

            if(!confirmBtn || !modal || !modalCancelBtn || !modalConfirmBtn || !hiddenSubmitBtn){
                return;
            }

            function openModal(){
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal(){
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            }

            confirmBtn.addEventListener('click', function(){
                openModal();
            });

            modalCancelBtn.addEventListener('click', function(){
                closeModal();
            });

            modalConfirmBtn.addEventListener('click', function(){
                closeModal();
                // submit the form
                hiddenSubmitBtn.click();
            });

            modal.addEventListener('click', function(e){
                if(e.target === modal){
                    closeModal();
                }
            });

            document.addEventListener('keydown', function(e){
                if(modal.classList.contains('show') && e.key === 'Escape'){
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>
