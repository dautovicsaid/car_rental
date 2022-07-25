const loginInputs = {
  username: document.getElementsByName("username")[0],
  password: document.getElementsByName("password")[0],
};

const registerInputs = {
  first_name: document.getElementsByName("first_name")[0],
  last_name: document.getElementsByName("last_name")[0],
  username: document.getElementsByName("username")[0],
  password: document.getElementsByName("password")[0],
  confirm_password: document.getElementsByName("confirm_password")[0],
  passport_number: document.getElementsByName("confirm_password")[0],
  country_id: document.getElementsByName("country_id")[0],
};

function validateLogin(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(loginInputs).forEach(
    (inputName) => (formData[inputName] = loginInputs[inputName].value)
  );

  if (!validateLoginFormData(formData)) {
    return;
  }

  document.getElementById("loginForm").submit();
}

function validateLoginFormData(login) {
  let errors = {
    username: null,
    password: null,
  };

  if (login.password.length < 8) {
    errors.password = "Password must contain at least 8 letter";
  }
  if (login.username.length < 1) {
    errors.username = "Enter username";
  }

  for (let property of Object.keys(errors)) {
    if (!(errors[property] === null)) {
      displayLoginErrors(errors);
      return false;
    }
  }

  return true;
}

function displayLoginErrors(errors) {
  for (let property of Object.keys(errors)) {
    let errorMessage = errors[property];
    let errorSpan = document.getElementById(`error-${property}`);

    if (!(errorMessage == null)) {
      errorSpan.classList.remove("d-none");
      errorSpan.innerHTML = errorMessage;
    } else {
      errorSpan.innerHTML = "";
      errorSpan.classList.add("d-none");
    }
  }
}

function validateRegister(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(registerInputs).forEach(
    (inputName) => (formData[inputName] = registerInputs[inputName].value)
  );

  if (!validateRegisterFormData(formData)) {
    return;
  }

  document.getElementById("registerForm").submit();
}

function validateRegisterFormData(register) {
  let errors = {
    first_name: null,
    last_name: null,
    username: null,
    password: null,
    confirm_password: null,
    passport_number: null,
    country_id: null,
  };

  if (register.first_name.length < 1) {
    errors.first_name = "Enter first name";
  }
  if (register.last_name.length < 1) {
    errors.last_name = "Enter last name";
  }
  if (register.username.length < 1) {
    errors.username = "Enter username";
  }
  if (register.password.length < 8 || register.password.length == NaN) {
    errors.password = "Password must contain at least 8 letter";
  }

  if (
    register.confirm_password != register.password ||
    register.confirm_password.length == NaN
  ) {
    errors.confirm_password = "Confirm password must match";
  }

  if (register.passport_number < 1) {
    errors.passport_number = "Enter passport number";
  }

  if (register.country_id < 1) {
    errors.country_id = "Select country";
  }

  for (let property of Object.keys(errors)) {
    if (!(errors[property] === null)) {
      displayRegisterErrors(errors);
      return false;
    }
  }

  return true;
}

function displayRegisterErrors(errors) {
  for (let property of Object.keys(errors)) {
    let errorMessage = errors[property];
    let errorSpan = document.getElementById(`error-${property}`);

    if (!(errorMessage == null)) {
      errorSpan.classList.remove("d-none");
      errorSpan.innerHTML = errorMessage;
    } else {
      errorSpan.innerHTML = "";
      errorSpan.classList.add("d-none");
    }
  }
}
