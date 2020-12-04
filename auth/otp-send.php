<?php
$response = array(
	'db_conn_succ' => true,
	'email_valid' => true,
	'email_exists' => true,
	'otp_sent' => false
);
require("db-connect.php");

$email = htmlspecialchars(trim($_POST['email'])); //email

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$response['email_valid'] = false;
	$response['msg_email'] = "Invalid email ID";
	echo json_encode($response);
	exit();
}

$email = $mysqli -> real_escape_string($email);

if($mysqli -> query("SELECT email FROM users WHERE email='$email'") -> num_rows == 0) {
	$response['email_exists'] = false;
	echo json_encode($response);
	exit();
}

//Generate a 6-digit OTP
$otp = "";
for($i = 1; $i <= 6; $i++) $otp .= rand(0, 9);

//Send email
$name = $mysqli -> query("SELECT name FROM users WHERE email='$email'") -> fetch_row()[0];
require("create-mail.php");
$headers = "MIME-Version: 1.0\nContent-type:text/html;charset=UTF-8\nFrom: $from";
$mail_sent = mail($email, $subject, $message, $headers);

//If successfully sent, insert the otp into database
if($mail_sent && $mysqli -> query("UPDATE users SET otp='$otp' WHERE email='$email'") === TRUE)
	$response['otp_sent'] = true;

echo json_encode($response);
?>