<?php
$response = array(
	'db_conn_succ' => true
);

if($response['db_conn_succ']) {
	$email = $mysqli -> real_escape_string(htmlspecialchars(trim($_POST['email'])));
	$mysqli -> query("UPDATE users SET otp=null WHERE email='$email'");
}
?>