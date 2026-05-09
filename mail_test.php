<?php

// Simple PHPMailer SMTP test page.
// Usage: http://localhost/Salon_System/mail_test.php?email=you@example.com

require_once __DIR__ . '/includes/mailer.php';

$to = isset($_GET['email']) ? trim((string)$_GET['email']) : '';
if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    echo 'Missing or invalid ?email= query parameter.';
    exit;
}

$ok = sendLoginNotificationEmail(
    $to,
    'Test User',
    date('Y-m-d H:i:s'),
    'Password (test)'
);

// Helpful tip for local SMTP debugging
if (getenv('APP_DEBUG') === '1') {
    echo '<pre>Debug mode ON (APP_DEBUG=1). Check the PHPMailer SMTP output above (if any) and XAMPP error logs.</pre>';
}

echo $ok ? 'OK: Email was sent.' : 'FAIL: Email was not sent (check SMTP creds/logs).';



