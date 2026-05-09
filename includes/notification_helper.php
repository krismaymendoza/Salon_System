<?php

function createNotification(
    $conn,
    $user_id,
    $title,
    $message
){

    $stmt = $conn->prepare("
        INSERT INTO notifications
        (user_id, title, message)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
        "iss",
        $user_id,
        $title,
        $message
    );

    $stmt->execute();

    $stmt->close();
}

?>