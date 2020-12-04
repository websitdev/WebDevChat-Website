const request = new XMLHttpRequest();

function submitEmail(event) {
    event.preventDefault();
    if (!validateEmail(email_form['email'])) return;
    const form_data = new FormData(email_form);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) checkEmail(JSON.parse(this.responseText));
            else showDialog("Something went wrong.<br>Please try again later.");
            hideSpinner();
        }
    };
    request.open("POST", "otp-send.php");
    request.send(form_data);
}

function submitOTP(event) {
    event.preventDefault();
    if (!validateOTP(otp_form['otp'])) return;
    const form_data = new FormData(otp_form);
    form_data.append("email", email_form['email'].value);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) checkOTP(JSON.parse(this.responseText));
            else showDialog("Something went wrong.<br>Please try again later.");
            hideSpinner();
        }
    };
    request.open("POST", "otp-verify.php");
    request.send(form_data);
}

function submitNewPass(event) {
    event.preventDefault();
    if (!checkPass(new_pass_form['new-pass'])) return;
    const form_data = new FormData(new_pass_form);
    form_data.append("email", email_form['email'].value);
    form_data.append("otp", otp_form['otp'].value);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) checkNewPass(JSON.parse(this.responseText));
            else showDialog("Something went wrong.<br>Please try again later.");
            hideSpinner();
        }
    };
    request.open("POST", "set-new-pass.php");
    request.send(form_data);
}

function checkEmail(data) {
    if (!data.db_conn_succ) {
        showDialog("Could not connect to database at the moment.<br>Please try again later.");
        return;
    }
    if (data.email_valid) {
        if (data.email_exists) {
            if (data.otp_sent) {
                progress_bar.style.width = "33%";
                exitLeft(email_form, 500);
                setTimeout(() => enterRight(otp_form, 500), 500);
            } else showDialog("Something went wrong.<br>Please try again later.");
        } else errorShow(email_form['email'], "This email is not registered yet");
    } else errorShow(email_form['email'], data.msg_email);
}

function checkOTP(data) {
    if (!data.db_conn_succ) {
        showDialog("Could not connect to database at the moment.<br>Please try again later.");
        return;
    }
    if (data.otp_valid) {
        if (data.otp_match) {
            progress_bar.style.width = "66%";
            exitLeft(otp_form, 500);
            setTimeout(() => enterRight(new_pass_form, 500), 500);
        } else errorShow(otp_form['otp'], "OTP did not match the one we sent");
    } else errorShow(otp_form['otp'], data.msg_otp);
}

function checkNewPass(data) {
    if (!data.db_conn_succ) {
        showDialog("Could not connect to database at the moment.<br>Please try again later.");
        return;
    }
    if (data.pass_match) {
        if (data.pass_reset_succ) {
            progress_bar.style.width = "100%";
            showDialog("Your password has been resetted successfully!!!<br>You will be redirected to the login page.", 's');
            dialog.querySelector("button").addEventListener("click", () => location.replace("../login-page.html"));
        } else showDialog("Something went wrong.<br>Please try again later.");
    } else errorShow(new_pass_form['re-password'], "Confirmation password do not match the original password");
}

function cancelEmail() {
    const form_data = new FormData(email_form);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                progress_bar.style.width = "0";
                exitRight(otp_form, 500);
                setTimeout(() => enterLeft(email_form, 500), 500);
            } else showDialog("Something went wrong.<br>Please try again later.");
            hideSpinner();
        }
    };
    request.open("POST", "clear-otp.php");
    request.send(form_data);
}

function resendOTP() {
    if (!data.db_conn_succ) {
        showDialog("Could not connect to database at the moment.<br>Please try again later.");
        return;
    }
    const form_data = new FormData(email_form);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status != 200) showDialog("Something went wrong.<br>Please try again later.");
            hideSpinner();
        }
    };
    request.open("POST", "otp-send.php");
    request.send(form_data);
}