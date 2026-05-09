<?php
session_start();

include '../db.php';

if(isset($_POST['add_service'])){

    $service_name = $_POST['service_name'];

    $price = $_POST['price'];

    $duration = $_POST['duration'];

    $specialization = $_POST['specialization'];

    /*
    |--------------------------------------------------------------------------
    | IMAGE UPLOAD
    |--------------------------------------------------------------------------
    */

    $image_name = $_FILES['image']['name'];

    $tmp_name = $_FILES['image']['tmp_name'];

    move_uploaded_file(
        $tmp_name,
        "../uploads/services/" . $image_name
    );

    /*
    |--------------------------------------------------------------------------
    | INSERT SERVICE
    |--------------------------------------------------------------------------
    */

    mysqli_query($conn, "
    INSERT INTO services
    (
        service_name,
        image,
        price,
        duration,
        specialization
    )
    VALUES
    (
        '$service_name',
        '$image_name',
        '$price',
        '$duration',
        '$specialization'
    )
    ");

    header("Location: services.php");
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Admin Panel | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    
    <title>Add Service</title>

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
            background: #ff8fab;
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 10px;
            cursor: pointer;
        }

    </style>

</head>
<body>

<div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <div class="content">

        <div class="form-container">

            <h1>Add Service</h1>

            <br>

            <form method="POST" enctype="multipart/form-data">

                <input
                type="text"
                name="service_name"
                placeholder="Service Name"
                required>

                <input
                type="number"
                name="price"
                placeholder="Price"
                required>

                <input
                type="text"
                name="duration"
                placeholder="Duration"
                required>

                <input
                type="text"
                name="specialization"
                placeholder="Specialization"
                required>

                <input
                type="file"
                name="image"
                required>

                <button
                type="submit"
                name="add_service">

                    Add Service

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>