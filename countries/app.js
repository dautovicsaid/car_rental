const createCountryInputs = {
  name: document.getElementsByName("name")[0],
};

function validateCountry(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(createCountryInputs).forEach(
    (inputName) => (formData[inputName] = createCountryInputs[inputName].value)
  );

  if (!validateCountryFormData(formData)) {
    return;
  }

  document.getElementById("countryForm").submit();
}

function validateCountryFormData(country) {
  let errors = {
    name: null,
  };

  if (country.name.length < 1) {
    errors.name = "Please enter country name";
  }

  for (let property of Object.keys(errors)) {
    if (!(errors[property] === null)) {
      displayCountryErrors(errors);
      return false;
    }
  }

  return true;
}

function displayCountryErrors(errors) {
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
