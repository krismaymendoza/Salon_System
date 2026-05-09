<?php
session_start();

include '../db.php';

$user_id = $_SESSION['user_id'];

// Debug info (only returned to admin JS if notifications are empty)
// This helps identify session/user_id issues.

$query = mysqli_query($conn, "
SELECT *
FROM notifications
WHERE user_id='$user_id'
ORDER BY created_at DESC
LIMIT 10
");

$output = "";

$count = 0;

$rowsFound = 0;

while($row = mysqli_fetch_assoc($query)){

    $rowsFound++;
    
    if($row['status'] == 'Unread'){
        $count++;
    }

    $output .= '\r\n\r\n    <div class="notification-item">\r\n\r\n        <h4>'.$row['title'].'</h4>\r\n\r\n        <p>'.$row['message'].'</p>\r\n\r\n        <small>'.$row['created_at'].'</small>\r\n\r\n    </div>\r\n\r\n    ';
}

// If no notifications were found, return a helpful placeholder so we can see why UI is empty.
if ($rowsFound === 0) {
    $output = '<div class="notification-item"><h4>No notifications</h4><p>user_id=' . htmlspecialchars($user_id) . '</p></div>';
}

$response = array(

    "notifications" => $output,
    "count" => $count

);

echo json_encode($response);

?>
