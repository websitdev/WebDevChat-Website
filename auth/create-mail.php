<?php
$from = "webdevchat.com";
$subject = "OTP for password reset";

$message = "
<html>
<body style='text-align:center; padding:20px;'>
	Hello $name,<br>
	&nbsp;&nbsp;&nbsp;We have received request for resetting your WebDevChat account password.<br>
	Use this OTP to reset your password:<br>
	<span style='font-size:1.5em; color:#0074f9;>$otp</span><br><br>
	You can ignore this message if this wasn't you.<br><br>
	Regards,
	WebDev team
</body>
</html>
"
?>