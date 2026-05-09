<?php

include '../db.php';

$query = mysqli_query($conn, "
SELECT
appointments.*,
users.first_name,
users.last_name,
services.service_name
FROM appointments
JOIN users ON appointments.user_id = users.id
JOIN services ON appointments.service_id = services.id
");

$events = array();

while($row = mysqli_fetch_assoc($query)){

    $color = "#ffb703";

    if($row['status'] == "Approved"){
        $color = "#3a86ff";
    }

    if($row['status'] == "Completed"){
        $color = "#38b000";
    }

    if($row['status'] == "Cancelled"){
        $color = "#d90429";
    }

    $events[] = array(

        'id' => $row['id'],

        'title' =>
            $row['service_name'] .
            " - " .
            $row['first_name'],

        'start' =>
            $row['appointment_date'] .
            "T" .
            $row['appointment_time'],

        'color' => $color,

        'extendedProps' => array(

            'customer' =>
                $row['first_name'] . " " .
                $row['last_name'],

            'service' =>
                $row['service_name'],

            'status' =>
                $row['status']
        )
    );
}

echo json_encode($events);

?>