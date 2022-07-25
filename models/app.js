const createModelInputs = {
  name: document.getElementsByName("name")[0],
  brand_id: document.getElementsByName("brand_id")[0],
};

function validateModel(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(createModelInputs).forEach(
    (inputName) => (formData[inputName] = createModelInputs[inputName].value)
  );

  if (!validateModelFormData(formData)) {
    return;
  }

  document.getElementById("modelForm").submit();
}

function validateModelFormData(model) {
  let errors = {
    name: null,
    brand_id: null,
  };

  if (model.name.length < 1) {
    errors.name = "Please enter model name";
  }
  if (model.brand_id < 1) {
    errors.brand_id = "Please select brand";
  }

  for (let property of Object.keys(errors)) {
    if (!(errors[property] === null)) {
      displayModelErrors(errors);
      return false;
    }
  }

  return true;
}

function displayModelErrors(errors) {
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
