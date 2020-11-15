<?php
	$response = array('db_err' => '', 'msg_id' => '', 'msg_pass' => '', 'pass_hash' => '');
	require("db-connect.php");
	
	$identity = htmlspecialchars(trim($_POST['identity'])); //username or email
    $password = htmlspecialchars(trim($_POST['password'])); //password
	
    if(strpos($identity, ' ')) {
		$response['msg_id'] = "Username or Email must not contain spaces";
		echo json_encode($response);
		exit();
	}
    $id = $mysqli -> real_escape_string($identity);

    //Check if username is present in database
    if($mysqli -> query("SELECT username FROM users WHERE username='$id'") -> num_rows > 0) {
		$response['msg_id'] = "OK";
        $actual_pass = $mysqli -> query("SELECT password FROM users WHERE username='$id'") -> fetch_row()[0];
		if(password_verify($password, $actual_pass)) {
			$response['msg_pass'] = "OK";
			$response['pass_hash'] = $actual_pass;
		}
		else $response['msg_pass'] = "Incorrect password";
	} //Check if email is present in database
	else if($mysqli -> query("SELECT email FROM users WHERE email='$id'") -> num_rows > 0) {
		$response['msg_id'] = "OK";
        $actual_pass = $mysqli -> query("SELECT password FROM users WHERE email='$id'") -> fetch_row()[0];
		if(password_verify($password, $actual_pass)) {
			$response['msg_pass'] = "OK";
			$response['pass_hash'] = $actual_pass;
		}
		else $response['msg_pass'] = "Incorrect password";
	} else $response['msg_id'] = "This username or email is not resgistered yet.<br>If you are a new user, please register yourself.";
	
	echo json_encode($response);
?>