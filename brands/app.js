const createBrandInputs = {
  name: document.getElementsByName("name")[0],
};

function validateBrand(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(createBrandInputs).forEach(
    (inputName) => (formData[inputName] = createBrandInputs[inputName].value)
  );

  if (!validateBrandFormData(formData)) {
    return;
  }

  document.getElementById("brandForm").submit();
}

function validateBrandFormData(brand) {
  let errors = {
    name: null,
  };

  if (brand.name.length < 1) {
    errors.name = "Please enter brand name";
  }

  for (let property of Object.keys(errors)) {
    if (!(errors[property] === null)) {
      displayBrandErrors(errors);
      return false;
    }
  }

  return true;
}

function displayBrandErrors(errors) {
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
