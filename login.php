<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($query) > 0){
        $user = mysqli_fetch_assoc($query);
        if(password_verify($password, $user['password'])){
            // Optional: If reset_token/reset_expires is set, we treat it as "email not verified yet".
            if(!empty($user['reset_token']) && !empty($user['reset_expires'])){
                if(strtotime($user['reset_expires']) >= time()){
                    echo "<script>alert('Please verify your email before logging in.');</script>";
                    exit();
                }
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'];

            include 'includes/logger.php';
            logAction($conn, $_SESSION['user_id'], $_SESSION['role'], "Logged in");

            // Send Gmail login notification via PHPMailer (manual install under includes/phpmailer/)
            $loginTime = date('Y-m-d H:i:s');
            $userEmail = $user['email'];
            $userName  = $user['first_name'] ?? $user['name'] ?? 'User';


            include 'includes/mailer.php';
            $emailOk = sendLoginNotificationEmail($userEmail, $userName, $loginTime, 'Password');

            // Also create an in-app notification so user can see it in the UI.
            include 'includes/notification_helper.php';
            $title = 'Login notification';
            $msg   = "Your account was logged in on {$loginTime}.";
            createNotification($conn, $_SESSION['user_id'], $title, $msg);

            if($user['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            }
            elseif($user['role'] == 'employee'){
                header("Location: employee/dashboard.php");
            }
            else{
                header("Location: customer/dashboard.php");
            }
        } else {
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        echo "<script>alert('Account not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Glow & Style Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="form-page">

    <div class="form-container">
        <form method="POST" class="auth-form">
            <div class="section-header">
                <h2>Welcome Back</h2>
                <div class="underline"></div>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn-book">
                Login
            </button>

            <p class="form-footer"> 
                <a href="forgot_password.php">Forgot Password?</a>
            </p>

            <p class="form-footer">
                No account yet? 
                <a href="register.php">Create an Account</a>
            </p>
            
            <p class="form-footer">
                <a href="index.php">← Back to Home</a>
            </p>
        </form>
    </div>

</body>
</html>