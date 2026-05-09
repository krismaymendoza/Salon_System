<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
}

include '../db.php';

$query = mysqli_query($conn, "
SELECT system_logs.*, users.first_name, users.last_name
FROM system_logs
LEFT JOIN users ON system_logs.user_id = users.id
ORDER BY system_logs.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Admin Panel | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <title>System Logs</title>

    <link rel="stylesheet" href="../css/admin.css">

    <style>

        .table-container{
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        th{
            background: #ffd6e0;
            padding: 15px;
        }

        td{
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .badge{
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
        }

        .admin{
            background: #3a86ff;
        }

        .employee{
            background: #ff8fab;
        }

        .customer{
            background: #38b000;
        }

    </style>

</head>
<body>

<div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content">

        <h1>System Logs</h1>

        <br>

        <div class="table-container">

            <table>

                <tr>

                    <th>User</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Date</th>

                </tr>

                <?php while($row = mysqli_fetch_assoc($query)){ ?>

                <tr>

                    <td>
                        <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                    </td>

                    <td>
                        <span class="badge <?php echo $row['role']; ?>">
                            <?php echo $row['role']; ?>
                        </span>
                    </td>

                    <td>
                        <?php echo $row['action']; ?>
                    </td>

                    <td>
                        <?php echo $row['created_at']; ?>
                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

</body>
</html>