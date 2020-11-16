<?php

//database credencials
$db_server_name="localhost";
$db_username=getenv('DB_USER');
$db_password=getenv('DB_PASSWORD');
$db_name="webdvxms_WebDevChat";

$db=mysqli_connect($db_server_name,$db_username,$db_password,$db_name);//the db conection


//error handeling
if (!$db) {
    die("Sql Connection Failed: " . mysqli_connect_error());
}
else{
	echo"success!!");
}

?>
