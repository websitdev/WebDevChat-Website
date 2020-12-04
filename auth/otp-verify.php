<?php
$response = array(
	'db_conn_succ' => true,
	'otp_valid' => true,
	'otp_match' => false
);
require("db-connect.php");

$otp = htmlspecialchars(trim($_POST['otp'])); //OTP

if(strlen($otp) != 6) {
	$response['otp_valid'] = false;
	$response['msg_otp'] = "OTP must be of 6 digits. You have entered ".strlen($otp);
	echo json_encode($response);
	exit();
}

$email = $mysqli -> real_escape_string(htmlspecialchars(trim($_POST['email']))); //email

$actual_otp = $mysqli -> query("SELECT otp FROM users WHERE email='$email'");
if($actual_otp && $actual_otp -> fetch_row()[0] == $otp) $response['otp_match'] = true;

echo json_encode($response);
?>