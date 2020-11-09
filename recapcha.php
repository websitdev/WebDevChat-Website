<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Google reCAPTCHA v3</title>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js?render=6Le67IoUAAAAAOS9zjG8l9773rGxGpV7aT0WWlO1'></script>

    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('YOUR_KEY', { action: 'contact' })
                .then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                console.log(recaptchaResponse)
                recaptchaResponse.value = token;
            });
        });
    </script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="panel panel-login">
            <div class="panel-heading">
                <div class="row">

                </div>
                <hr>
            </div>
            <div class="panel-body">
                <div class="row">
        <div class="col-md-6 col-md-offset-3">

                    <?php // Check if form was submitted:
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Build POST request:
                        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
                        $recaptcha_secret = '6LdcxeAZAAAAACnDP8PpVa6k-sR0yYZAD6i8I3vx';
                        $recaptcha_response = $_POST['recaptcha_response'];

                        // Make and decode POST request:
                        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
                        $recaptcha = json_decode($recaptcha);
                        if($recaptcha->success==true){
                            // Take action based on the score returned:
                            if ($recaptcha->score >= 0.5) {
                                echo '<pre>';
                                print_r("Verified - send email");
                                echo '</pre>';
                                exit;

                                // Verified - send email
                            } else {
                                echo '<pre>';
                                print_r("Not verified - show form error");
                                echo '</pre>';
                                exit;
                                // Not verified - show form error
                            }
                        }else{ // there is an error /
                            ///  timeout-or-duplicate meaning you are submit the  form
                            echo '<pre>';
                            print_r($recaptcha);
                            echo '</pre>';
                            exit;

                        }
                    }
                 ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>

                        <input type="hidden" value="" name="recaptcha_response" id="recaptchaResponse">

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
      </div>
      </div>
 </div>

</body>

</html>
