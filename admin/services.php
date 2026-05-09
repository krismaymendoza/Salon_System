<?php
session_start();

if(!isset($_SESSION['user_id']) ||
   $_SESSION['role'] != 'admin'){

    header("Location: ../login.php");
}

include '../db.php';

$query = mysqli_query($conn, "
SELECT * FROM services
ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Admin Panel | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Services Management</title>

    <link rel="stylesheet" href="../css/admin.css">

    <style>

        .top-bar{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .add-btn{
            background: #ff8fab;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }

        .services-grid{
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .service-card{
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .service-card img{
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .service-content{
            padding: 20px;
        }

        .service-content h3{
            margin-bottom: 10px;
            color: #444;
        }

        .price{
            color: #ff8fab;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .duration,
        .specialization{
            margin-bottom: 8px;
            color: #666;
        }

        .actions{
            margin-top: 20px;
        }

        .edit-btn,
        .delete-btn{
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
        }

        .edit-btn{
            background: #3a86ff;
        }

        .delete-btn{
            background: #d90429;
        }

    </style>

</head>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <?php include 'includes/sidebar.php'; ?>

    <!-- CONTENT -->

    <div class="content">

        <div class="top-bar">

            <h1>Services Management</h1>

            <a href="add_service.php" class="add-btn">
                Add Service
            </a>

        </div>

        <div class="services-grid">

            <?php while($row = mysqli_fetch_assoc($query)){ ?>

            <div class="service-card">

                <img
                src="../uploads/services/<?php echo $row['image']; ?>">

                <div class="service-content">

                    <h3>
                        <?php echo $row['service_name']; ?>
                    </h3>

                    <div class="price">

                        ₱<?php echo $row['price']; ?>

                    </div>

                    <div class="duration">

                        Duration:
                        <?php echo $row['duration']; ?>

                    </div>

                    <div class="specialization">

                        Specialization:
                        <?php echo $row['specialization']; ?>

                    </div>

                    <div class="actions">

                        <a
                        class="edit-btn"
                        href="edit_service.php?id=<?php echo $row['id']; ?>">
                            Edit
                        </a>

                        <a
                        class="delete-btn"
                        href="delete_service.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Delete service?')">
                            Delete
                        </a>

                    </div>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>