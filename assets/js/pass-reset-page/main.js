const main = document.querySelector("main"),
    email_form = document.forms['email-form'],
    otp_form = document.forms['otp-form'],
    new_pass_form = document.forms['new-pass-form'],
    progress_bar = document.getElementById("progress"),
    spinner = document.getElementById("spinner"),
    dialog = document.getElementById("dialog-container");

function togglePasswordView(elem) {
    pwd_box = elem.parentElement.children[0];
    if (pwd_box.hasAttribute('show')) {
        pwd_box.type = 'password'; //hide password
        pwd_box.removeAttribute('show');
        elem.classList.remove('bx-hide');
        elem.classList.add('bx-show');
    } else {
        pwd_box.type = 'text'; //show password
        pwd_box.setAttribute('show', '');
        elem.classList.remove('bx-show');
        elem.classList.add('bx-hide');
    }
}

function enterRight(elem, anim_duration) {
    elem.style.display = "block";
    elem.animate({
        opacity: [0, 1],
        transform: ['translateX(100%) rotateY(90deg)', 'translateX(0) rotateY(0)']
    }, {
        duration: anim_duration,
        fill: "forwards"
    });
}

function exitRight(elem, anim_duration) {
    elem.animate({
        opacity: [1, 0],
        transform: ['translateX(0) rotateY(0)', 'translateX(100%) rotateY(90deg)']
    }, {
        duration: anim_duration,
        fill: "forwards"
    });
    setTimeout(() => {
        elem.style.display = "none"
    }, anim_duration);
}

function exitLeft(elem, anim_duration) {
    elem.animate({
        opacity: [1, 0],
        transform: ['translateX(0) rotateY(0)', 'translateX(-100%) rotateY(-90deg)']
    }, {
        duration: anim_duration,
        fill: "forwards"
    });
    setTimeout(() => {
        elem.style.display = "none"
    }, anim_duration);
}

function enterLeft(elem, anim_duration) {
    elem.style.display = "block";
    elem.animate({
        opacity: [0, 1],
        transform: ['translateX(-100%) rotateY(-90deg)', 'translateX(0) rotateY(0)']
    }, {
        duration: anim_duration,
        fill: "forwards"
    });
}

dialog.querySelector("button").onclick = function() {
    dialog.children[0].animate({
        transform: ['scale(1)', 'scale(0)']
    }, {
        duration: 300,
        fill: "forwards"
    });
    dialog.animate({
        opacity: [1, 0]
    }, {
        duration: 200,
        fill: "forwards",
        delay: 300
    });
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
    dialog.querySelector("button").innerHTML = "OK";
    dialog.children[0].style.backgroundColor = type == 'f' ? "#ffaeae" : "lightgreen";
    dialog.style.display = "flex";
    dialog.animate({
        opacity: [0, 1]
    }, {
        duration: 200,
        fill: "forwards"
    });
    dialog.children[0].animate({
        transform: ['scale(0)', 'scale(1)']
    }, {
        duration: 300,
        fill: "forwards",
        delay: 200
    });
    main.style.filter = "blur(5px)";
}

function showSpinner() {
    spinner.style.display = "flex";
    spinner.animate({
        opacity: [0, 1]
    }, {
        duration: 100,
        fill: "forwards"
    });
    spinner.children[0].classList.add("fa-pulse");
    email_form.style.filter = "blur(3px)";
    otp_form.style.filter = "blur(3px)";
    new_pass_form.style.filter = "blur(3px)";
}

function hideSpinner() {
    spinner.animate({
        opacity: [1, 0]
    }, {
        duration: 100,
        fill: "forwards"
    });
    setTimeout(() => {
        spinner.children[0].classList.remove("fa-pulse");
        spinner.style.display = "none";
        email_form.style.filter = "none";
        otp_form.style.filter = "none";
        new_pass_form.style.filter = "none";
    }, 100);
}