<?php
	$response = array('db_err' => '', 'login_success' => false, 'username' => '', 'email' => '');
	require("auth/db-connect.php");

	$credentials = json_decode(file_get_contents("php://input"));
	$identity = $credentials -> id;
	$password = $credentials -> pass;

	$id = $mysqli -> real_escape_string($identity);

	if($mysqli -> query("SELECT username FROM users WHERE username='$id'") -> num_rows > 0) {
        $actual_pass = $mysqli -> query("SELECT password FROM users WHERE username='$id'") -> fetch_row()[0];
		if($password == $actual_pass) {
			$response['login_success'] = true;
			$response['username'] = $identity;
			$response['email'] = $mysqli -> query("SELECT email FROM users WHERE username='$id'") -> fetch_row()[0];
		}
	} //Check if email is present in database
	else if($mysqli -> query("SELECT email FROM users WHERE email='$id'") -> num_rows > 0) {
		$response['msg_id'] = "OK";
        $actual_pass = $mysqli -> query("SELECT password FROM users WHERE email='$id'") -> fetch_row()[0];
		if($password == $actual_pass) {
			$response['login_success'] = true;
			$response['email'] = $identity;
			$response['username'] = $mysqli -> query("SELECT username FROM users WHERE email='$id'") -> fetch_row()[0];
		}
	}
	
	echo json_encode($response);
?>