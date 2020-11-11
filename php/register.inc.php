<?php

include_once 'dbh.inc.php';

//register handeling

//this is a fake db it will be changed to mysql later
$db_userid=[1,2,3,4];
$db_username=["arydev5","arydel17","bob24","papy99"];
$db_email=["arydev5@mail.com","arydel17@mail.com","bob24@mail.com","papy99@mail.com"];
$db_pwd=["123456","pass","boby","papy99A"];


//this things are from the form
$form_username=$_POST["username"];
$form_email=$_POST["email"];
$form_pwd=$_POST["pwd"];//pwd stands for password
$form_pwd_conf=$POST["pwd_conf"];

//ceck if is in db
function in_db(){
    $used=0;
    for ($i=0; $i<sizeof($db_userid);$i++) {
        for ($username=0; $username <sizeof($db_userid); $username++) {
            if($db_username[$username]==$form_username){echo "usernmame used";$used++;}
        }
        for ($email=0;$email <sizeof($db_userid); $email++) {
            if($db_username[$username]==$form_username){echo "email used";$used++;}
        }
 }
 return $used;
}


//validete email comming soon


if ($form_pwd==$form_pwd_conf) {
    if (in_db()==0) {
        echo "you are registered";
    }else {
        echo"email or username is used";
    }

}
else {
    echo "error passwords dont mach";
    echo "<br>";
    echo $form_pwd;
    echo "<br>";
    echo $form_pwd_conf;
}
