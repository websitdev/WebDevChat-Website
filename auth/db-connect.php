<?php
    @ $mysqli = new mysqli("address", "username", "password", "db");

    if($mysqli -> connect_errno) {
        $response['db_err'] = "Could not connect to database at the moment.<br>Please try again later.";
        echo json_encode($response);
        exit();
    }
?>
