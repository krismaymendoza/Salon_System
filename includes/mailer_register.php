<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmailVerification($toEmail, $toName, $verificationLink){
    $toEmail = trim((string)$toEmail);
    if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $smtpHost = 'smtp.gmail.com';
    $smtpPort = 587;

    // IMPORTANT:
    // - GMAIL_FROM_EMAIL and GMAIL_SMTP_USER must be the SAME Gmail address (example: krismay@gmail.com)
    // - GMAIL_SMTP_PASS must be the Gmail App Password (NOT your normal password)
    $fromEmail = getenv('GMAIL_FROM_EMAIL') ?: '';
    $fromName  = 'Glow & Style Salon';

    $smtpUser  = getenv('GMAIL_SMTP_USER') ?: '';
    $smtpPass  = getenv('GMAIL_SMTP_PASS') ?: '';

    // In local dev, show the real PHPMailer error so we can fix SMTP/credentials quickly.
    $debug = getenv('APP_DEBUG') === '1';

    $missing = [];
    if ($fromEmail === '') $missing[] = 'GMAIL_FROM_EMAIL';
    if ($smtpUser === '') $missing[] = 'GMAIL_SMTP_USER';
    if ($smtpPass === '') $missing[] = 'GMAIL_SMTP_PASS';

    if (!empty($missing)) {
        // Fail fast with a clear message when debugging.
        if ($debug) {
            echo '<div style="color:red;font-family:monospace">SMTP config missing: ' . htmlspecialchars(implode(', ', $missing)) . '. Set them in PHP environment (XAMPP) and use a Gmail App Password.</div>';
        }
        return false;
    }



    $subject = 'Verify your email — Glow & Style Salon';


    $body = "Hello {$toName},\n\n".
            "Please verify your email address for your new account.\n\n".
            "Click this link to verify:\n{$verificationLink}\n\n".
            "If you did not create this account, you can ignore this email.\n\n".
            "Regards,\nGlow & Style Salon";

    $base = __DIR__ . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'PHPMailer-master' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    require_once $base . 'PHPMailer.php';
    require_once $base . 'SMTP.php';
    require_once $base . 'Exception.php';

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

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

        if ($debug) {
            // Show PHPMailer internal SMTP conversation for fast diagnosis.
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function($str, $level) { echo '<pre style="margin:0;">' . htmlspecialchars($str) . "\n" . '</pre>'; };
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        $errorInfo = $mail->ErrorInfo ?: $e->getMessage();
        error_log('Mailer register verification error: ' . $errorInfo);

        if ($debug) {
            echo '<div style="color:red;font-family:monospace">Mailer ErrorInfo: ' . htmlspecialchars($errorInfo) . '</div>';
            echo '<div style="color:#666;font-family:monospace">SMTP Host: ' . htmlspecialchars($smtpHost) . ', Port: ' . htmlspecialchars((string)$smtpPort) . '</div>';
            echo '<div style="color:#666;font-family:monospace">From: ' . htmlspecialchars($fromEmail) . '</div>';
        }

        return false;
    }

}

