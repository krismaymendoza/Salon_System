<?php
session_start();
include 'db.php';

$token = isset($_GET['token']) ? trim((string)$_GET['token']) : '';
if ($token === '') {
    die('Missing token.');
}

// Simple verification using users.reset_token + reset_expires.
// This is reused for email verification.
$query = mysqli_prepare($conn, "SELECT id, reset_expires, reset_token FROM users WHERE reset_token = ? LIMIT 1");
mysqli_stmt_bind_param($query, 's', $token);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die('Invalid verification link.');
}

$expires = $user['reset_expires'];
if ($expires === null) {
    die('Verification link expired.');
}

if (strtotime($expires) < time()) {
    die('Verification link expired.');
}

// Mark as verified (we don\'t have a verified column; so clear token fields)
$update = mysqli_prepare($conn, "UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = ?");
mysqli_stmt_bind_param($update, 'i', $user['id']);
mysqli_stmt_execute($update);

// Optional: create in-app notification
include 'includes/notification_helper.php';
createNotification(
    $conn,
    (int)$user['id'],
    'Email verified',
    'Your account email has been verified. You can now log in.'
);

// Redirect to login
header('Location: login.php?verified=1');
exit();

