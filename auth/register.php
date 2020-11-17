<?php
	$response = array(
		'db_conn_succ' => true,
		'name_valid' => true,
		'email_valid' => true,
		'uname_valid' => true,
		'pass_match' => true,
		'name_uniq' => false,
		'email_uniq' => false,
		'uname_uniq' => false,
		'reg_succ' => false
	);
	require("db-connect.php");

    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
	$uname = htmlspecialchars(trim($_POST['username']));
	$password = htmlspecialchars(trim($_POST['register-password']));
    $re_pass = htmlspecialchars(trim($_POST['re-password']));

	if(!preg_match("/^[a-zA-Z-'. ]*$/", $name)) {
		$response['name_valid'] = false;
		$response['msg_name'] = "Name must not contain digits or special characters";
	}

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response['email_valid'] = false;
		$response['msg_email'] = "Invalid email ID";
	}

	if(!preg_match("/^[a-zA-Z0-9]*$/", $uname)) {
		$response['uname_valid'] = false;
		$response['msg_uname'] = "Username must not contain spaces or special characters";
	}
	
	if($password != $re_pass) $response['pass_match'] = false;
	
	if(!$response['name_valid'] || !$response['email_valid'] || !$response['uname_valid'] || !$response['pass_match']) {
		echo json_encode($response);
		exit();
	}

    $name = $mysqli -> real_escape_string($name);
    $email = $mysqli -> real_escape_string($email);
	$uname = $mysqli -> real_escape_string($uname);
	$password = $mysqli -> real_escape_string(password_hash($password, PASSWORD_DEFAULT));

	//Check if credentials are already present in database
	if($mysqli -> query("SELECT email FROM users WHERE email='$email'") -> num_rows == 0) $response['email_uniq'] = true;
	if($mysqli -> query("SELECT username FROM users WHERE username='$uname'") -> num_rows == 0) $response['uname_uniq'] = true;

	if(!$response['uname_uniq'] || !$response['email_uniq']) {
		echo json_encode($response);
		exit();
	}

	//Create an entry for current user
	if($mysqli -> query("INSERT INTO users (name, email, username, password) VALUES ('$name', '$email', '$uname', '$password')") === TRUE)
		$response['reg_succ'] = true;

	echo json_encode($response);
?>