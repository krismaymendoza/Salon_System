<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Minimal Gmail SMTP mail helper.
// Requires PHPMailer files to be present in includes/phpmailer/ (manual install approach).
//
// Expected manual install location:
//   c:/xampp/htdocs/salon_system/includes/phpmailer/PHPMailer.php
//   c:/xampp/htdocs/salon_system/includes/phpmailer/SMTP.php
//   c:/xampp/htdocs/salon_system/includes/phpmailer/Exception.php
//   plus any required dependencies.

if (!function_exists('sendLoginNotificationEmail')) {
// Function body removed (duplicate) to prevent redeclare errors.
}

function _sendLoginNotificationEmail($toEmail, $toName, $loginTime, $loginMethod = 'Password'){
    // Basic validation

    $toEmail = trim((string)$toEmail);
    if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // ====== CONFIG (EDIT THESE) ======
    // Gmail SMTP with App Password is required.
    $smtpHost = 'smtp.gmail.com';
    $smtpPort = 587;

    // Put your Gmail address here (sender)
    // Use environment variables if available; otherwise edit these placeholders.
    $fromEmail = getenv('GMAIL_FROM_EMAIL') ?: 'YOUR_GMAIL_ADDRESS@gmail.com';
    $fromName  = 'Glow & Style Salon';

    // Gmail App Password is required (NOT your normal password)
    $smtpUser  = getenv('GMAIL_SMTP_USER') ?: 'YOUR_GMAIL_ADDRESS@gmail.com';
    $smtpPass  = getenv('GMAIL_SMTP_PASS') ?: 'YOUR_GMAIL_APP_PASSWORD';


    // =================================

    $subject = 'Login notification — You are signed in';
    $body = "Hello {$toName},\n\n".
            "This is a notification that your account has been logged into the Salon System successfully.\n\n".
            "Time: {$loginTime}\n".
            "Method: {$loginMethod}\n\n".
            "If this was not you, please change your password immediately.". "\n\n".
            "Regards,\n".
            "Glow & Style Salon";

    // Load PHPMailer classes (manual install)
    // PHPMailer manual location in this project:
    //   includes/phpmailer/PHPMailer-master/src/
    $base = __DIR__ . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'PHPMailer-master' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    require_once $base . 'PHPMailer.php';
    require_once $base . 'SMTP.php';
    require_once $base . 'Exception.php';


    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        // Recipients
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Basic debug output in dev; caller can handle via return value.
        // Avoid echo in production if you prefer.
        if (getenv('APP_DEBUG') === '1') {
            error_log('Mailer error: ' . $mail->ErrorInfo);
        } else {
            error_log('Mailer error: ' . $mail->ErrorInfo);
        }
        return false;
    }

}

function sendPasswordResetEmail($toEmail, $toName, $resetLink){
    $toEmail = trim((string)$toEmail);
    if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $smtpHost = 'smtp.gmail.com';
    $smtpPort = 587;

    $fromEmail = getenv('GMAIL_FROM_EMAIL') ?: 'YOUR_GMAIL_ADDRESS@gmail.com';
    $fromName  = 'Glow & Style Salon';

    $smtpUser  = getenv('GMAIL_SMTP_USER') ?: 'YOUR_GMAIL_ADDRESS@gmail.com';
    $smtpPass  = getenv('GMAIL_SMTP_PASS') ?: 'YOUR_GMAIL_APP_PASSWORD';

    $subject = 'Reset your password — Glow & Style Salon';
    $body = "Hello {$toName},\n\n".
            "We received a request to reset your password.\n\n".
            "Click this link to reset your password:\n{$resetLink}\n\n".
            "If you did not request this, you can ignore this email.\n\n".
            "Regards,\nGlow & Style Salon";

    $base = __DIR__ . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'PHPMailer-master' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    require_once $base . 'PHPMailer.php';
    require_once $base . 'SMTP.php';
    require_once $base . 'Exception.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        if (getenv('APP_DEBUG') === '1') {
            error_log('Password reset mailer error: ' . $mail->ErrorInfo);
        } else {
            error_log('Password reset mailer error: ' . $mail->ErrorInfo);
        }
        return false;
    }

}

function sendLoginNotificationEmail($toEmail, $toName, $loginTime, $loginMethod = 'Password'){
    // Basic validation
    $toEmail = trim((string)$toEmail);
    if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }


    // ====== CONFIG (EDIT THESE) ======
    // Gmail SMTP with App Password is required.
    $smtpHost = 'smtp.gmail.com';
    $smtpPort = 587;

    // Put your Gmail address here (sender)
    // Use environment variables if available; otherwise edit these placeholders.
    $fromEmail = getenv('GMAIL_FROM_EMAIL') ?: 'YOUR_GMAIL_ADDRESS@gmail.com';
    $fromName  = 'Glow & Style Salon';

    // Gmail App Password is required (NOT your normal password)
    $smtpUser  = getenv('GMAIL_SMTP_USER') ?: 'YOUR_GMAIL_ADDRESS@gmail.com';
    $smtpPass  = getenv('GMAIL_SMTP_PASS') ?: 'YOUR_GMAIL_APP_PASSWORD';



    // =================================

    $subject = 'Login notification — You are signed in';
    $body = "Hello {$toName},\n\n".
            "This is a notification that your account has been logged into the Salon System successfully.\n\n".
            "Time: {$loginTime}\n".
            "Method: {$loginMethod}\n\n".
            "If this was not you, please change your password immediately.". "\n\n".
            "Regards,\n".
            "Glow & Style Salon";

    // Load PHPMailer classes (manual install)
    // PHPMailer manual location in this project:
    //   includes/phpmailer/PHPMailer-master/src/
    $base = __DIR__ . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'PHPMailer-master' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    require_once $base . 'PHPMailer.php';
    require_once $base . 'SMTP.php';
    require_once $base . 'Exception.php';


    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        // Recipients
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Basic debug output in dev; caller can handle via return value.
        // Avoid echo in production if you prefer.
        if (getenv('APP_DEBUG') === '1') {
            error_log('Mailer error: ' . $mail->ErrorInfo);
        } else {
            error_log('Mailer error: ' . $mail->ErrorInfo);
        }
        return false;
    }

}

