<?php
$response = array(
	'db_conn_succ' => true,
	'pass_match' => true,
	'pass_reset_succ' => false
);
require("db-connect.php");

$new_pass = htmlspecialchars(trim($_POST['new-pass'])); //password
$re_password = htmlspecialchars(trim($_POST['re-password'])); //confirmation password

if($new_pass != $re_password) {
	$response['pass_match'] = false;
	echo json_encode($response);
	exit();
}

$email = $mysqli -> real_escape_string(htmlspecialchars(trim($_POST['email']))); //email
if($mysqli -> query("SELECT email FROM users WHERE email='$email'") -> num_rows > 0) {
	$otp = htmlspecialchars(trim($_POST['otp'])); //OTP
	$actual_otp = $mysqli -> query("SELECT otp FROM users WHERE email='$email'");
	if($actual_otp && $actual_otp -> fetch_row()[0] == $otp) {
		$new_pass = $mysqli -> real_escape_string(password_hash($new_pass, PASSWORD_DEFAULT));
		if($mysqli -> query("UPDATE users SET password='$new_pass' WHERE email='$email'") === TRUE) $response['pass_reset_succ'] = true;
	}
}

echo json_encode($response);
?>