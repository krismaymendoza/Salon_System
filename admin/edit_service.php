<?php
session_start();

include '../db.php';
include '../includes/logger.php'; // Include the logging utility

$id = $_GET['id'];

$query = mysqli_query($conn, "
SELECT * FROM services
WHERE id='$id'
");

$service = mysqli_fetch_assoc($query);

if(isset($_POST['update_service'])){

    $service_name = $_POST['service_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $specialization = $_POST['specialization'];

    /*
    |--------------------------------------------------------------------------
    | IMAGE HANDLING
    |--------------------------------------------------------------------------
    */
    $image_name = $service['image'];

    if($_FILES['image']['name'] != ""){
        $image_name = $_FILES['image']['name'];
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/services/" . $image_name
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE EXECUTION
    |--------------------------------------------------------------------------
    */
    $update = mysqli_query($conn, "
    UPDATE services
    SET
    service_name='$service_name',
    image='$image_name',
    price='$price',
    duration='$duration',
    specialization='$specialization'
    WHERE id='$id'
    ");

    if($update){
        // Log the specific service update in the system_logs table
        logAction($conn,
            $_SESSION['user_id'],
            $_SESSION['role'],
            "Updated service: " . $service_name
        );
    }

    header("Location: services.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Edit Service</title>

    <link rel="stylesheet" href="../css/admin.css">

    <style>

        .form-container{
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 600px;
        }

        input{
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button{
            background: #3a86ff;
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 10px;
            cursor: pointer;
        }

        img{
            width: 200px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

    </style>

</head>
<body>

<div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content">

        <div class="form-container">

            <h1>Edit Service</h1>

            <br>

            <img
            src="../uploads/services/<?php echo $service['image']; ?>">

            <form method="POST" enctype="multipart/form-data">

                <input
                type="text"
                name="service_name"
                value="<?php echo $service['service_name']; ?>"
                required>

                <input
                type="number"
                name="price"
                value="<?php echo $service['price']; ?>"
                required>

                <input
                type="text"
                name="duration"
                value="<?php echo $service['duration']; ?>"
                required>

                <input
                type="text"
                name="specialization"
                value="<?php echo $service['specialization']; ?>"
                required>

                <input
                type="file"
                name="image">

                <button
                type="submit"
                name="update_service">

                    Update Service

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>