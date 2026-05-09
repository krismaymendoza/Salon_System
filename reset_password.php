<?php
include 'db.php';

if(isset($_GET['token'])){
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $now = date("Y-m-d H:i:s");

    // Verify token validity and expiration
    $query = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token' AND reset_expires > '$now'");
    $user = mysqli_fetch_assoc($query);

    if(!$user){
        echo "<script>alert('Invalid or expired token. Please request a new reset link.');</script>";
    }

    if(isset($_POST['update_password'])){
        if($user){
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Update password and clear token to prevent reuse
            $update = mysqli_query(
                $conn,
                "UPDATE users SET password='$new_password', reset_token=NULL, reset_expires=NULL WHERE reset_token='$token'"
            );

            if($update){
                echo "<script>alert('Password updated successfully!'); setTimeout(function(){ window.location='login.php'; }, 50);</script>";
            }else{
                echo "<script>alert('Password update failed. Please try again.');</script>";
            }
        }
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Password | Glow & Style</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="form-page">
    <div class="form-container">
        <form method="POST" class="auth-form">
            <h2>Create New Password</h2>
            <input type="password" name="password" placeholder="Enter New Password" required 
                   style="width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 50px; border: 1px solid #eee;">
            <button type="submit" name="update_password" class="btn-book" 
                    style="width: 100%; background: #ff8fab; color: white; border: none; padding: 15px; border-radius: 50px; cursor: pointer;">
                Update Password
            </button>
        </form>
    </div>
</body>
</html>

