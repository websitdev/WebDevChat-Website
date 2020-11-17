<?php
    //database credencials
    $db_server_name="localhost";
    $db_username=getenv('DB_USER');
    $db_password=getenv('DB_PASSWORD');
    $db_name="webdvxms_WebDevChat";

    $mysqli = new mysqli($db_server_name, $db_username, $db_password, $db_name);

    if($mysqli -> connect_errno) {
        $response['db_conn_succ'] = false;
        echo json_encode($response);
        exit();
    }
?>
