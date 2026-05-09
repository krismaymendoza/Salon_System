<?php
include 'db.php';
include 'includes/mailer.php';

if(isset($_POST['reset_request'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user_query = mysqli_query($conn, "SELECT id, name FROM users WHERE email='$email'");



    if(mysqli_num_rows($user_query) > 0){
        $token = bin2hex(random_bytes(32)); // Generates a secure random token
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

        mysqli_query($conn, "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE email='$email'");

        $reset_link = "http://localhost/Salon_System/reset_password.php?token=" . $token;

        // Send email
        $toName = mysqli_fetch_assoc($user_query)['name'] ?? '';
        $sent = sendPasswordResetEmail($email, $toName, $reset_link);

        if ($sent) {
            echo "<script>alert('A reset link has been sent to your email.');</script>";
        } else {
            echo "<script>alert('Reset link created, but email could not be sent.');</script>";
        }
    } else {
        echo "<script>alert('Email address not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password | Glow & Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="form-page">
    <div class="form-container">
        <form method="POST" class="auth-form">
            <h2 style="font-family: 'Playfair Display', serif;">Reset Password</h2>
            <div class="underline" style="width: 50px; height: 3px; background: #ff8fab; margin: 10px auto 20px;"></div>
            <p style="font-size: 14px; margin-bottom: 20px;">Enter your email to receive a password reset link.</p>
            
            <input type="email" name="email" placeholder="Email Address" required 
                   style="width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 50px; border: 1px solid #eee;">
            
            <button type="submit" name="reset_request" class="btn-book" 
                    style="width: 100%; background: #ff8fab; color: white; border: none; padding: 15px; border-radius: 50px; cursor: pointer;">
                Send Reset Link
            </button>
            <p style="margin-top: 20px;"><a href="login.php" style="color: #666; text-decoration: none;">Back to Login</a></p>
        </form>
    </div>
</body>
</html>