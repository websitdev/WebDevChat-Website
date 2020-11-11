var pass1;

function errorShow(input, msg) {
    input.parentElement.classList.remove("input-container");
    input.parentElement.classList.add("input-container-error");
    input.parentElement.querySelector(".err-msg").innerText = msg;
    input.parentElement.parentElement.querySelector("input[type=submit]").setAttribute("disabled", '');
}

function errorHide(input) {
    input.parentElement.classList.add("input-container");
    input.parentElement.classList.remove("input-container-error");
    input.parentElement.querySelector(".err-msg").innerText = "";
    input.parentElement.parentElement.querySelector("input[type=submit]").removeAttribute("disabled");
}

function validateIdentity(input) {
    if (input.value == '') return;
    input.value = input.value.trim();
    errorHide(input);
    if (input.value.includes(' ')) errorShow(input, "Username or Email cannot have space");
}

function validateEmail(input) {
    if (input.value == '') return;
    input.value = input.value.trim();
    errorHide(input);
    if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(input.value)) errorShow(input, "Invalid email ID");
}

function validateName(input) {
    if (input.value == '') return;
    input.value = input.value.trim();
    errorHide(input);
    if (/\d/.test(input.value)) errorShow(input, "Name cannot have digits");
    if (!/^[A-Za-z0-9\s]+$/.test(input.value)) errorShow(input, "Name cannot have special characters");
}

function validateUsername(input) {
    if (input.value == '') return;
    input.value = input.value.trim();
    errorHide
    if (input.value.includes(' ')) errorShow(input, "Username cannot have space");
    else if (/^[A-Za-z0-9]+$/.test(input.value)) errorHide(input);
    else errorShow(input, "Username cannot have special characters");
}

function getPass1(input) {
    pass1 = input.value;
}

function checkPass(input) {
    if (input.value == '') return;
    errorHide(input.parentElement);
    if (input.value != pass1) {
        errorShow(input.parentElement, "Confirmation password do not match the original password");
        input.value = "";
    }
}