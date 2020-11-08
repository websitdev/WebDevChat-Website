const register_form = document.getElementById("register-form"),
    login_form = document.getElementById("login-form");

function registerFormShow() {
    login_form.setAttribute('hidden', '');
    register_form.removeAttribute('hidden');
}

function loginFormShow() {
    register_form.setAttribute('hidden', '');
    login_form.removeAttribute('hidden');
}

function showpass() {
  var x =
  document.getElementById('passwordd');
  if (x.type ==="password") {
    x.type = "text";
  }
  else {
    x.type = "password";
  }
};

function showpasss() {
  var x =
  document.getElementById('passworddd');
  if (x.type ==="password") {
    x.type = "text";
  }
  else {
    x.type = "password";
  }
};
