<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
}

include '../db.php';

$query = mysqli_query($conn, "
SELECT employees.*, users.first_name, users.last_name, users.email
FROM employees
JOIN users ON employees.user_id = users.id
ORDER BY employees.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Admin Panel | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Employees</title>

    <link rel="stylesheet" href="../css/admin.css">

    <style>

        .top-bar{
            display:flex;
            justify-content:space-between;
            margin-bottom:20px;
        }

        .add-btn{
            background:#ff8fab;
            color:white;
            padding:10px 15px;
            border-radius:8px;
            text-decoration:none;
        }

        .card-grid{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:20px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:15px;
            box-shadow:0 0 10px rgba(0,0,0,0.08);
        }

        .actions a{
            padding:6px 10px;
            border-radius:6px;
            text-decoration:none;
            color:white;
            font-size:13px;
        }

        .edit{background:#3a86ff;}
        .delete{background:#d90429;}

    </style>

</head>
<body>

<div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content">

        <div class="top-bar">

            <h1>Employees</h1>

            <a class="add-btn" href="add_employee.php">
                Add Employee
            </a>

        </div>

        <div class="card-grid">

            <?php while($row = mysqli_fetch_assoc($query)){ ?>

            <div class="card">

                <h3>
                    <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                </h3>

                <p><?php echo $row['email']; ?></p>

                <p>Specialization: <?php echo $row['specialization']; ?></p>

                <div class="actions">

                    <a class="edit" href="edit_employee.php?id=<?php echo $row['id']; ?>">
                        Edit
                    </a>

                    <a class="delete" href="delete_employee.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete employee?')">
                        Delete
                    </a>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>