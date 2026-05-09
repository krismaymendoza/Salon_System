<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include '../db.php';

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| FETCH APPOINTMENTS WITH SERVICE NAMES
|--------------------------------------------------------------------------
*/
$query = mysqli_query($conn, "
    SELECT appointments.*, services.service_name 
    FROM appointments 
    JOIN services ON appointments.service_id = services.id 
    WHERE appointments.user_id = '$user_id' 
    ORDER BY appointment_date ASC
");

$total_appointments = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | Glow & Style Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .sidebar { background: var(--white); }
        .logo span { color: var(--primary); font-style: italic; }
        
        /* Specific Badge for Cancellation Requests */
        .badge.cancellation-requested {
            background: #ffeaa7;
            color: #d6a01d;
        }

        .cancel-req-btn {
            background: #ff7675;
            color: white;
            padding: 6px 12px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cancel-req-btn:hover {
            background: #d63031;
            box-shadow: 0 4px 10px rgba(214, 48, 49, 0.2);
        }
    </style>
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <div class="sidebar-top">
            <h2 class="logo">Glow & Style <span>Panel</span></h2>
            
            <nav class="menu">
                <a href="dashboard.php" class="menu-item active">
                    <span class="icon">📊</span> Dashboard
                </a>
                <a href="book_appointment.php" class="menu-item">
                    <span class="icon">📅</span> Book Now
                </a>
                <a href="edit_profile.php" class="menu-item">
                    <span class="icon">👤</span> My Profile
                </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <main class="content">
        <div class="welcome">
            <h1 style="font-family: 'Playfair Display', serif;">Welcome, <?php echo $_SESSION['name']; ?></h1>
            <p>Manage your beauty schedule and appointment requests.</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Bookings</h3>
                <p><?php echo $total_appointments; ?></p>
            </div>
        </div>

        <div class="table-container">
            <div class="section-header" style="text-align: left; margin-bottom: 20px;">
                <h2 style="font-family: 'Playfair Display', serif;">My Appointments</h2>
                <div class="underline" style="margin: 5px 0;"></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total_appointments > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo $row['service_name']; ?></td>
                            <td><?php echo date("M d, Y", strtotime($row['appointment_date'])); ?></td>
                            <td><?php echo date("h:i A", strtotime($row['appointment_time'])); ?></td>
                            <td>
                                <span class="badge <?php echo str_replace(' ', '-', strtolower($row['status'])); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['status'] == 'Pending' || $row['status'] == 'Approved'): ?>
                                    <a href="request_cancel.php?id=<?php echo $row['id']; ?>" 
                                       class="cancel-req-btn"
                                       onclick="return confirm('Are you sure you want to request a cancellation? This requires Admin approval.')">
                                       Request Cancel
                                    </a>
                                <?php elseif($row['status'] == 'Cancellation Requested'): ?>
                                    <small style="color: var(--text-gray); font-style: italic;">Awaiting Approval</small>
                                <?php else: ?>
                                    <span style="color: #ccc;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-gray); padding: 40px;">
                                No appointments found. <a href="book_appointment.php" style="color: var(--primary);">Book your first session today!</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</div>

</body>
</html>