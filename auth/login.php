<?php
	$response = array(
		'db_conn_succ' => true,
		'id_valid' => true,
		'id_match' => false,
		'pass_match' => false
	);
	require("db-connect.php");
	
	$identity = htmlspecialchars(trim($_POST['identity'])); //username or email
    $password = htmlspecialchars(trim($_POST['login-password'])); //password
	
    if(strpos($identity, ' ')) {
		$response['id_valid'] = false;
		$response['msg_id'] = "Username or Email must not contain spaces";
		echo json_encode($response);
		exit();
	}
    $id = $mysqli -> real_escape_string($identity);

    //Check if username is present in database
    if($mysqli -> query("SELECT username FROM users WHERE username='$id'") -> num_rows > 0) {
		$response['id_match'] = true;
        $actual_pass = $mysqli -> query("SELECT password FROM users WHERE username='$id'") -> fetch_row()[0];
		if(password_verify($password, $actual_pass)) {
			$response['pass_match'] = true;
			$response['pass_hash'] = $actual_pass;
		}
		else $response['msg_pass'] = "Incorrect password";
	} //Check if email is present in database
	else if($mysqli -> query("SELECT email FROM users WHERE email='$id'") -> num_rows > 0) {
		$response['id_match'] = true;
        $actual_pass = $mysqli -> query("SELECT password FROM users WHERE email='$id'") -> fetch_row()[0];
		if(password_verify($password, $actual_pass)) {
			$response['pass_match'] = true;
			$response['pass_hash'] = $actual_pass;
		}
	}
	
	echo json_encode($response);
?>