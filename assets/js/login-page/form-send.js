const request = new XMLHttpRequest();
const main = document.querySelector("main"),
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
        dialog.children[0].children[0].innerHTML = "";
    }, 500);
}

function showDialog(msg, type = 'f') {
    const div = document.createElement("div");
    div.innerHTML = msg;
    dialog.children[0].children[0].appendChild(div);
    dialog.children[0].style.backgroundColor = type == 'f' ? "#ffaeae" : "lightgreen";
    dialog.style.display = "flex";
    dialog.animate({ opacity: [0, 1] }, { duration: 200, fill: "forwards" });
    dialog.children[0].animate({ transform: ['scale(0)', 'scale(1)'] }, { duration: 300, fill: "forwards", delay: 200 });
    main.style.filter = "blur(5px)";
}

function showSpinner() {
    spinner.style.display = "flex";
    spinner.animate({ opacity: [0, 1] }, { duration: 100, fill: "forwards" });
    spinner.children[0].classList.add("fa-pulse");
    login_form.style.filter = "blur(3px)";
    register_form.style.filter = "blur(3px)";
}

function hideSpinner() {
    spinner.animate({ opacity: [1, 0] }, { duration: 100, fill: "forwards" });
    setTimeout(() => {
        spinner.children[0].classList.remove("fa-pulse");
        spinner.style.display = "none";
        login_form.style.filter = "none";
        register_form.style.filter = "none";
    }, 100);
}

function submitLogin(event) {
    event.preventDefault();
    const form_data = new FormData(login_form);
    showSpinner();
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) checkLogin(JSON.parse(this.responseText));
            else showDialog("Something went wrong.<br>Please try again later.");
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
            else showDialog("Something went wrong.<br>Please try again later.");
            hideSpinner();
        }
    };
    request.open("POST", "auth/register.php");
    request.send(form_data);
}

function checkLogin(data) {
    if (!data.db_conn_succ) {
        //Database connection error has occured
        showDialog("Could not connect to database at the moment.<br>Please try again later.");
        return;
    }
    if (!data.id_valid) { errorShow(login_form['identity'], data.msg_id); return; }
    if (data.id_match) {
        if (data.pass_match) {
            if (login_form['remember'].checked) {
                //Store login credentials into localStorage
                var credentials = localStorage.getItem("users");
                if (credentials == null) credentials = [{ id: login_form['identity'].value, pass: data.pass_hash }];
                else {
                    credentials = JSON.parse(credentials);
                    var already_exist = false;
                    credentials.forEach(user => { already_exist = user.id === login_form['identity'].value ? true : false; });
                    if (!already_exist) credentials.push({ id: login_form['identity'].value, pass: data.pass_hash });
                }
                localStorage.setItem("users", JSON.stringify(credentials));
            } else {
                //Store login credentials into sessionStorage
                var credentials = sessionStorage.getItem("users");
                if (credentials == null) credentials = [{ id: login_form['identity'].value, pass: data.pass_hash }];
                else {
                    credentials = JSON.parse(credentials);
                    credentials.push({ id: login_form['identity'].value, pass: data.pass_hash });
                }
                sessionStorage.setItem("users", JSON.stringify(credentials));
            }

            //Redirect user to dashboard
            location.replace(`dashboard.html?id=${login_form['identity'].value}`);
        } else errorShow(login_form['login-password'].parentElement, "Incorrect password");
    } else errorShow(login_form['identity'], "This username is not registered yet. If you are a new user, please register.");
}

function checkRegister(data) {
    if (!data.db_conn_succ) {
        //Database connection error has occured
        showDialog("Could not connect to database at the moment.<br>Please try again later.");
        return;
    }
    if (!data.name_valid) { errorShow(register_form['name'], data.msg_name); }
    if (!data.email_valid) { errorShow(register_form['email'], data.msg_email); }
    if (!data.uname_valid) { errorShow(register_form['username'], data.msg_uname); }
    if (!data.pass_match) { errorShow(register_form['re-password'], "Confirmation password do not match the original password"); }
    if (!data.name_valid || !data.email_valid || !data.uname_valid || !data.pass_match) return;

    if (!data.email_uniq) errorShow(register_form['email'], "This email ID is already taken");
    if (!data.uname_uniq) errorShow(register_form['username'], "This username is already taken");
    if (!data.uname_uniq || !data.email_uniq) return;

    if (data.reg_succ)
        showDialog("You have been registered successfully at WebDevChat!!!<br>Now login to access your account.", 's');
    else
        showDialog("Something went wrong.<br>Please try again later or contact us.");
}