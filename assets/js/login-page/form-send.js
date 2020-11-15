const request = new XMLHttpRequest();
const main = document.querySelector("main"),
    forms_section = document.getElementById("section2"),
    login_form = document.forms['login-form'],
    register_form = document.forms['register-form'],
    dialog = document.getElementById("dialog-container"),
    spinner = document.getElementById("spinner");

dialog.querySelector("button").onclick = function() {
    dialog.children[0].animate({ transform: ['scale(1)', 'scale(0)'] }, { duration: 300, fill: "forwards" });
    dialog.animate({ opacity: [1, 0] }, { duration: 200, fill: "forwards", delay: 300 });
    setTimeout(() => {
        dialog.style.display = "none";
        main.style.filter = "none";
    }, 500);
}

function showDialog(msg, type = 'success') {
    dialog.children[0].children[0].innerHTML = msg;
    dialog.children[0].style.backgroundColor = type == 'success' ? "lightgreen" : "#ffaeae";
    dialog.style.display = "flex";
    dialog.animate({ opacity: [0, 1] }, { duration: 200, fill: "forwards" });
    dialog.children[0].animate({ transform: ['scale(0)', 'scale(1)'] }, { duration: 300, fill: "forwards", delay: 200 });
    main.style.filter = "blur(5px)";
}

function showSpinner() {
    spinner.style.display = "flex";
    spinner.animate({ opacity: [0, 1] }, { duration: 100, fill: "forwards" });
    spinner.children[0].classList.add("fa-pulse");
    forms_section.style.filter = "blur(3px)";
}

function hideSpinner() {
    spinner.animate({ opacity: [1, 0] }, { duration: 100, fill: "forwards" });
    setTimeout(() => {
        spinner.children[0].classList.remove("fa-pulse");
        spinner.style.display = "none";
        forms_section.style.filter = "none";
    }, 100);
}

function submitLogin(event) {
    event.preventDefault();
    const form_data = new FormData(login_form);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) checkLogin(JSON.parse(this.responseText));
            else showDialog("Something went wrong.<br>Please try again later.", "failure");
            hideSpinner();
        }
    };
    request.open("POST", "auth/login.php");
    request.send(form_data);
}

function submitRegister(event) {
    event.preventDefault();
    const form_data = new FormData(register_form);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) checkRegister(JSON.parse(this.responseText));
            else showDialog("Something went wrong.<br>Please try again later.", "failure");
            hideSpinner();
        }
    };
    request.open("POST", "auth/register.php");
    request.send(form_data);
}

function checkLogin(data) {
    if (data.db_err != '') {
        //Database connection error has occured
        showDialog(data.db_err, "failure");
        return;
    }
    if (data.msg_id == 'OK') {
        if (data.msg_pass == 'OK') {
            //Store login credentials in session storage or local storage depending on the value of "Remember me"
            const credentials = { id: login_form['identity'].value, pass: data.pass_hash };
            if (login_form['remember'].checked) localStorage.setItem("user", JSON.stringify(credentials));
            else sessionStorage.setItem("user", JSON.stringify(credentials));

            //Redirect user to dashboard
            location.replace("dashboard.html");
        } else errorShow(login_form['password'].parentElement, data.msg_pass);
    } else errorShow(login_form['identity'], data.msg_id);
}

function checkRegister(data) {
    if (data.db_err != '') {
        //Database connection error has occured
        showDialog(data.db_err, "failure");
        return;
    }
    if (data.reg_success) {
        showDialog("You have been registered successfully at WebDevChat!!!<br>Now login to access your account.");
    } else {
        if (data.msg_name == 'AE' && data.msg_email == 'AE' && data.msg_uname == 'AE') {
            showDialog("Looks like you are already registered.<br>Please log in to access your account.", "failure");
        } else {
            if (data.msg_email != 'OK') errorShow(register_form['email'], data.msg_email);
            if (data.msg_uname != 'OK') errorShow(register_form['username'], data.msg_uname);
            if (data.msg_pass != 'OK') errorShow(register_form['re-password'].parentElement, data.msg_pass);
        }
    }
}