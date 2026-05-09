<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include '../db.php';
include '../includes/logger.php'; // Required for tracking changes

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];

    $update = mysqli_query($conn, "
        UPDATE users SET
        first_name='$first_name',
        last_name='$last_name',
        contact_number='$contact_number'
        WHERE id='$user_id'
    ");

    if($update){
        $_SESSION['name'] = $first_name;

        // Log the profile update action
        logAction($conn, 
            $_SESSION['user_id'], 
            $_SESSION['role'], 
            "Updated personal profile information"
        );

        echo "<script>alert('Profile Updated Successfully'); window.location='edit_profile.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Glow & Style Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="form-page">

    <div class="form-container">
        <form method="POST" class="auth-form">
            <div class="section-header">
                <h2>Edit Profile</h2>
                <div class="underline"></div>
            </div>

            <div class="input-group">
                <label style="display: block; text-align: left; margin-bottom: 5px; font-size: 13px; color: #666;">First Name</label>
                <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
            </div>

            <div class="input-group">
                <label style="display: block; text-align: left; margin-bottom: 5px; font-size: 13px; color: #666;">Last Name</label>
                <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
            </div>

            <div class="input-group">
                <label style="display: block; text-align: left; margin-bottom: 5px; font-size: 13px; color: #666;">Contact Number</label>
                <input type="text" name="contact_number" value="<?php echo $user['contact_number']; ?>" required>
            </div>

            <div class="input-group">
                <label style="display: block; text-align: left; margin-bottom: 5px; font-size: 13px; color: #666;">Email Address (Fixed)</label>
                <input type="email" value="<?php echo $user['email']; ?>" disabled style="background: #f0f0f0; cursor: not-allowed;">
            </div>

            <button type="submit" name="update" class="btn-book" style="width: 100%; border: none; cursor: pointer;">
                Update Profile
            </button>

            <p class="form-footer">
                <a href="dashboard.php">← Back to Dashboard</a>
            </p>
        </form>
    </div>

</body>
</html>