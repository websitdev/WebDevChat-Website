<?php
	$response = array('db_err' => '', 'msg_name' => '', 'msg_email' => '', 'msg_uname' => '', 'msg_pass' => '', 'reg_success' => false);
	require("db-connect.php");

    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
	$uname = htmlspecialchars(trim($_POST['username']));
	$password = htmlspecialchars(trim($_POST['password']));
    $re_pass = htmlspecialchars(trim($_POST['re-password']));

    $response['msg_name'] = preg_match("/^[a-zA-Z-' ]*$/", $name)? "OK" : "Name must not contain digits or special characters";
    $response['msg_email'] = filter_var($email, FILTER_VALIDATE_EMAIL)? "OK" : "Invalid email ID";
	$response['msg_uname'] = preg_match("/^[a-zA-Z0-9 ]*$/", $uname)? "OK" : "Invalid username";
	$response['msg_pass'] = $password == $re_pass? "OK" : "Passwords do not match";
	
	if($response['msg_name'] != 'OK' || $response['msg_email'] != 'OK' || $response['msg_uname'] != 'OK' || $response['msg_pass'] != 'OK') {
		echo json_encode($response);
		exit();
	}

    $name = $mysqli -> real_escape_string($name);
    $email = $mysqli -> real_escape_string($email);
	$uname = $mysqli -> real_escape_string($uname);
	$password = $mysqli -> real_escape_string(password_hash($password, PASSWORD_DEFAULT));

	//Check if credentials are already present in database
	if($mysqli -> query("SELECT name FROM users WHERE name='$name'") -> num_rows > 0) $response['msg_name'] = "AE";
	if($mysqli -> query("SELECT email FROM users WHERE email='$email'") -> num_rows > 0) $response['msg_email'] = "AE";
	if($mysqli -> query("SELECT username FROM users WHERE username='$uname'") -> num_rows > 0) $response['msg_uname'] = "AE";

	if($response['msg_name'] == "AE" && $response['msg_email'] == "AE" && $response['msg_uname'] == "AE") {
		echo json_encode($response);
		exit();
	} else if($response['msg_email'] == "AE" || $response['msg_uname'] == "AE") {
		echo json_encode($response);
		exit();
	}

	//Create an entry for current user
	if($mysqli -> query("INSERT INTO users (name, email, username, password) VALUES ('$name', '$email', '$uname', '$password')") === TRUE)
		$response['reg_success'] = true;

	echo json_encode($response);
?>