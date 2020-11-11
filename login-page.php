<?php
    //Connect to database
    //Uncomment before usage
    //$connection=new mysqli("db_server","db_uname","db_pass","db_name");
    //if($connection->connect_errno) {
    //    echo "<div style='background-color:#ffb5b5; border:2px solid red; border-radius:20px; position:relative; display:inline-block; padding:30px; left:50%; top:50%; transform:translate(-50%,-50%);'>
    //    Error: Cannot connect to database.Please try again later.
    //    </div>";
    //    exit();
    //}

    $identity=$login_pass=$name=$email=$username=$register_pass=$re_pass="";
    $err_identity=$err_login_pass=$err_name=$err_email=$err_uname=$err_pass_chk="";

    if($_SERVER["REQUEST_METHOD"]=="POST") {
        if(isset($_POST["login-btn"])) checkLogin();
        else checkRegister();
    }

    function checkLogin() {
        $identity=trim($_POST["identity"]); //username or email
        $login_pass=htmlspecialchars(trim($_POST["login-password"])); //password

        if(strpos($identity,' ')!==false) {
            $err_identity="Username or Email must not contain spaces";
            return;
        }

        //get values from database and check

        if(isset($_POST["remember"])) {
            setcookie("WEBDEVCHAT_IDENTITY",$identity,time()+31536000,"/");
            setcookie("WEBDEVCHAT_PASSWORD",password_hash($login_pass,PASSWORD_DEFAULT));
        }
    }

    function checkRegister() {
        $name=trim($_POST["name"]);
        $email=trim($_POST["email"]);
        $username=trim($_POST["username"]);
        $register_pass=htmlspecialchars(trim($_POST["register-password"]));
        $re_pass=htmlspecialchars(trim($_POST["re-password"]));

        if(!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $err_name="Name must not contain digits or special characters";
            return;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err_email="Invalid email ID";
            return;
        }
        if(!preg_match("/^[a-zA-Z]*$/",$username)){
            $err_uname="Username must not contain spaces";
            return;
        }
        if(!$register_pass==$re_pass) {
            $err_pass_chk="Passwords do not match";
            return;
        }

        //database work
    }
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>WebDevChat || Login Page</title>
    <link rel="icon" href="assets/img/login-page/icon.png" />
    <link rel="stylesheet" href="assets/css/login-page.css" />
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>

<body>
    <main>
        <section id="section1">
            <span class="heading">Welcome to<br>WebDevChat</span>
            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ligula risus, viverra ut eleifend nec, feugiat pretium eros.</span>
        </section>
        <section id="section2">
            <form id="login-form" method="POST" action="login-page.php">
                <span class="heading">Log in to WebDevChat</span>
                <div class="<?php echo $err_identity==''?'input-container':'input-container-error'; ?>">
                    <input type="text" name="identity" placeholder="Username or Email" onchange="validateIdentity(this)" oninput="errorHide(this)" required value="<?php echo $identity; ?>" />
                    <div class="err-msg"><?php echo $err_identity; ?></div>
                </div>
                <div class="<?php echo $err_login_pass==''?'input-container':'input-container-error'; ?> pass-input">
                    <input type="password" name="login-password" placeholder="Password" oninput="errorHide(this)" required value="<?php echo $login_pass; ?>" />
                    <button onclick="togglePasswordView(this)" class="bx bx-show" type="button"></button>
                    <div class="err-msg"><?php echo $err_login_pass; ?></div>
                </div>
                <div>
                    <input type="checkbox" name="remember" id="rem_chk" /><label for="rem_chk">Remember me</label>
                </div>
                <input type="submit" value="Log in" name="login-btn" />
                <hr/>
                <span>Log in with</span>
                <div class="other-accounts">
                    <a href="#" class="bx bxl-google"></a>
                    <a href="#" class="bx bxl-facebook"></a>
                    <a href="#" class="bx bxl-instagram"></a>
                    <a href="#" class="bx bxl-telegram"></a>
                    <a href="#" class="bx bxl-linkedin"></a>
                </div>
                <span class="form-toggle">Not a member? <a href="javascript:registerFormShow()">Register</a></span>
            </form>
            <form id="register-form" method="POST" action="login-page.php" hidden>
                <span class="heading">Register</span>
                <div class="<?php echo $err_name==''?'input-container':'input-container-error'; ?>">
                    <input type="text" name="name" placeholder="Name" onchange="validateName(this)" oninput="errorHide(this)" required value="<?php echo $name; ?>" />
                    <div class="err-msg"><?php echo $err_name; ?></div>
                </div>
                <div class="<?php echo $err_email==''?'input-container':'input-container-error'; ?>">
                    <input type="email" name="email" placeholder="Email ID" onchange="validateEmail(this)" oninput="errorHide(this)" required value="<?php echo $email; ?>" />
                    <div class="err-msg"><?php echo $err_email; ?></div>
                </div>
                <div class="<?php echo $err_uname==''?'input-container':'input-container-error'; ?>">
                    <input type="text" name="username" placeholder="Username" onchange="validateUsername(this)" oninput="errorHide(this)" required value="<?php echo $username; ?>" />
                    <div class="err-msg"><?php echo $err_uname; ?></div>
                </div>
                <div class="input-container pass-input">
                    <input type="password" name="register-password" placeholder="Password" onchange="getPass1(this)" required />
                    <button onclick="togglePasswordView(this)" class="bx bx-show" type="button"></button>
                </div>
                <div class="<?php echo $err_pass_chk==''?'input-container':"input-container-error"; ?>">
                    <div class="pass-input">
                        <input type="password" name="re-password" placeholder="Confirm password" onchange="checkPass(this)" oninput="errorHide(this.parentElement)" required />
                        <button onclick="togglePasswordView(this)" class="bx bx-show" type="button"></button>
                    </div>
                    <div class="err-msg"><?php echo $err_pass_chk; ?></div>
                </div>
                <input type="submit" value="Register" name="register-btn" />
                <hr/>
                <span>Register with</span>
                <div class="other-accounts">
                    <a href="#" class="bx bxl-google"></a>
                    <a href="#" class="bx bxl-facebook"></a>
                    <a href="#" class="bx bxl-instagram"></a>
                    <a href="#" class="bx bxl-telegram"></a>
                    <a href="#" class="bx bxl-linkedin"></a>
                </div>
                <span class="form-toggle">Already a member? <a href="javascript:loginFormShow()">Login</a></span>
            </form>
        </section>
    </main>
</body>
<script src="assets/js/login-page.js"></script>
<script src="assets/js/login-page-form-validation.js"></script>

</html>