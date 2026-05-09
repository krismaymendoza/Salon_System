<?php
include 'db.php';

if(isset($_POST['register'])){

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check_email) > 0){
        echo "<script>alert('Email already exists!');</script>";
    } else {

        // Line 19: The INSERT query
        $query = "INSERT INTO users 
        (first_name, last_name, contact_number, email, password, role)
        VALUES
        ('$first_name','$last_name','$contact_number','$email','$password', 'customer')";

        if(mysqli_query($conn, $query)){
            /* |--------------------------------------------------------------------------
            | GENERATE CUSTOM ID (cstmr-2026-0001)
            |--------------------------------------------------------------------------
            */
            $new_user_id = mysqli_insert_id($conn);
            $year = date("Y");
            $custom_id = "cstmr-" . $year . "-" . str_pad($new_user_id, 4, '0', STR_PAD_LEFT);

            // Update the user record with the new formatted ID
            mysqli_query($conn, "UPDATE users SET custom_id='$custom_id' WHERE id='$new_user_id'");

            // -------------------- Email verification --------------------
            // Reuse users.reset_token + reset_expires columns for verification.
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 60 * 60); // 1 hour

            mysqli_query(
                $conn,
                "UPDATE users SET reset_token='$token', reset_expires='$expires' WHERE id='$new_user_id'"
            );

            $verificationLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/salon_system/register_verify_email.php?token=' . urlencode($token);

            $fullName = trim(($first_name ?? '') . ' ' . ($last_name ?? ''));
            if ($fullName === '') $fullName = 'User';

            include 'includes/mailer_register.php';
            $emailSent = sendEmailVerification($email, $fullName, $verificationLink);

            if($emailSent){
                echo "<script>alert('Registration Successful! Please verify your email to activate your account.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Registration Successful! (But verification email could not be sent.)'); window.location='login.php';</script>";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Glow & Style Salon</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="form-page">

<div class="form-container">

    <form method="POST" class="auth-form">

        <div class="section-header">
            <h2 style="font-family: 'Playfair Display', serif;">Create Account</h2>
            <div class="underline"></div>
        </div>

        <div class="input-group">
            <input type="text" name="first_name" placeholder="First Name" required>
        </div>

        <div class="input-group">
            <input type="text" name="last_name" placeholder="Last Name" required>
        </div>

        <div class="input-group">
            <input type="text" name="contact_number" placeholder="Contact Number" required>
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Create Password" required>
        </div>

        <button type="submit" name="register" class="btn-book" style="width: 100%; border: none; cursor: pointer;">
            Join the Salon
        </button>

        <p class="form-footer">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </form>

</div>

</body>
</html>