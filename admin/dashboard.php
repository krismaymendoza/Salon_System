<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

include '../db.php';

/*
|--------------------------------------------------------------------------
| SUMMARY METRICS
|--------------------------------------------------------------------------
*/
$total_customers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='customer'"));
$total_appointments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM appointments WHERE status != 'Cancelled'"));
$total_services = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM services"));
$total_employees = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM employees"));

/*
|--------------------------------------------------------------------------
| ANALYTICS QUERIES
|--------------------------------------------------------------------------
*/
// 1. Bookings per Month (Last 6 Months)
$monthly_query = mysqli_query($conn, "
    SELECT MONTHNAME(appointment_date) as month, COUNT(*) as total 
    FROM appointments 
    WHERE status != 'Cancelled'
    GROUP BY MONTH(appointment_date) 
    ORDER BY MONTH(appointment_date) ASC 
    LIMIT 6
");
$months = []; $counts = [];
while($row = mysqli_fetch_assoc($monthly_query)) {
    $months[] = $row['month'];
    $counts[] = $row['total'];
}

// 2. Service Popularity
$service_popularity = mysqli_query($conn, "
    SELECT services.service_name, COUNT(appointments.id) as count 
    FROM appointments 
    JOIN services ON appointments.service_id = services.id 
    GROUP BY services.service_name
");
$service_names = []; $service_counts = [];
while($row = mysqli_fetch_assoc($service_popularity)) {
    $service_names[] = $row['service_name'];
    $service_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-container {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 25px;
            margin-top: 30px;
        }
        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }
        .chart-card h3 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        canvas { width: 100% !important; height: auto !important; }
    </style>
</head>
<body>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content">
        <div class="welcome">
            <h1 style="font-family: 'Playfair Display', serif;">Admin Overview</h1>
            <p>Monitor your salon's performance and client activity.</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Customers</h3>
                <p><?php echo $total_customers; ?></p>
            </div>
            <div class="card">
                <h3>Total Bookings</h3>
                <p><?php echo $total_appointments; ?></p>
            </div>
            <div class="card">
                <h3>Services</h3>
                <p><?php echo $total_services; ?></p>
            </div>
            <div class="card">
                <h3>Employees</h3>
                <p><?php echo $total_employees; ?></p>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-card">
                <h3>Appointment Trends (Monthly)</h3>
                <canvas id="trendChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Service Distribution</h3>
                <canvas id="serviceChart"></canvas>
            </div>
        </div>
    </main>
</div>

<script>
// Trend Chart Initialization
const trendCtx = document.getElementById('trendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Total Bookings',
            data: <?php echo json_encode($counts); ?>,
            backgroundColor: '#ff8fab',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { display: false } } }
    }
});

// Service Chart Initialization
const serviceCtx = document.getElementById('serviceChart').getContext('2d');
new Chart(serviceCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($service_names); ?>,
        datasets: [{
            data: <?php echo json_encode($service_counts); ?>,
            backgroundColor: ['#ff8fab', '#fb6f92', '#ffc2d1', '#ffe5ec', '#ffb3c1'],
            hoverOffset: 4,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } }
        }
    }
});
</script>

</body>
</html>