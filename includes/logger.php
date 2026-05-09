<?php

function logAction($conn, $user_id, $role, $action){

    mysqli_query($conn, "
        INSERT INTO system_logs (user_id, role, action)
        VALUES ('$user_id', '$role', '$action')
    ");
}

?>